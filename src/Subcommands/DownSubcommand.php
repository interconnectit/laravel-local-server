<?php

namespace InterconnectIt\LaravelLocalServer\Subcommands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownSubcommand extends Subcommand
{
    const COMMAND = 'docker-compose down --remove-orphans';

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Stopping and clearing containers...</>');

        return $this->runProcess(static::COMMAND);
    }
}
