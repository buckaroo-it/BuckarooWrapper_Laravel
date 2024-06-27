<?php

namespace Buckaroo\Laravel\DTO;

use Illuminate\Contracts\Support\Arrayable;
use ReflectionClass;
use ReflectionProperty;
use Str;

abstract class BaseData implements Arrayable
{
    public static function fromArray(array $data): static
    {
        $reflection = new ReflectionClass(static::class);
        $instance = $reflection->newInstanceWithoutConstructor();

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();

            if (array_key_exists($name, $data)) {
                if (method_exists($instance, $method = Str::camel('set' . ucfirst($name)))) {
                    $instance->{$method}($data[$name]);
                } else {
                    $instance->$name = $data[$name];
                }
            }
        }

        return $instance;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
