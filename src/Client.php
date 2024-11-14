<?php

namespace Cbwar\FactorioRcon;

use Cbwar\FactorioRcon\Protocol\Packet;
use Cbwar\FactorioRcon\Protocol\PacketFactory;
use RuntimeException;

class Client implements RCONClientInterface
{
    /**
     * @var false|resource
     */
    private $socket = false;

    private bool $authenticated = false;


    private int $packedId = 1;

    public function __construct(private readonly string $host,
                                private readonly string $port,
                                private readonly string $password,
                                private readonly string $timeout = '2')
    {
    }

    private function log(string $message): void
    {
        echo 'RCON // ' . $message . PHP_EOL;
    }

    public function __destruct()
    {
        if ($this->socket) {
            $this->log('Closing connection');
            fclose($this->socket);
        }
    }

    private function readAllWithoutTimeout(): string
    {
        $buffer = fgetc($this->socket);
        while (stream_get_meta_data($this->socket)['unread_bytes'] > 0) {
            $buffer .= fgetc($this->socket);
        }
        return $buffer;
    }

    public function execute(string $command): string
    {
        $this->authenticate();

        $this->log('Sending command: ' . $command);
        $factory = new PacketFactory();

        // Send request
        $request = $factory->toBinary(new Packet(++$this->packedId,
            Protocol\Packet::REQUEST_TYPE_COMMAND,
            $command));
        fwrite($this->socket, $request);

        $buffer = $this->readAllWithoutTimeout();
        $response = $factory->toPacket($buffer);

        // Send ack
        $request = $factory->toBinary(new Packet(++$this->packedId,
            Protocol\Packet::REQUEST_TYPE_COMMAND));
        fwrite($this->socket, $request);

        return $response->getPayload();
    }


    private function authenticate(): void
    {
        if ($this->authenticated) {
            $this->log('Already authenticated');
            return;
        }

        $this->connect();

        if ($this->socket === false) {
            throw new RuntimeException('Not connected');
        }

        $this->log('Authenticating');


        $factory = new PacketFactory();
        $request = $factory->toBinary(new Packet(++$this->packedId,
            Protocol\Packet::REQUEST_TYPE_AUTH, $this->password));

        $this->log('Sending authentication request');
        fwrite($this->socket, $request);

        $buffer = $this->readAllWithoutTimeout();
        $response = $factory->toPacket($buffer);
        if ($response->getType() !== Packet::RESPONSE_TYPE_AUTH || $response->getId() === PacketFactory::FAILURE) {
            throw new RuntimeException('Authentication failed');
        }

        $this->log('Authenticated successfully');
        $this->authenticated = true;
    }

    private function connect(): void
    {
        if ($this->socket) {
            $this->log('Already connected');
            return;
        }

        $this->log('Connecting to ' . $this->host . ':' . $this->port);
        $this->socket = stream_socket_client("tcp://$this->host:$this->port", $errno,
            $errstr);

        if (!$this->socket) {
            $this->log('Failed to connect error: ' . $errno . ' ' . $errstr);
            throw new RuntimeException('Failed to connect');
        }

        stream_set_timeout($this->socket, $this->timeout);
        $this->log('Connected');
    }
}
