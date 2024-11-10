<?php

namespace Source\Domain\Traits;

trait HydrateTrait
{
    public function hydrate(array $data): void
    {
        foreach ($data as $property => $value) {
            $method = 'set' . str_replace('_', '', ucwords($property, '_'));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
