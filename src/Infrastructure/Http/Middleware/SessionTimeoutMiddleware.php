<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Middleware;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ResponsiveSk\Slim4Paths\Paths;

class SessionTimeoutMiddleware implements MiddlewareInterface
{
    private array $config;
    private Paths $paths;

    public function __construct(?array $config = null, ?Paths $paths = null)
    {
        $this->paths = $paths ?? new Paths(__DIR__ . '/../../../../');
        $this->config = $config ?? require $this->paths->getPath('config') . '/session.php';
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->startSecureSession();

        $now = time();
        $path = $request->getUri()->getPath();

        // 1. Session expiry s mark/admin rozlíšením
        if (isset($_SESSION['last_activity'])) {
            $timeout = $this->getTimeoutForPath($path);

            if (($now - $_SESSION['last_activity']) > $timeout) {
                $this->handleSessionExpiry($request);

                return $this->redirectToLoginWithMessage('Session expired');
            }
        }

        // 2. Fingerprint validation (iba pre prihlásených)
        if ($this->shouldValidateFingerprint()) {
            if (!$this->validateSessionFingerprint($request)) {
                $this->handleSecurityViolation($request);

                return $this->redirectToLoginWithMessage('Security violation detected');
            }
        }

        // 3. Session ID regeneration pre mark používateľov
        $this->maybeRegenerateSessionId($now);

        // 4. Optimalizovaný update last_activity
        $this->updateLastActivityIfNeeded($now);

        $response = $handler->handle($request);

        // 5. Secure cookies
        return $this->applySecureCookieHeaders($response);
    }

    private function startSecureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $cookieParams = $this->config['cookie_params'];

            session_set_cookie_params($cookieParams);
            session_name($this->config['name'] ?? 'resp_blog_session'); // Use config name
            session_start();
        }
    }

    private function getTimeoutForPath(string $path): int
    {
        // Rozlišujeme mark (admin) vs normálne cesty
        if ($this->isMarkPath($path)) {
            return $this->config['timeout']['mark'];
        }

        // API cesty
        if ($this->isApiPath($path)) {
            return $this->config['timeout']['api'];
        }

        return $this->config['timeout']['default'];
    }

    private function isMarkPath(string $path): bool
    {
        return str_starts_with($path, '/mark') || str_contains($path, '/mark/');
    }

    private function isApiPath(string $path): bool
    {
        return str_starts_with($path, '/api');
    }

    private function shouldValidateFingerprint(): bool
    {
        return $this->config['fingerprint']['enabled'] &&
               isset($_SESSION['user_id'], $_SESSION['fingerprint']);
    }

    private function validateSessionFingerprint(ServerRequestInterface $request): bool
    {
        $current = $this->calculateFingerprint($request);

        return hash_equals($_SESSION['fingerprint'], $current);
    }

    private function calculateFingerprint(ServerRequestInterface $request): string
    {
        $components = $this->config['fingerprint']['components'];
        $data = [];

        foreach ($components as $component) {
            switch ($component) {
                case 'user_agent':
                    $data[] = $request->getHeaderLine('User-Agent');

                    break;

                case 'ip_subnet': // Len subnet pre lepšiu UX
                    $ip = $request->getServerParams()['REMOTE_ADDR'] ?? '0.0.0.0';
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                        $data[] = substr($ip, 0, strrpos($ip, '.'));
                    }

                    break;

                case 'accept_language':
                    $data[] = $request->getHeaderLine('Accept-Language');

                    break;
            }
        }

        // Salt z configu pre vyššiu bezpečnosť
        $data[] = $this->config['fingerprint']['salt'];

        return hash('sha256', implode('|', $data));
    }

    private function maybeRegenerateSessionId(int $now): void
    {
        // Regenerácia session ID pre mark používateľov
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'mark') {
            if (!isset($_SESSION['regenerated_at'])) {
                $_SESSION['regenerated_at'] = $now;
            } elseif (($now - $_SESSION['regenerated_at']) > 300) { // 5 min
                session_regenerate_id(true);
                $_SESSION['regenerated_at'] = $now;
            }
        }
    }

    private function updateLastActivityIfNeeded(int $now): void
    {
        // Optimalizácia: update iba ak uplynulo >60 sekúnd
        if (!isset($_SESSION['last_activity']) || ($now - $_SESSION['last_activity']) > 60) {
            $_SESSION['last_activity'] = $now;
        }
    }

    private function handleSessionExpiry(ServerRequestInterface $request): void
    {
        $userId = $_SESSION['user_id'] ?? 'unknown';
        $ip = $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown';

        error_log("[SESSION] Expired for user {$userId} from IP {$ip}");
        $this->destroySession();
    }

    private function handleSecurityViolation(ServerRequestInterface $request): void
    {
        $userId = $_SESSION['user_id'] ?? 'unknown';
        $ip = $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown';
        $ua = substr($request->getHeaderLine('User-Agent'), 0, 100);

        error_log("[SECURITY] Fingerprint mismatch for user {$userId} - IP: {$ip}, UA: {$ua}");
        $this->destroySession();
    }

    private function destroySession(): void
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }

    private function redirectToLoginWithMessage(string $message): ResponseInterface
    {
        // Flash message do session
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['flash_error'] = $message;

        return new Response(302, ['Location' => '/login']);
    }

    private function applySecureCookieHeaders(ResponseInterface $response): ResponseInterface
    {
        $needsSecure = $this->config['cookie_params']['secure'] ?? false;

        if ($needsSecure && session_status() === PHP_SESSION_ACTIVE) {
            $cookieParams = session_get_cookie_params();

            $cookieHeader = sprintf(
                '%s=%s; Path=%s; Max-Age=%s; HttpOnly; SameSite=%s%s',
                session_name(),
                session_id(),
                $cookieParams['path'],
                $cookieParams['lifetime'],
                $cookieParams['samesite'],
                '; Secure'
            );

            return $response->withHeader('Set-Cookie', $cookieHeader);
        }

        return $response;
    }
}
