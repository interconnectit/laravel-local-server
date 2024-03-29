<?php

namespace InterconnectIt\LaravelLocalServer\Subcommands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StopSubcommand extends Subcommand
{
    const COMMAND = 'docker-compose stop';

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Stopping...</>');

        return $this->runProcess(static::COMMAND);
    }
}
