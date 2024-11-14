<?php

namespace Cbwar\FactorioRcon\Protocol;

use Stringable;

readonly class Packet implements Stringable
{
    public const REQUEST_TYPE_AUTH = 3;
    public const REQUEST_TYPE_COMMAND = 2;
    public const RESPONSE_TYPE_AUTH = 2;
    public const RESPONSE_TYPE_DEFAULT = 0;


    public function __construct(private int    $id,
                                private int    $type,
                                private string $payload = '')
    {
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

    public function getSize(): int
    {
        return mb_strlen($this->payload);
    }

    public function __toString(): string
    {
        $payload = mb_convert_encoding($this->payload, 'utf-8');
        $bodySize = strlen($this->payload);
        // Add 4 to the size (body + 10) for the null char
        $buffer = str_repeat("\0", $bodySize + 14);
        // Subtract 4 because the packet size field is not included when
        // determining the size of the packet
        $size = pack('l', strlen($buffer) - 4);
        $id = pack('l', $this->id);
        $type = pack('l', $this->type);
        return $size . $id . $type . $payload . "\0\0";
    }
}
