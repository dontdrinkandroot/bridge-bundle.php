<?php

namespace Dontdrinkandroot\BridgeBundle\Validator\Security;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (strlen($value) < 8) {
            $this->context->buildViolation(Password::MESSAGE_TO_SHORT)->addViolation();
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
            $this->context->buildViolation(Password::MESSAGE_CHARACTER_REQUIREMENTS)->addViolation();
        }
    }

    /**
     * Checks that the password contains a character that is not a digit, not an upper or lowercase letter
     * and not a currency symbol.
     */
    public static function hasSpecialCharacters(string $value): bool
    {
        return 1 === preg_match('/[^\da-zA-Z[\x{20A0}-\x{20CF}]/u', $value);
    }
}
