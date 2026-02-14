---
name: pest-testing
description: >-
  Tests applications using the Pest 4 PHP framework. Activates when writing tests, creating unit or feature
  tests, adding assertions, testing Livewire components, browser testing, debugging test failures,
  working with datasets or mocking; or when the user mentions test, spec, TDD, expects, assertion,
  coverage, or needs to verify functionality works.
---

# Pest Testing 4

## When to Apply

Activate this skill when:

- Creating new tests (unit, feature, or browser)
- Modifying existing tests
- Debugging test failures
- Working with browser testing or smoke testing
- Writing architecture tests or visual regression tests

## Documentation

Use `search-docs` for detailed Pest 4 patterns and documentation.

## Basic Usage

### Creating Tests

All tests must be written using Pest. Use `php artisan make:test --pest {name}`.

### Test Organization

- Unit/Feature tests: `tests/Feature` and `tests/Unit` directories.
- Component tests: `resources/views/` directory with `.test.php` suffix (configured in `phpunit.xml` as `Components` suite).
- Browser tests: `tests/Browser/` directory.
- Do NOT remove tests without approval — these are core application code.

### Basic Test Structure

<code-snippet name="Basic Pest Test Example" lang="php">
it('is true', function () {
    expect(true)->toBeTrue();
});
</code-snippet>

### Running Tests

- Run minimal tests with filter before finalizing: `php artisan test --compact --filter=testName`.
- Run all tests: `php artisan test --compact`.
- Run file: `php artisan test --compact tests/Feature/ExampleTest.php`.
- Run component tests: `php artisan test --compact resources/views/pages/booking/⚡create.test.php`.

## Pest 4 Features

### Built on PHPUnit 12

Pest 4 runs on PHPUnit 12, inheriting its latest features and improvements. Requires PHP 8.3+.

### Browser Testing

Pest v4 adds first-class browser testing powered by Playwright. Write end-to-end tests in PHP:

<code-snippet name="Pest Browser Test Example" lang="php">
it('may reset the password', function () {
    Notification::fake();

    $this->actingAs(User::factory()->create());

    $page = visit('/sign-in');

    $page->assertSee('Sign In')
        ->assertNoJavaScriptErrors()
        ->click('Forgot Password?')
        ->fill('email', 'nuno@laravel.com')
        ->click('Send Reset Link')
        ->assertSee('We have emailed your password reset link!');

    Notification::assertSent(ResetPassword::class);
});
</code-snippet>

- Browser tests live in `tests/Browser/`.
- Use Laravel features like `Event::fake()`, `assertAuthenticated()`, and model factories.
- Use `RefreshDatabase` for clean state per test.
- Test on multiple browsers and devices if requested.

### Smoke Testing

Quickly validate multiple pages have no JavaScript errors:

<code-snippet name="Pest Smoke Testing Example" lang="php">
$pages = visit(['/', '/about', '/contact']);

$pages->assertNoJavaScriptErrors()->assertNoConsoleLogs();
</code-snippet>

### Visual Regression Testing

Capture and compare screenshots to detect pixel-level visual changes with `assertScreenshotMatches()`.

### Test Sharding

Split tests across parallel CI processes with the `--shard` flag for faster execution.

### Architecture Testing

Enforce codebase conventions with architecture tests:

<code-snippet name="Architecture Test Example" lang="php">
arch('controllers')
    ->expect('App\Http\Controllers')
    ->toExtendNothing()
    ->toHaveSuffix('Controller');
</code-snippet>

## Assertions

Use specific assertions instead of `assertStatus()`:

| Use | Instead of |
|-----|------------|
| `assertSuccessful()` | `assertStatus(200)` |
| `assertNotFound()` | `assertStatus(404)` |
| `assertForbidden()` | `assertStatus(403)` |

<code-snippet name="Pest Response Assertion" lang="php">
it('returns all', function () {
    $this->postJson('/api/docs', [])->assertSuccessful();
});
</code-snippet>

## Testing Livewire Components

Test Volt single-file components using the `pages::` namespace:

<code-snippet name="Testing Volt Components" lang="php">
use Livewire\Livewire;

it('renders the create page', function () {
    Livewire::test('pages::booking.create')
        ->assertStatus(200);
});

it('validates required fields', function () {
    Livewire::test('pages::booking.create')
        ->set('bookingForm.name', '')
        ->call('save')
        ->assertHasErrors(['bookingForm.name']);
});
</code-snippet>

<code-snippet name="Testing Standard Livewire Components" lang="php">
Livewire::test(Counter::class)
    ->assertSet('count', 0)
    ->call('increment')
    ->assertSet('count', 1)
    ->assertSee(1);
</code-snippet>

## Mocking

Import mock function before use: `use function Pest\Laravel\mock;`

## Datasets

Use datasets for repetitive tests (validation rules, etc.):

<code-snippet name="Pest Dataset Example" lang="php">
it('has emails', function (string $email) {
    expect($email)->not->toBeEmpty();
})->with([
    'james' => 'james@laravel.com',
    'taylor' => 'taylor@laravel.com',
]);
</code-snippet>

Bound datasets can be resolved after `beforeEach()` setup, useful in Laravel contexts.

## Pest 4 Changes from Pest 3

- Built on PHPUnit 12 (was PHPUnit 11).
- Adds browser testing, visual regression, smoke testing, and device/theme simulation.
- Requires PHP 8.3+ (was earlier versions).
- `tap()` replaced with `defer()`.
- New expectations like `toBeSlug`.
- CI sharding with `--shard` flag.
- Unified backend + frontend code coverage reporting.

## Common Pitfalls

- Not importing `use function Pest\Laravel\mock;` before using mock
- Using `assertStatus(200)` instead of `assertSuccessful()`
- Forgetting datasets for repetitive validation tests
- Deleting tests without approval
- Forgetting `assertNoJavaScriptErrors()` in browser tests
- Using wrong component namespace for Volt tests (use `pages::` prefix)
