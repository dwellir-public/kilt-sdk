<?php

namespace KiltSdkPhp\Requests;

use Assert\Assert;
use KiltSdkPhp\Exceptions\RequestValueException;

/**
* @SuppressWarnings(PHPMD.BooleanArgumentFlag)
*/
abstract class BaseRequest implements \JsonSerializable
{
    /** @var array<string, mixed> */
    protected array $serializableData = [];

    private string $function;
    private ?int $id = null;

    public function __construct(string $function)
    {
        $this->function = $function;
    }

    protected function addString(string $field, ?string $value, bool $nullable = false): void
    {
        if (is_null($value) && !$nullable) {
            throw new RequestValueException("parameter $field is null even though thats not allowed");
        }

        if (!is_null($value)) {
            try {
                Assert::that($value)->string();
            } catch (\Exception $e) {
                throw new RequestValueException("parameter $field is not a string");
            }
        }

        $this->$field = $value;
        $this->serializableData[$field] = $value;
    }

    protected function addBool(string $field, ?bool $value, bool $nullable = false): void
    {
        if (is_null($value) && !$nullable) {
            throw new RequestValueException("parameter $field is null even though thats not allowed");
        }

        if (!is_null($value)) {
            try {
                Assert::that($value)->boolean();
            } catch (\Exception $e) {
                throw new RequestValueException("parameter $field is not a boolean");
            }
        }

        $this->$field = $value;
        $this->serializableData[$field] = $value;
    }

    /**
     * @param ?array<mixed, mixed> $value
     */
    protected function addArray(string $field, ?array $value, bool $nullable = false): void
    {
        if (is_null($value) && !$nullable) {
            throw new RequestValueException("parameter $field is null even though thats not allowed");
        }

        if (!is_null($value)) {
            try {
                Assert::that($value)->isArray();
            } catch (\Exception $e) {
                throw new RequestValueException("parameter $field is not a boolean");
            }
        }

        $this->$field = $value;
        $this->serializableData[$field] = $value;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function jsonSerialize()
    {
        return (object)[ "function" => $this->function, "id" => $this->id, "parameters" => $this->serializableData ];
    }
}
