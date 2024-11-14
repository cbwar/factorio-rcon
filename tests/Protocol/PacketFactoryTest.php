<?php

namespace Tests\Protocol;

use Cbwar\FactorioRcon\Protocol\Packet;
use Cbwar\FactorioRcon\Protocol\PacketFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PacketFactory::class)]
#[CoversClass(Packet::class)]
class PacketFactoryTest extends TestCase
{

    public function testCreateRequest(): void
    {
        $factory = new PacketFactory();
        $packet = new Packet(1, Packet::REQUEST_TYPE_COMMAND, 'test');
        $expected = "\016\000\000\000\001\000\000\000\002\000\000\000test\000\000";
        $this->assertSame($expected, $factory->toBinary($packet));
    }

    public function testHandleResponse(): void
    {
        $factory = new PacketFactory();
        $buffer = "\016\000\000\000\001\000\000\000\000\000\000\000test\000\000";
        $expected = new Packet(1, Packet::RESPONSE_TYPE_DEFAULT, 'test');
        $this->assertEquals($expected, $factory->toPacket($buffer));
    }


}
