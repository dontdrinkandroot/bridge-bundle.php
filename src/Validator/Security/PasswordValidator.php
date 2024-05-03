<?php

namespace Dontdrinkandroot\BridgeBundle\Validator\Security;

use Override;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordValidator extends ConstraintValidator
{
    #[Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!is_string($value) || '' === $value) {
            return;
        }

        if (strlen($value) < 8) {
            $this->context
                ->buildViolation(Password::MESSAGE_TOO_SHORT)
                ->setTranslationDomain('ddr_security')
                ->addViolation();
        }

        $numConstraintsFulfilled = 0;

        if (self::hasSpecialCharacters($value)) {
            $numConstraintsFulfilled++;
        }

        $hasUppercaseLetter = preg_match('/[A-Z]/', $value);
        if ($hasUppercaseLetter) {
            $numConstraintsFulfilled++;
        }

        $hasLowercaseLetter = preg_match('/[a-z]/', $value);
        if ($hasLowercaseLetter) {
            $numConstraintsFulfilled++;
        }

        $hasNumber = preg_match('/\d/', $value);
        if ($hasNumber) {
            $numConstraintsFulfilled++;
        }

        if ($numConstraintsFulfilled < 3) {
            $this->context
                ->buildViolation(Password::MESSAE_MISSING_CHARACTER_CLASS)
                ->setTranslationDomain('ddr_security')
                ->addViolation();
        }
    }

    /**
     * Checks that the password contains a character that is not a digit, not an upper or lowercase letter.
     */
    public static function hasSpecialCharacters(string $value): bool
    {
        return 1 === preg_match('/[^\da-zA-Z]/u', $value);
    }
}
