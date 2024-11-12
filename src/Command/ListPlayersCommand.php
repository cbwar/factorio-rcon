<?php

namespace Cbwar\FactorioRcon\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'factorio:players')]
class ListPlayersCommand extends AbstractRconCommand
{
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setDescription('List online players on server')
            ->setHelp('This command allows you to list players on server');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $players = $this->getFactorioClient($input)->getOnlinePlayers();
        $data = implode(', ', $players);
        $output->write($data);
        return Command::SUCCESS;

    }

}
