<?php

namespace Cbwar\FactorioRcon\Protocol;

use Stringable;

readonly class Request implements Stringable
{
    public const TYPE_AUTH = 3;
    public const TYPE_COMMAND = 2;

    public function __construct(private int    $id,
                                private int    $type,
                                private string $body = '')
    {
    }

    public function __toString(): string
    {
        $body = mb_convert_encoding($this->body, 'utf-8');
        $bodySize = strlen($this->body);
        // Add 4 to the size (body + 10) for the null char
        $buffer = str_repeat("\0", $bodySize + 14);
        // Subtract 4 because the packet size field is not included when
        // determining the size of the packet
        $size = pack('l', strlen($buffer) - 4);
        $id = pack('l', $this->id);
        $type = pack('l', $this->type);
        return $size . $id . $type . $body . "\0\0";
    }
}
