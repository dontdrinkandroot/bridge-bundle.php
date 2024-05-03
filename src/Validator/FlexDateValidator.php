<?php

namespace Dontdrinkandroot\BridgeBundle\Validator;

use Override;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class FlexDateValidator extends ConstraintValidator
{
    public const string PATTERN = '/^\d{4}(-\d{2}(-\d{2})?)?$/';

    #[Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!preg_match(self::PATTERN, $value)) {
            $this->context->buildViolation('ddr.flexdate.formatinvalid')
                ->addViolation();
            return;
        }

        if (!\Dontdrinkandroot\Common\FlexDate::fromString($value)->isValidDate()) {
            $this->context->buildViolation('ddr.flexdate.dateinvalid')
                ->addViolation();
        }
    }
}
