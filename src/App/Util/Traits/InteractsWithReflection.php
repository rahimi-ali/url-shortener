<?php

namespace App\Util\Traits;

use ReflectionClass;
use ReflectionException;

trait InteractsWithReflection
{
    /**
     * @param array<string, mixed> $propertyValues
     * @throws ReflectionException
     */
    protected function setProperties(object $object, array $propertyValues): void
    {
        $reflection = new ReflectionClass($object);

        foreach ($propertyValues as $propertyName => $value) {
            $property = $reflection->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        }
    }

    /**
     * @param string[] $properties
     * @return array<string, mixed>
     * @throws ReflectionException
     */
    protected function getProperties(object $object, array $properties): array
    {
        $reflection = new ReflectionClass($object);

        $propertyValues = [];
        foreach ($properties as $propertyName) {
            $property = $reflection->getProperty($propertyName);
            $property->setAccessible(true);
            $propertyValues[$propertyName] = $property->getValue($object);
        }

        return $propertyValues;
    }
}