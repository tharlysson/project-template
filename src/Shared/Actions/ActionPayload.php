<?php

declare(strict_types=1);

namespace POS\Shared\Actions;

use JsonSerializable;
use POS\Shared\DTO\ResponseStatus;

class ActionPayload implements JsonSerializable
{
    private int $statusCode;

    /**
     * @var array|object|null
     */
    private $data;

    private ?ActionError $error;

    public function __construct(
        int $statusCode = 200,
        $data = null,
        ?ActionError $error = null
    ) {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->error = $error;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array|null|object
     */
    public function getData()
    {
        return $this->data;
    }

    public function getError(): ?ActionError
    {
        return $this->error;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        $status = ResponseStatus::SUCCESS;
        if ($this->statusCode < 299) {
            $status = ResponseStatus::WARNING;
        } elseif ($this->statusCode <= 400) {
            $status = ResponseStatus::FAIL;
        } elseif ($this->statusCode <= 500) {
            $status = ResponseStatus::ERROR;
        }

        $payload = [
            'status' => $status->value,
        ];

        if ($this->data !== null) {
            $payload['data'] = $this->data;
        } elseif ($this->error !== null) {
            $payload['data']['error'] = $this->error;
        }

        return $payload;
    }
}
