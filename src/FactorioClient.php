<?php

namespace Cbwar\FactorioRcon;

readonly class FactorioClient
{

    public function __construct(private RCONClientInterface $client)
    {
    }

    public function getOnlinePlayers(): array
    {
        $data = $this->client->execute('/players online');
        preg_match_all('/\s+(\w+)$/', $data, $matches);
        return $matches[1];
    }

    public function getRegisteredPlayers(): array
    {
        $data = $this->client->execute('/players');
        preg_match_all('/\s+(\w+)$/', $data, $matches);
        return $matches[1];
    }
}
