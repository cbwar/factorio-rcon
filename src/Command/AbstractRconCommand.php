<?php

namespace Cbwar\FactorioRcon\Command;

use Cbwar\FactorioRcon\Client;
use Cbwar\FactorioRcon\FactorioClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

abstract class AbstractRconCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('host', InputArgument::REQUIRED, 'RCON host')
            ->addArgument('port', InputArgument::REQUIRED, 'RCON port')
            ->addArgument('password', InputArgument::REQUIRED, 'RCON password')
            ->addOption('timeout', 't', InputArgument::OPTIONAL, 'Timeout in seconds', 2);
    }

    protected function getClient(InputInterface $input): Client
    {
        return new Client($input->getArgument('host'),
            $input->getArgument('port'),
            $input->getArgument('password'),
            $input->getOption('timeout'));
    }
    protected function getFactorioClient(InputInterface $input): FactorioClient
    {
        return new FactorioClient($this->getClient($input));
    }

}
