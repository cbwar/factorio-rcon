<?php

namespace Cbwar\FactorioRcon\Protocol;

readonly class Response
{
    public const TYPE_AUTH = 2;
    public const TYPE_DEFAULT = 0;

    public function __construct(private int    $id,
                                private int    $type,
                                private int    $size,
                                private string $payload)
    {
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

}
