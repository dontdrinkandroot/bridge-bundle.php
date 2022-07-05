<?php

namespace Dontdrinkandroot\BridgeBundle\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\FlexDate;

class FlexDateType extends StringType
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['fixed'] = true;
        $column['length'] = 10;
        $column['nullable'] = true;
        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'flexdate';
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): FlexDate
    {
        if (null === $value) {
            return new FlexDate();
        }

        return FlexDate::fromString(Asserted::string($value));
    }
}
