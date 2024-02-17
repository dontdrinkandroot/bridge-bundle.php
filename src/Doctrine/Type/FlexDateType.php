<?php

namespace Dontdrinkandroot\BridgeBundle\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\FlexDate;
use Override;

class FlexDateType extends StringType
{
    final public const NAME = 'ddr_flexdate';

    #[Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['fixed'] = true;
        $column['length'] = 10;
        $column['nullable'] = true;
        return $platform->getStringTypeDeclarationSQL($column);
    }

    #[Override]
    public function getName(): string
    {
        return self::NAME;
    }

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        $flexDate = Asserted::instanceOf($value, FlexDate::class);
        if (FlexDate::PRECISION_NONE === $flexDate->getPrecision()) {
            return null;
        }

        return (string)$flexDate;
    }

    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): FlexDate
    {
        if (null === $value) {
            return new FlexDate();
        }

        return FlexDate::fromString(Asserted::string($value));
    }

    #[Override]
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
