<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\Integration\Validation;

use Dontdrinkandroot\BridgeBundle\Tests\WebTestCase;
use Dontdrinkandroot\Common\FlexDate;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FlexDateValidationTest extends WebTestCase
{
    public function testValid(): void
    {
        self::bootKernel();
        $validator = self::getService(ValidatorInterface::class);
        $flexDate = new FlexDate();

        $validationErrors = $validator->validate($flexDate);
        self::assertCount(0, $validationErrors);

        $flexDate->setYear(2021);
        $validationErrors = $validator->validate($flexDate);
        self::assertCount(0, $validationErrors);

        $flexDate->setMonth(1);
        $validationErrors = $validator->validate($flexDate);
        self::assertCount(0, $validationErrors);

        $flexDate->setDay(2);
        $validationErrors = $validator->validate($flexDate);
        self::assertCount(0, $validationErrors);
    }

    public function testMissingFields(): void
    {
        self::bootKernel();
        $validator = self::getService(ValidatorInterface::class);
        $flexDate = new FlexDate();
        $flexDate->setDay(2);

        $validationErrors = $validator->validate($flexDate);
        self::assertCount(2, $validationErrors);

        $validationError = $validationErrors->get(0);
        self::assertEquals('', $validationError->getPropertyPath());
        self::assertEquals('The year is not set.', $validationError->getMessage());

        $validationError = $validationErrors->get(1);
        self::assertEquals('', $validationError->getPropertyPath());
        self::assertEquals('The month is not set.', $validationError->getMessage());

        $flexDate->setMonth(1);
        $validationErrors = $validator->validate($flexDate);
        self::assertCount(1, $validationErrors);

        $validationError = $validationErrors->get(0);
        self::assertEquals('', $validationError->getPropertyPath());
        self::assertEquals('The year is not set.', $validationError->getMessage());
    }

    public function testInvalidDate(): void
    {
        self::bootKernel();
        $validator = self::getService(ValidatorInterface::class);
        $flexDate = new FlexDate();
        $flexDate->setYear(2021);
        $flexDate->setMonth(2);
        $flexDate->setDay(31);

        $validationErrors = $validator->validate($flexDate);
        self::assertCount(1, $validationErrors);

        $validationError = $validationErrors->get(0);
        self::assertEquals('', $validationError->getPropertyPath());
        self::assertEquals('The date does not exist.', $validationError->getMessage());
    }

    public function testInvalidRange(): void
    {
        self::bootKernel();
        $validator = self::getService(ValidatorInterface::class);
        $flexDate = new FlexDate();
        $flexDate->setYear(-1);
        $flexDate->setMonth(-1);
        $flexDate->setDay(-1);

        $validationErrors = $validator->validate($flexDate);
        self::assertCount(4, $validationErrors);

        $validationError = $validationErrors->get(0);
        self::assertEquals('', $validationError->getPropertyPath());
        self::assertEquals('The date does not exist.', $validationError->getMessage());

        $validationError = $validationErrors->get(1);
        self::assertEquals('year', $validationError->getPropertyPath());
        self::assertEquals('This value should be 0 or more.', $validationError->getMessage());

        $validationError = $validationErrors->get(2);
        self::assertEquals('month', $validationError->getPropertyPath());
        self::assertEquals('This value should be between 1 and 12.', $validationError->getMessage());

        $validationError = $validationErrors->get(3);
        self::assertEquals('day', $validationError->getPropertyPath());
        self::assertEquals('This value should be between 1 and 31.', $validationError->getMessage());

        $flexDate->setYear(2021);
        $flexDate->setMonth(13);
        $flexDate->setDay(32);

        $validationErrors = $validator->validate($flexDate);
        self::assertCount(3, $validationErrors);

        $validationError = $validationErrors->get(0);
        self::assertEquals('', $validationError->getPropertyPath());
        self::assertEquals('The date does not exist.', $validationError->getMessage());

        $validationError = $validationErrors->get(1);
        self::assertEquals('month', $validationError->getPropertyPath());
        self::assertEquals('This value should be between 1 and 12.', $validationError->getMessage());

        $validationError = $validationErrors->get(2);
        self::assertEquals('day', $validationError->getPropertyPath());
        self::assertEquals('This value should be between 1 and 31.', $validationError->getMessage());
    }
}
