<?php

namespace Safemood\Workflow\DTO;

use Safemood\Workflow\Contracts\DTOInterface;

abstract class BaseDTO implements DTOInterface
{
    public function toArray(): array
    {
        $properties = [];

        $reflectionClass = new \ReflectionClass($this);
        $classProperties = $reflectionClass->getProperties();

        foreach ($classProperties as $property) {
            $property->setAccessible(true);
            $properties[$property->getName()] = $property->getValue($this);
        }

        return $properties;
    }

    public function __call(string $name, array $arguments)
    {
        if (strpos($name, 'get') === 0) {
            $property = lcfirst(substr($name, 3));
            if (property_exists($this, $property)) {
                return $this->$property;
            }
        }

        if (strpos($name, 'set') === 0) {
            $property = lcfirst(substr($name, 3));
            if (property_exists($this, $property)) {
                $this->$property = $arguments[0];

                return $this;
            }
        }

        throw new \Exception("Method {$name} does not exist.");
    }
}
