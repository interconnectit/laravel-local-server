<?php

namespace InterconnectIt\LaravelLocalServer\Subcommands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class StartSubcommand extends Subcommand
{
    const COMMAND = 'docker-compose up -d --remove-orphans';

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $this->ensureNetworkPresence($output);

        $output->writeln('<info>Starting...</>');

        $isStartupFailed = $this->runProcess(static::COMMAND);

        if ($isStartupFailed) {
            $output->writeln('<error>Sorry, the local server is failed to start.</>');

            return $isStartupFailed;
        }

        $siteUrl = sprintf('http://%s.localtest.me/', $this->getProjectName());
        $databaseUrl = sprintf('http://phpmyadmin.%s.localtest.me/', $this->getProjectName());
        $mailhogUrl = sprintf('http://mailpit.%s.localtest.me/', $this->getProjectName());

        $output->writeln('');
        $output->writeln('<info>Your local server is ready!</>');
        $output->writeln(sprintf('<info>To access your site visit:</> <comment>%s</>', $siteUrl));
        $output->writeln(sprintf('<info>To access your database visit:</> <comment>%s</>', $databaseUrl));
        $output->writeln(sprintf('<info>To access your mail inbox visit:</> <comment>%s</>', $mailhogUrl));

        return $isStartupFailed;
    }

    protected function ensureNetworkPresence(OutputInterface $output): void
    {
        $requiredNetwork = 'laravel';

        $process = Process::fromShellCommandline(
            'docker network list --format="{{.Name}}"',
            $this->getConfigDirectory(),
            $this->getEnvironmentVariables()
        );

        $process->run();

        if (strpos($process->getOutput(), $requiredNetwork) !== false) {
            return;
        }

        $isFailed = $this->runProcess(sprintf('docker network create %s', $requiredNetwork));

        if ($isFailed) {
            $output->writeln('<error>Sorry, the local server is failed to setup the required network.</>');
        }
    }
}
