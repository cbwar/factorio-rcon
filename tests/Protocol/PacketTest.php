<?php

namespace Tests\Protocol;

use Cbwar\FactorioRcon\Protocol\Packet;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Packet::class)]
class PacketTest extends TestCase
{

    public function testRequest(): void
    {
        $packet = new Packet();
        $options = [
            'id' => 1,
            'type' => Packet::SERVERDATA_EXECCOMMAND,
            'body' => 'test'
        ];
        $expected = "\016\000\000\000\001\000\000\000\002\000\000\000test\000\000";
        $this->assertSame($expected, $packet->request($options));
    }

    public function testResponse(): void
    {
        $packet = new Packet();
        $buffer = "\016\000\000\000\001\000\000\000\000\000\000\000test\000\000";
        $expected = [
            'size' => 4,
            'id' => 1,
            'type' => Packet::SERVERDATA_RESPONSE_VALUE,
            'payload' => 'test'
        ];
        $this->assertSame($expected, $packet->response($buffer));
    }


}
