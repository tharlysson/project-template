<?php

declare(strict_types=1);

namespace POS\Shared\ValueObjects;

use POS\Shared\Exceptions\InvalidValueException;
use Ramsey\Uuid\Uuid as UuidType;
class Uuid
{
    public string $value;

    /**
     * @throws InvalidValueException
     */
    final public function __construct(?string $value = null)
    {
        if (is_null($value)) {
            $value = UuidType::uuid4()->toString();
        }

        if (UuidType::isValid(uuid: $value) === false) {
            throw new InvalidValueException(class: self::class, value: $value);
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(Uuid $id): bool
    {
        return $this->value === $id->value;
    }
}