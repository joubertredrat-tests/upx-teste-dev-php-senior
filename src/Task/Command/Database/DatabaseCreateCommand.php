<?php

namespace Acme\Task\Command\Database;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseCreateCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        parent::configure();

        $this
            ->setName('db:database:create')
            ->setDescription('Create database')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();

        $filePath = $app['dbDir'] . $app['databaseConfig']['sqlite']['file'];
        touch($filePath);

        $output->write('Database created');
    }
}
