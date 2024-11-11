<?php

namespace Cbwar\FactorioRcon;

class Client
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
                                private readonly string  $timeout = '2')
    {
    }

    private function log(string $message)
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

    public function execute(string $command): string
    {
        $this->authenticate();

        $this->log('Sending command: ' . $command);
        $packet = new Packet();

        // Send request
        $options = [
            'id' => ++$this->packedId,
            'type' => Packet::SERVERDATA_EXECCOMMAND,
            'body' => $command
        ];
        $request = $packet->request($options);
        fwrite($this->socket, $request);

        $buffer = "";
        while(($data = fread($this->socket, 500)) !== false) {
            $buffer .= $data;
        }
        $response = $packet->response($buffer);

        // Send ack
        $options = [
            'id' => ++$this->packedId,
            'type' => Packet::SERVERDATA_EXECCOMMAND,
            'body' => ''
        ];
        $request = $packet->request($options);
        fwrite($this->socket, $request);

        return $response['payload'];
    }


    private function authenticate()
    {
        if ($this->authenticated) {
            $this->log('Already authenticated');
            return;
        }

        $this->connect();

        if ($this->socket === false) {
            throw new \RuntimeException('Not connected');
        }

        $this->log('Authenticating');


        $packet = new Packet();
        $options = [
            'id' => ++$this->packedId,
            'type' => Packet::SERVERDATA_AUTH,
            'body' => $this->password
        ];
        $request = $packet->request($options);

        $this->log('Sending authentication request');
        fwrite($this->socket, $request);

        $buffer = fread($this->socket, 1000);
        $response = $packet->response($buffer);
        if ($response['type'] !== Packet::SERVERDATA_AUTH_RESPONSE || $response['id'] === Packet::FAILURE) {
            throw new \RuntimeException('Authentication failed');
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
        stream_set_timeout($this->socket, $this->timeout);

        if (!$this->socket) {
            $this->log('Failed to connect error: ' . $errno . ' ' . $errstr);
            throw new \RuntimeException('Failed to connect');
        }

        $this->log('Connected');
    }
}
