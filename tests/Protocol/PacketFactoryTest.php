<?php

namespace Tests\Protocol;

use Cbwar\FactorioRcon\Protocol\PacketFactory;
use Cbwar\FactorioRcon\Protocol\Request;
use Cbwar\FactorioRcon\Protocol\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PacketFactory::class)]
#[CoversClass(Response::class)]
#[CoversClass(Request::class)]
class PacketFactoryTest extends TestCase
{

    public function testCreateRequest(): void
    {
        $packet = new PacketFactory();
        $options = new Request(1, Request::TYPE_COMMAND, 'test');
        $expected = "\016\000\000\000\001\000\000\000\002\000\000\000test\000\000";
        $this->assertSame($expected, $packet->createRequest($options));
    }

    public function testHandleResponse(): void
    {
        $packet = new PacketFactory();
        $buffer = "\016\000\000\000\001\000\000\000\000\000\000\000test\000\000";
        $expected = new Response(1, Response::TYPE_DEFAULT, 4, 'test');
        $this->assertEquals($expected, $packet->handleResponse($buffer));
    }


}
