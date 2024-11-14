<?php

namespace Cbwar\FactorioRcon\Protocol;

class PacketFactory
{

    public const FAILURE = -1;

    public function toBinary(Packet $request): string
    {
        return (string)$request;
    }

    public function toPacket(string $buffer): Packet
    {
        $id = (int)unpack('l', substr($buffer, 4, 4))[1];
        $type = (int)unpack('l', substr($buffer, 8, 4))[1];
        $payload = substr($buffer, 12, -2);
        return new Packet($id, $type, $payload);
    }

}
