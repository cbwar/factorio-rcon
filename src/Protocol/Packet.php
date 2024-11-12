<?php

namespace Cbwar\FactorioRcon\Protocol;

class Packet
{

    public const SERVERDATA_AUTH = 3;
    public const SERVERDATA_AUTH_RESPONSE = 2;
    public const SERVERDATA_EXECCOMMAND = 2;
    public const SERVERDATA_RESPONSE_VALUE = 0;

    public const FAILURE = -1;

    public function request($options): string
    {
        $id = $options['id'];
        $type = $options['type'];
        $body = mb_convert_encoding($options['body'], 'utf-8');

        $bodySize = strlen($body);
        // Add 4 to the size (body + 10) for the null char
        $buffer = str_repeat("\0", $bodySize + 14);
        // Subtract 4 because the packet size field is not included when
        // determining the size of the packet
        $size = pack('l', strlen($buffer) - 4);
        $id = pack('l', $id);
        $type = pack('l', $type);
        $body .= "\0\0";

        return $size . $id . $type . $body;
    }

    public function response($buffer): array
    {
        $size = unpack('l', substr($buffer, 0, 4))[1] - 10;
        $id = unpack('l', substr($buffer, 4, 4))[1];
        $type = unpack('l', substr($buffer, 8, 4))[1];
        $payload = substr($buffer, 12, -2);

        return [
            'size' => $size,
            'id' => $id,
            'type' => $type,
            'payload' => $payload
        ];
    }

}
