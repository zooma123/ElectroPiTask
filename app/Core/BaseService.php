<?php

namespace App\Core;

abstract class BaseService
{
    protected ?string $error = null;
    protected int $errorCode = 400;
    protected mixed $data = null;

    public function setError(string $error, int $errorCode = 400): self
    {
        $this->error = $error;
        $this->errorCode = $errorCode;
        return $this;
    }

    public function hasError(): bool
    {
        return !is_null($this->error);
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setData(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
