<?php


namespace Cbwar\FactorioRcon\Command;

use Cbwar\FactorioRcon\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'rcon:send')]
class RConCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Send RCON command to server')
            ->setHelp('This command allows you to send RCON command to server')
            ->addArgument('host', InputArgument::REQUIRED, 'RCON host')
            ->addArgument('port', InputArgument::REQUIRED, 'RCON port')
            ->addArgument('password', InputArgument::REQUIRED, 'RCON password')
            ->addArgument('cmd', InputArgument::REQUIRED, 'RCON command to send')
            ->addArgument('timeout', InputArgument::OPTIONAL, 'Timeout in seconds', 2);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rcon = new Client($input->getArgument('host'),
            $input->getArgument('port'),
            $input->getArgument('password')
            , $input->getArgument('timeout'));

        // TODO: handle errors
        $response = $rcon->execute($input->getArgument('cmd'));
        $output->write($response);
        return Command::SUCCESS;
    }
}
