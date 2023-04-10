<?php

declare(strict_types=1);

namespace App\Example\Infrastructure\Sql\Type;

use App\Example\Domain\Example\ExampleName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class ExampleNameType extends Type
{
    public const TYPE = 'app_module_example_example_name';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function getName(): string
    {
        return __CLASS__;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        assert($value instanceof ExampleName);

        return (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ExampleName
    {
        assert(is_string($value));

        return ExampleName::fromString($value);
    }

    public function canRequireSQLConversion(): bool
    {
        return true;
    }
}
