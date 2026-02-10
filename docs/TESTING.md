# Testing Strategy

## Overview

This document outlines the comprehensive testing strategy for the Blog application, ensuring code quality, reliability, and maintainability.

## Test Structure

```
tests/
├── Unit/                          # Unit tests - fast, isolated
│   ├── Domain/
│   │   ├── Blog/
│   │   │   ├── Entity/           # Entity business logic
│   │   │   └── ValueObject/      # Value object validation
│   │   └── User/
│   │       ├── Entity/
│   │       └── ValueObject/
│   └── Application/              # Use case logic
│       └── Blog/
├── Integration/                   # Integration tests - slower, with dependencies
│   ├── Infrastructure/
│   │   └── Persistence/          # Database operations
│   ├── Middleware/               # HTTP middleware
│   │   ├── AuthMiddlewareTest.php
│   │   ├── CsrfMiddlewareTest.php
│   │   ├── ErrorHandlerMiddlewareTest.php
│   │   └── SessionTimeoutMiddlewareTest.php
│   └── Application/              # Use case with real dependencies
│       └── Blog/
└── bootstrap.php                 # Test bootstrap
```

## Test Types

### 1. Unit Tests

**Purpose**: Test individual components in isolation

**Characteristics**:
- Fast execution (< 10ms per test)
- No external dependencies (database, file system, network)
- Mock all collaborators
- Test one concept per test

**Coverage**:
- Domain entities and value objects
- Application use cases (with mocked repositories)
- Business logic and validation rules

**Example**:
```php
public function test_creates_article_with_unique_slug(): void
{
    $repository = $this->createMock(ArticleRepository::class);
    $repository->expects($this->once())
        ->method('getBySlug')
        ->willReturn(null);
    
    $useCase = new CreateArticle($repository);
    $result = $useCase->execute(['title' => 'Test', 'content' => 'Content']);
    
    $this->assertTrue($result['success']);
}
```

### 2. Integration Tests

**Purpose**: Test component interactions with real dependencies

**Characteristics**:
- Slower execution (100ms - 1s per test)
- Uses real database (SQLite in-memory)
- Tests actual SQL queries
- Verifies repository implementations

**Coverage**:
- Repository implementations with real database
- Middleware with actual HTTP requests/responses
- Database query optimization

**Example**:
```php
public function test_can_save_and_retrieve_article(): void
{
    $article = Article::create($title, $content, $authorId);
    
    $this->repository->add($article);
    $retrieved = $this->repository->getById($article->id());
    
    $this->assertNotNull($retrieved);
    $this->assertEquals($article->id()->toString(), $retrieved->id()->toString());
}
```

### 3. Error Scenario Testing

**Purpose**: Ensure graceful handling of edge cases and failures

**Characteristics**:
- Tests exception handling
- Validates error responses
- Verifies logging behavior
- Security violation detection

**Coverage Areas**:
- **Middleware Errors**:
  - CSRF validation failures
  - Authentication/authorization failures
  - Rate limiting exceeded
  - Session timeout

- **Repository Errors**:
  - Database connection failures
  - Constraint violations
  - Concurrent modification conflicts

- **Application Errors**:
  - Validation failures
  - Business rule violations
  - External service failures

**Example**:
```php
public function test_handles_database_connection_error(): void
{
    $request = $this->createServerRequest('GET', '/api/articles');
    $handler = $this->createFailingHandler(
        new \PDOException('Connection refused')
    );

    $response = $this->middleware->process($request, $handler);

    $this->assertSame(500, $response->getStatusCode());
    $this->assertStringNotContainsString('Connection refused', (string) $response->getBody());
}
```

## Running Tests

### All Tests
```bash
composer test
```

### Unit Tests Only
```bash
composer test:unit
```

### Integration Tests Only
```bash
composer test:integration
```

### Specific Test File
```bash
./vendor/bin/phpunit tests/Unit/Domain/Blog/Entity/ArticleTest.php
```

### With Coverage Report
```bash
./vendor/bin/phpunit --coverage-html coverage/
```

## Test Data

### Factories

Use factories for consistent test data creation:

```php
class ArticleFactory
{
    public static function create(array $overrides = []): Article
    {
        return Article::create(
            $overrides['title'] ?? Title::fromString('Test Article'),
            $overrides['content'] ?? new Content('Test content'),
            $overrides['authorId'] ?? AuthorId::generate()
        );
    }
}
```

### Database Fixtures

Integration tests use:
- SQLite in-memory database
- Schema created in `setUpBeforeClass()`
- Data cleaned between tests in `setUp()`
- Foreign keys and indexes identical to production

## Mocking Guidelines

### When to Mock

✅ **Mock**:
- External services (email, payment gateways)
- Repositories in unit tests
- Current time (for time-dependent logic)
- Random generators (UUIDs, tokens)

❌ **Don't Mock**:
- Value objects (create real instances)
- Simple DTOs
- Database in integration tests

### Mock Examples

**Repository**:
```php
$repository = $this->createMock(ArticleRepository::class);
$repository->expects($this->once())
    ->method('getBySlug')
    ->with($this->equalTo($slug))
    ->willReturn(null);
```

**Logger**:
```php
$logger = $this->createMock(LoggerInterface::class);
$logger->expects($this->once())
    ->method('error')
    ->with($this->stringContains('Failed to send email'));
```

## Coverage Requirements

### Minimum Coverage

- **Domain Layer**: 90% (business critical)
- **Application Layer**: 80% (use cases)
- **Infrastructure Layer**: 60% (repository implementations)
- **Overall**: 75%

### Critical Paths (100% Coverage Required)

1. Authentication and authorization
2. Payment processing
3. Data validation
4. Error handling
5. Security checks (CSRF, XSS prevention)

## Continuous Integration

Tests run automatically on:
- Every pull request
- Merge to main branch
- Daily scheduled builds

### CI Pipeline

```yaml
test:
  stage: test
  script:
    - composer install
    - composer cs-check
    - composer stan
    - composer test:unit
    - composer test:integration
  coverage: '/Coverage:\s+(\d+%)/'
```

## Best Practices

### 1. Test Naming

Use descriptive names explaining behavior:
```php
// Good
test_throws_exception_when_title_exceeds_max_length()
test_redirects_unauthenticated_user_to_login_page()

// Bad
test_title()
test_auth()
```

### 2. Arrange-Act-Assert

Structure tests clearly:
```php
public function test_creates_article_with_slug(): void
{
    // Arrange
    $title = Title::fromString('Test Article');
    $useCase = new CreateArticle($repository);
    
    // Act
    $result = $useCase->execute(['title' => $title->toString()]);
    
    // Assert
    $this->assertTrue($result['success']);
    $this->assertEquals('test-article', $result['slug']);
}
```

### 3. One Concept Per Test

Don't test multiple things:
```php
// Bad - tests too much
public function test_article(): void
{
    $article = Article::create($title, $content, $author);
    $this->assertNotNull($article);
    $this->assertEquals($title, $article->title());
    $this->assertEquals('draft', $article->status());
    $article->publish();
    $this->assertEquals('published', $article->status());
}

// Good - split into separate tests
public function test_creates_article_with_draft_status(): void
public function test_changes_status_to_published(): void
```

### 4. Avoid Test Interdependence

Each test should be independent:
```php
// Bad - depends on previous test
public function test_creates_article(): void
{
    $this->article = Article::create(...);
}

public function test_publishes_article(): void
{
    // Depends on $this->article from previous test
    $this->article->publish();
}

// Good - each test creates its own data
public function test_creates_article_with_draft_status(): void
{
    $article = Article::create(...);
    $this->assertEquals('draft', $article->status());
}
```

## Debugging Tests

### Verbose Output
```bash
./vendor/bin/phpunit --verbose
```

### Stop on First Failure
```bash
./vendor/bin/phpunit --stop-on-failure
```

### Filter Specific Tests
```bash
./vendor/bin/phpunit --filter test_creates_article
```

### Debug Test with Xdebug
```bash
php -dxdebug.mode=debug -dxdebug.start_with_request=yes ./vendor/bin/phpunit tests/Unit/Domain/Blog/Entity/ArticleTest.php
```

## Common Issues

### 1. "Mocked method does not exist"

Check that the mocked interface/class has the method:
```php
// Wrong - interface doesn't have this method
$mock = $this->createMock(ArticleRepository::class);
$mock->expects($this->once())->method('save'); // Use 'add' instead
```

### 2. "Failed asserting that null matches expected"

Mock return value not configured:
```php
$mock->method('getById')
    ->willReturn($article); // Don't forget this!
```

### 3. Database locked in integration tests

Ensure proper cleanup:
```php
protected function tearDown(): void
{
    $this->cleanupDatabase();
    parent::tearDown();
}
```

## Resources

- [PHPUnit Documentation](https://phpunit.readthedocs.io/)
- [Testing Best Practices](https://phptherightway.com/#testing)
- [Mock Objects](https://phpunit.readthedocs.io/en/9.5/test-doubles.html)
