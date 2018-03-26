<?php

namespace Acme\Task\Command\Database;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * DatabaseDrop Command
 *
 * @package Acme\Task\Command\Database
 */
class DatabaseDropCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('db:database:drop')
            ->setDescription('Create drop')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();

        $filePath = $app['dbDir'] . $app['databaseConfig']['sqlite']['file'];
        unlink($filePath);

        $output->write('Database droped');
    }
}
