<?php

namespace InterconnectIt\LaravelLocalServer\Subcommands;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

abstract class Subcommand
{
    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    abstract public function __invoke(InputInterface $input, OutputInterface $output): int;

    protected function runProcess(string $command): int
    {
        $process = Process::fromShellCommandline(
            $command,
            $this->getConfigDirectory(),
            $this->getEnvironmentVariables()
        );

        $process->setTimeout(0);

        $exitCode = $process->run(function ($_, $buffer) {
            echo $buffer;
        });

        return $exitCode;
    }

    protected function getConfigDirectory(): string
    {
        return is_dir(getcwd().'/docker')
            ? getcwd().'/docker'
            : getcwd().'/vendor/interconnectit/laravel-local-server/docker';
    }

    protected function getEnvironmentVariables(): array
    {
        return [
            'COMPOSE_PROJECT_NAME' => $this->getProjectName(),
            'VOLUME'               => getcwd(),
            'PATH'                 => getenv('PATH'),

            // Windows-specific
            'TEMP'                 => getenv('TEMP'),
            'SystemRoot'           => getenv('SystemRoot'),
        ];
    }

    protected function getProjectName(): string
    {
        $projectName = basename(getcwd());
        $projectName = preg_replace('/[^A-Za-z0-9\-\_]/', '', $projectName);

        return $projectName;
    }
}
