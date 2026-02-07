<?php

// src/Core/UseCaseMapper.php

declare(strict_types=1);

namespace Blog\Core;

use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Core UseCaseMapper - mapuje HTTP request na vstup pre use-cases
 *
 * Toto je doménovo-agnostický mapper, ktorý sa používa v celej aplikácii
 */
final class UseCaseMapper
{
    /**
     * Mapuje request na use-case vstupné dáta
     */
    public static function mapToUseCaseInput(
        ServerRequestInterface $request,
        array $mappingConfig
    ): array {
        $result = [];

        foreach ($mappingConfig as $inputKey => $source) {
            $result[$inputKey] = self::extractFromSource($source, $request);
        }

        return $result;
    }

    /**
     * Extrakt dát zo zdroja podľa konfigurácie
     */
    private static function extractFromSource(string $source, ServerRequestInterface $request): mixed
    {
        // Formát: typ:kluc (napr. "body:title", "query:page", "attribute:id")
        $parts = explode(':', $source, 2);

        if (count($parts) === 1) {
            // Default: body parameter
            $type = 'body';
            $key = $parts[0];
        } else {
            [$type, $key] = $parts;
        }

        return match($type) {
            'body' => self::getFromBody($key, $request),
            'query' => self::getFromQuery($key, $request),
            'attribute' => self::getFromAttributes($key, $request),
            'route' => self::getFromAttributes($key, $request), // alias pre attribute
            'header' => self::getFromHeaders($key, $request),
            'server' => self::getFromServer($key, $request),
            'cookie' => self::getFromCookies($key, $request),
            'session' => self::getFromSession($key, $request),
            default => throw new InvalidArgumentException("Unknown source type: {$type}")
        };
    }

    private static function getFromBody(string $key, ServerRequestInterface $request): mixed
    {
        $body = $request->getParsedBody() ?? [];

        return $body[$key] ?? null;
    }

    private static function getFromQuery(string $key, ServerRequestInterface $request): mixed
    {
        $query = $request->getQueryParams();

        return $query[$key] ?? null;
    }

    private static function getFromAttributes(string $key, ServerRequestInterface $request): mixed
    {
        return $request->getAttribute($key);
    }

    private static function getFromHeaders(string $key, ServerRequestInterface $request): mixed
    {
        return $request->getHeaderLine($key);
    }

    private static function getFromServer(string $key, ServerRequestInterface $request): mixed
    {
        $server = $request->getServerParams();

        return $server[$key] ?? null;
    }

    private static function getFromCookies(string $key, ServerRequestInterface $request): mixed
    {
        $cookies = $request->getCookieParams();

        return $cookies[$key] ?? null;
    }

    private static function getFromSession(string $key, ServerRequestInterface $request): mixed
    {
        // Session musí byť nastavená v middleware
        $session = $request->getAttribute('session');

        return $session[$key] ?? null;
    }

    /**
     * Mapuje use-case výstup na array pre templaty
     */
    public static function mapToViewData($useCaseResult, string $viewKey = 'data'): array
    {
        if (is_array($useCaseResult)) {
            return [$viewKey => $useCaseResult];
        }

        if (is_object($useCaseResult)) {
            // Skúsime toArray() alebo getArrayCopy() metódy
            if (method_exists($useCaseResult, 'toArray')) {
                return [$viewKey => $useCaseResult->toArray()];
            }

            if (method_exists($useCaseResult, 'getArrayCopy')) {
                return [$viewKey => $useCaseResult->getArrayCopy()];
            }

            // Pre entity môžeme použiť public properties
            if ($useCaseResult instanceof \JsonSerializable) {
                return [$viewKey => $useCaseResult->jsonSerialize()];
            }
        }

        // Fallback: wrap do array
        return [$viewKey => $useCaseResult];
    }

    /**
     * Mapuje use-case výstup na API response
     */
    public static function mapToApiResponse($useCaseResult): array
    {
        if (is_array($useCaseResult)) {
            return $useCaseResult;
        }

        if (is_object($useCaseResult)) {
            if (method_exists($useCaseResult, 'toArray')) {
                return $useCaseResult->toArray();
            }

            if ($useCaseResult instanceof \JsonSerializable) {
                return $useCaseResult->jsonSerialize();
            }
        }

        // Default: wrap scalar values
        return ['data' => $useCaseResult];
    }

    /**
     * Validuje vstupné dáta podľa pravidiel
     */
    public static function validate(array $data, array $validationRules): void
    {
        $errors = [];

        foreach ($validationRules as $field => $rules) {
            $value = $data[$field] ?? null;

            foreach ($rules as $rule) {
                if (!$rule->isValid($value)) {
                    $errors[$field][] = $rule->getMessage();
                }
            }
        }

        if (!empty($errors)) {
            throw new \DomainException('Validation failed: ' . json_encode($errors));
        }
    }
}
