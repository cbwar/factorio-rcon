<?php

namespace Tests;

use Cbwar\FactorioRcon\FactorioClient;
use Cbwar\FactorioRcon\RCONClientInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(FactorioClient::class)]
class FactorioClientTest extends TestCase
{

    /**
     * @var RCONClientInterface|MockObject
     */
    private $client;
    private FactorioClient $factorioClient;

    protected function setUp(): void
    {
        $this->client = $this->createMock(RCONClientInterface::class);
        $this->factorioClient = new FactorioClient($this->client);
    }

    public function testGetOnlinePlayers(): void
    {
        $this->client->expects($this->once())
            ->method('execute')
            ->with('/players online')
            ->willReturn(<<<TEXT
Players (1):
    player1(online)

TEXT
            );

        $this->assertEquals(['player1'], $this->factorioClient->getOnlinePlayers());
    }

    public function testGetOnlinePlayersWithNoPlayers(): void
    {
        $this->client->expects($this->once())
            ->method('execute')
            ->with('/players online')
            ->willReturn(<<<TEXT
Online players (0):

TEXT
            );

        $this->assertEquals([], $this->factorioClient->getOnlinePlayers());
    }

    public function testGetRegisteredPlayers(): void
    {
        $this->client->expects($this->once())
            ->method('execute')
            ->with('/players')
            ->willReturn(<<<TEXT
Players (2):
  player1  (online)
  player2
TEXT
            );

        $this->assertEquals(['player1', 'player2'], $this->factorioClient->getRegisteredPlayers());
    }

    public function testGetVersion(): void
    {
        $this->client->expects($this->once())
            ->method('execute')
            ->with('/version')
            ->willReturn('0.17.79');

        $this->assertEquals('0.17.79', $this->factorioClient->getVersion());
    }

    public function testGetTime(): void
    {
        $this->client->expects($this->once())
            ->method('execute')
            ->with('/time')
            ->willReturn('Time: 0:0');

        $this->assertEquals('Time: 0:0', $this->factorioClient->getTime());
    }

    public function testGetAdmins(): void
    {
        $this->client->expects($this->once())
            ->method('execute')
            ->with('/admins')
            ->willReturn(<<<TEXT
  admin1
  admin2
TEXT
            );

        $this->assertEquals(['admin1', 'admin2'], $this->factorioClient->getAdmins());
    }

    public function testGetEvolution(): void
    {
        $this->client->expects($this->once())
            ->method('execute')
            ->with('/evolution')
            ->willReturn('0.0%');

        $this->assertEquals('0.0%', $this->factorioClient->getEvolution());
    }

    public function testGetSeed(): void
    {
        $this->client->expects($this->once())
            ->method('execute')
            ->with('/seed')
            ->willReturn('123456');

        $this->assertEquals('123456', $this->factorioClient->getSeed());
    }
}
