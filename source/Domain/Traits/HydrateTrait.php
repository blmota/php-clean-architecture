<?php

namespace Source\Domain\Traits;

use DateTime;
use ReflectionClass;
use Source\Domain\Attributes\Column;

trait HydrateTrait
{
    public function hydrate(array $data): void
    {
        $reflection = new ReflectionClass(get_called_class());
        
        while ($reflection) {
            foreach ($reflection->getProperties() as $property) {
                $attributes = $property->getAttributes(Column::class);
                
                if (!empty($attributes)) {
                    /** @var Column $column */
                    $column = $attributes[0]->newInstance();
                    $propertyName = $column->name;

                    if (array_key_exists($propertyName, $data)) {
                        $value = $data[$propertyName];

                        if ($column->customConverter === 'DateTime') {
                            $value = new DateTime($value);
                        } else {
                            settype($value, $column->type);
                        }

                        $method = 'set' . str_replace('_', '', ucwords($propertyName, '_'));
                        if (method_exists($this, $method)) {
                            $this->$method($value);
                        }
                    }
                }
            }

            // Ir para a classe pai (se existir)
            $reflection = $reflection->getParentClass();
        }
    }
}
