<?php

namespace InterconnectIt\LaravelLocalServer;

use Composer\Command\BaseCommand;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Command extends BaseCommand
{
    /**
     * Registered subcommands.
     *
     * @var array
     */
    private $subcommands = [
        'start'   => Subcommands\StartSubcommand::class,
        'stop'    => Subcommands\StopSubcommand::class,
        'destroy' => Subcommands\DestroySubcommand::class,
        'status'  => Subcommands\StatusSubcommand::class,
        'logs'    => Subcommands\LogsSubcommand::class,
        'artisan' => Subcommands\ArtisanSubcommand::class,
        'build'   => Subcommands\BuildSubcommand::class,
    ];

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('local-server')
             ->setDescription('Run the local server.')
             ->setDefinition([
                 new InputArgument('subcommand', InputArgument::REQUIRED, 'start, stop, destroy, status, logs, artisan'),
                 new InputArgument('options', InputArgument::IS_ARRAY),
             ])
             ->setHelp(
                 <<<EOT
Run the local server.

Start the local server:
    start
Stop the local server:
    stop
Destroy the local server:
    destroy
View the local server status:
    status
View the local server logs:
    logs <service>      <service> can be nginx, php, mysql, redis
Run artisan command:
    artisan -- <command>    eg: artisan -- migrate
EOT
             );
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $subcommand = $input->getArgument('subcommand');

        if (!isset($this->subcommands[$subcommand])) {
            throw new InvalidArgumentException('Invalid subcommand given: ' . $subcommand);
        }

        $subcommandClass    = $this->subcommands[$subcommand];
        $subcommandInstance = new $subcommandClass($this->getApplication());
        $subcommandInstance($input, $output);
    }
}
