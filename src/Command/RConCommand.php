<?php


namespace Cbwar\FactorioRcon\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'factorio:rcon')]
class RConCommand extends AbstractRconCommand
{
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setDescription('Send RCON command to server')
            ->setHelp('This command allows you to send RCON command to server')
            ->addArgument('cmd', InputArgument::REQUIRED, 'RCON command to send');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO: handle errors
        $output->write('Sending command: ' . $input->getArgument('cmd') . PHP_EOL);
        $data = $this->getClient($input)->execute($input->getArgument('cmd'));
        $output->write($data);
        return Command::SUCCESS;
    }
}
