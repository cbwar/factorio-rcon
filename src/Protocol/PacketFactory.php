<?php

namespace Cbwar\FactorioRcon\Protocol;

class PacketFactory
{

    public const FAILURE = -1;

    public function createRequest(Request $request): string
    {
        return (string)$request;
    }

    public function handleResponse(string $buffer): Response
    {
        $size = ((int)unpack('l', substr($buffer, 0, 4))[1]) - 10;
        $id = (int)unpack('l', substr($buffer, 4, 4))[1];
        $type = (int)unpack('l', substr($buffer, 8, 4))[1];
        $payload = substr($buffer, 12, -2);
        return new Response($id, $type, $size, $payload);
    }

}