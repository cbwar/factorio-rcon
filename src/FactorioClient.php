<?php

namespace Cbwar\FactorioRcon;

readonly class FactorioClient
{

    public function __construct(private RCONClientInterface $client)
    {
    }

    private function parsePlayerList($payload): array
    {
        preg_match_all('/\s+([^(\n]+)/m', $payload, $matches);
        return array_map(static fn($match) => trim($match), $matches[1]);
    }

    public function getOnlinePlayers(): array
    {
        $data = $this->client->execute('/players online');
        return $this->parsePlayerList($data);
    }

    public function getRegisteredPlayers(): array
    {
        $data = $this->client->execute('/players');
        return $this->parsePlayerList($data);
    }

    public function getVersion(): string
    {
        return $this->client->execute('/version');
    }

    public function getTime(): string
    {
        return $this->client->execute('/time');
    }

    public function getAdmins(): array
    {
        $data = $this->client->execute('/admins');
        return $this->parsePlayerList($data);
    }

    public function getEvolution(): string
    {
        return $this->client->execute('/evolution');
    }

    public function getSeed(): string
    {
        return $this->client->execute('/seed');
    }
}
