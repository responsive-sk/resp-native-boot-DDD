<?php

declare(strict_types=1);

namespace Tests\Unit\Security;

use Blog\Security\AuthorizationService;
use Blog\Security\Exception\AuthenticationException;
use Blog\Security\Exception\AuthorizationException;
use PHPUnit\Framework\TestCase;
use ResponsiveSk\Slim4Session\SessionInterface;

final class AuthorizationServiceTest extends TestCase
{
    private AuthorizationService $authorization;
    private SessionInterface $session;

    protected function setUp(): void
    {
        $this->session = $this->createMockSession();
        $this->authorization = new AuthorizationService($this->session);
    }

    public function test_is_authenticated_returns_true_when_user_data_exists(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['user_id' => true],
                ['user_role' => true]
            ]);

        $this->assertTrue($this->authorization->isAuthenticated());
    }

    public function test_is_authenticated_returns_false_when_user_data_missing(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['user_id' => true],
                ['user_role' => false]
            ]);

        $this->assertFalse($this->authorization->isAuthenticated());
    }

    public function test_get_user_returns_user_data_when_authenticated(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['user_id' => true],
                ['user_role' => true]
            ]);

        $this->session->method('get')
            ->willReturnMap([
                ['user_id' => 'user-123'],
                ['user_role' => 'ROLE_USER']
            ]);

        $user = $this->authorization->getUser();

        $this->assertSame([
            'id' => 'user-123',
            'role' => 'ROLE_USER'
        ], $user);
    }

    public function test_get_user_returns_null_when_not_authenticated(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['user_id' => false],
                ['user_role' => false]
            ]);

        $user = $this->authorization->getUser();

        $this->assertNull($user);
    }

    public function test_has_role_returns_true_for_matching_role(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['user_id' => true],
                ['user_role' => true]
            ]);

        $this->session->method('get')
            ->willReturn('ROLE_USER');

        $this->assertTrue($this->authorization->hasRole('user'));
        $this->assertTrue($this->authorization->hasRole('ROLE_USER'));
    }

    public function test_has_role_returns_false_for_non_matching_role(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['user_id' => true],
                ['user_role' => true]
            ]);

        $this->session->method('get')
            ->willReturn('ROLE_USER');

        $this->assertFalse($this->authorization->hasRole('admin'));
        $this->assertFalse($this->authorization->hasRole('ROLE_ADMIN'));
    }

    public function test_has_role_returns_false_when_not_authenticated(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['user_id' => false],
                ['user_role' => false]
            ]);

        $this->assertFalse($this->authorization->hasRole('user'));
    }

    public function test_is_mark_returns_true_for_mark_session_flag(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['mark_session' => true],
                ['user_id' => true],
                ['user_role' => true]
            ]);

        $this->session->method('get')
            ->willReturnMap([
                ['mark_session' => true],
                ['user_role' => 'ROLE_USER']
            ]);

        $this->assertTrue($this->authorization->isMark());
    }

    public function test_is_mark_returns_true_for_mark_role(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['mark_session' => false],
                ['user_id' => true],
                ['user_role' => true]
            ]);

        $this->session->method('get')
            ->willReturnMap([
                ['mark_session' => false],
                ['user_role' => 'ROLE_MARK']
            ]);

        $this->assertTrue($this->authorization->isMark());
    }

    public function test_is_mark_returns_false_for_user_role(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['mark_session' => false],
                ['user_id' => true],
                ['user_role' => true]
            ]);

        $this->session->method('get')
            ->willReturnMap([
                ['mark_session' => false],
                ['user_role' => 'ROLE_USER']
            ]);

        $this->assertFalse($this->authorization->isMark());
    }

    public function test_require_auth_throws_exception_when_not_authenticated(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['user_id' => false],
                ['user_role' => false]
            ]);

        $this->expectException(AuthenticationException::class);
        $this->authorization->requireAuth();
    }

    public function test_require_auth_does_not_throw_when_authenticated(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['user_id' => true],
                ['user_role' => true]
            ]);

        $this->expectNotToPerformAssertions();
        $this->authorization->requireAuth();
    }

    public function test_require_role_throws_exception_when_not_authenticated(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['user_id' => false],
                ['user_role' => false]
            ]);

        $this->expectException(AuthenticationException::class);
        $this->authorization->requireRole('admin');
    }

    public function test_require_role_throws_exception_when_wrong_role(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['user_id' => true],
                ['user_role' => true]
            ]);

        $this->session->method('get')
            ->willReturn('ROLE_USER');

        $this->expectException(AuthorizationException::class);
        $this->authorization->requireRole('admin');
    }

    public function test_require_role_does_not_throw_when_correct_role(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['user_id' => true],
                ['user_role' => true]
            ]);

        $this->session->method('get')
            ->willReturn('ROLE_USER');

        $this->expectNotToPerformAssertions();
        $this->authorization->requireRole('user');
    }

    public function test_require_mark_throws_exception_when_not_mark(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['mark_session' => false],
                ['user_id' => true],
                ['user_role' => true]
            ]);

        $this->session->method('get')
            ->willReturn('ROLE_USER');

        $this->expectException(AuthorizationException::class);
        $this->authorization->requireMark();
    }

    public function test_require_mark_does_not_throw_when_mark(): void
    {
        $this->session->method('has')
            ->willReturnMap([
                ['mark_session' => false],
                ['user_id' => true],
                ['user_role' => true]
            ]);

        $this->session->method('get')
            ->willReturn('ROLE_MARK');

        $this->expectNotToPerformAssertions();
        $this->authorization->requireMark();
    }

    public function test_set_mark_session(): void
    {
        $this->session->expects($this->once())
            ->method('set')
            ->with('mark_session', true);

        $this->authorization->setMarkSession(true);
    }

    public function test_clear_mark_session(): void
    {
        $this->session->expects($this->once())
            ->method('delete')
            ->with('mark_session');

        $this->authorization->clearMarkSession();
    }

    private function createMockSession(): SessionInterface
    {
        return $this->createMock(SessionInterface::class);
    }
}
