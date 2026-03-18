## About

## Tech Stack

 * PHP 8.5
 * Symfony 7.4
 * PHPUnit 13
 * PHPStan
 * pnpm Package Manger for frontend

## Coding Guidelines

### General

* We always adhere to Clean Code and SOLID principles.
* We use strict typing (but no need to use include `declare(strict_types=1);`) using PHP 8.5 type definitions and PHPStan annotations to tighten them and use generics.
* No `empty()` checks, use type specific checks like `if(false === $var)`.
* We prefer `sprintf` over string concatenation.
* We use promoted properties for Entity and Model classes to avoid getters/setters where possible.
* We use PSR-12 coding style.

### Symfony

* Controllers (Action–domain–responder pattern) and Commands are invokable objects (so they define `__invoke()` as the main method).
* Controllers end with `Action`, the `#[Route]` attribute is directly at the class level, not at the `__invoke()` function. Request parameters are injected into `__invoke()`, services in the constructor.

### Tests

* For acceptance tests we use speaking and describing function names like `testAnonymousAccessUnauthorized()` so they are readable in testdox
* Use `self::assert*` instead of `$this->assert*`.
* Tests use the Arrange-Act-Assert pattern, separate parts by additional blank line, no need to mention that in comments.

## Project Structure

* config/
* src/
  * Config/
    * RouteName.php - Route name constants 
* tests/
  * Acceptance/
    * Controller/
    * Endpoint/
  * Integration/
  * Unit/

## Features

## Verification

* Run `bin/validate` to execute all validation steps:
  * PHPUnit tests with code coverage
  * PHPStan static analysis
  * CRAP index evaluation (threshold: 30, configurable via `CRAP_THRESHOLD` env var)
* A task is only considered successful if all steps pass, this is a required step to ensure the integrity of the project. This is our "definition of done".
