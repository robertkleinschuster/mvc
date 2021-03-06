<?php

declare(strict_types=1);

namespace Mvc\Helper;

class ValidationHelper
{
    /**
     * @var string[]
     */
    private $errorField_Map;

    /**
     * ValidationHelper constructor.
     */
    public function __construct()
    {
        $this->errorField_Map = [];
    }

    /**
     * @param string $field
     * @param string $error
     * @return ValidationHelper
     */
    public function addError(string $field, string $error): self
    {
        $this->errorField_Map[$field][] = $error;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return count($this->errorField_Map) > 0;
    }

    /**
     * @param string $field
     * @return array
     */
    public function getErrorList(string $field = null): array
    {
        if (null === $field) {
            $errors = [];
            foreach ($this->errorField_Map as $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    $errors[] = $error;
                }
            }
            return $errors;
        } else {
            return $this->errorField_Map[$field] ?? [];
        }
    }

    /**
     * @return string[]
     */
    public function getErrorFieldMap(): array
    {
        return $this->errorField_Map;
    }

    /**
     * @param array $errorField_Map
     * @return ValidationHelper
     */
    public function addErrorFieldMap(array $errorField_Map)
    {
        $this->errorField_Map = array_merge_recursive($this->errorField_Map, $errorField_Map);
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $result = [];
        foreach ($this->errorField_Map as $fieldErrorList) {
            $result[] = implode(PHP_EOL, $fieldErrorList);
        }
        return implode(PHP_EOL, $result);
    }
}
