# AGENTS.md

## About

TODO: Project specific

## Tech Stack

- PHP 8.5
- Symfony 7.4
- PHPUnit 13
- PHPStan
- pnpm Package Manager for frontend

## Coding Guidelines

### Response Policy

- Prefer the smallest correct and maintainable change.
- Ask a clarifying question if the request is ambiguous or has multiple valid interpretations.
- Do not refactor unrelated code.
- If a small refactor is necessary to make the change safe or coherent, do it only in the affected area.
- If rules conflict, follow this precedence:
  1. User request
  2. Project-specific rules
  3. General coding conventions
  4. Style preferences
- Distinguish between:
  - Must: required
  - Should: expected unless there is a good reason
  - Prefer: default choice, but alternatives are acceptable
- If you get stuck, stop and ask for clarification instead of guessing or expanding the scope.

### General

- We always adhere to Clean Code principles:
  - **Meaningful Names:** Choose names that reveal intent. A variable or function name should explain why it exists and what it does without needing a comment.
  - **Functions Should Do One Thing:** Functions should be small and perform a single task. If you can extract a subset of a function into another, it’s doing too much.
  - **DRY (Don't Repeat Yourself):** Eliminate duplication. Every piece of logic should have a single, unambiguous representation in the codebase.
  - **Comment on "why" not "how":** Code should explain itself. Use comments only when you cannot express intent through code or to explain "why" (business logic), never "how." Make sure the "why" is always clear so when looking at the code again the intent is clear either through the code or comments.
  - **The Boy Scout Rule:** Leave the code slightly better than you found it, but keep changes related to the requested task.
- We always adhere to SOLID principles:
  - **Single Responsibility (SRP):** A class should have only one reason to change.
  - **Open/Closed (OCP):** Software entities should be open for extension but closed for modification.
  - **Liskov Substitution (LSP):** Subtypes must be substitutable for their base types.
  - **Interface Segregation (ISP):** No client should be forced to depend on methods it does not use.
  - **Dependency Inversion (DIP):** Depend on abstractions, not concretions.
- We use strict typing (but no need to include `declare(strict_types=1);`) using PHP 8.5 type definitions and PHPStan annotations:
  - Require native PHP types wherever possible.
  - Use PHPStan annotations for generics and shapes when native types are insufficient.
  - Prefer explicit return and parameter types over docblock-only typing.
- No `empty()` checks, use type-specific checks like `if (false === $var)`, `if ('' === $var)`, etc.
- Prefer `sprintf()` over string concatenation.
- Use promoted properties for Entity and Model classes to avoid getters/setters where possible.
- Use PSR-12 coding style.
- Prefer DTOs/value objects over plain array hashmaps when arrays start carrying structure. If using arrays, make sure they are cleanly typed via PHPStan.
- One PHP class per file; never define multiple classes in a single file.
- When overriding methods, use the `#[Override]` attribute to ensure proper method overriding.
- Prefer inline assignments to avoid redundant method calls and temporary variables, e.g.
  ```php
  if (null !== ($var = $this->getVar())) {
      // Do something with var when it's only needed in this block
  }
  ```
- It is fine to handle test classes more loosely (inline classes/enums if only needed there, etc.).

### Symfony

- Controllers (Action–domain–responder pattern) and Commands are invokable objects, so they define `__invoke()` as the main method.
- Controllers end with `Action`, the `#[Route]` attribute is directly at the class level, not at the `__invoke()` function.
- Request parameters are injected into `__invoke()`, services are injected in the constructor.
- Templates must mirror the controller namespace structure. For example:
  - `App\Controller\Category\ListAction` renders `templates/Category/list.html.twig`
  - Root-level controllers like `App\Controller\ComparisonAction` render `templates/comparison.html.twig`

### Tests

- For acceptance tests, use speaking and descriptive function names like `testAnonymousAccessUnauthorized()` so they are readable in testdox.
- Use `self::assert*` instead of `$this->assert*`.
- Tests use the Arrange-Act-Assert pattern, separated by additional blank lines; no need to mention that in comments.

## Project Structure

- `config/`
- `src/`
  - `Config/`
    - `RouteName.php` - Route name constants
- `tests/`
  - `Acceptance/`
    - `Controller/`
    - `Endpoint/`
  - `Integration/`
  - `Unit/`

## Features

## Verification

- Run `bin/validate` to execute all validation steps:
  - PHPUnit tests with code coverage
  - PHPStan static analysis
  - CRAP index evaluation (threshold: 30, configurable via `CRAP_THRESHOLD` env var)
- A task is only considered successful if all steps pass; this is the required definition of done.
