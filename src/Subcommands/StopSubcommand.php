<?php

namespace InterconnectIt\LaravelLocalServer\Subcommands;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

final class StopSubcommand
{
    /**
     * The application instance.
     *
     * @var Application
     */
    private $application;

    /**
     * Create a subcommand instance.
     *
     * @param Application $application
     *
     * @return void
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Invoke the subcommand.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function __invoke(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('Stopping...');

        $compose = new Process('docker-compose stop', 'vendor/interconnectit/laravel-local-server/docker', [
            'COMPOSE_PROJECT_NAME' => basename(getcwd()),
            'DOCKER_HOST'          => getenv('DOCKER_HOST'),
            'VOLUME'               => getcwd(),
            'PATH'                 => getenv('PATH'),
        ]);
        $compose->run(function ($_, $buffer) {
            echo $buffer;
        });

        $output->writeln('Stopped.');
    }
}
