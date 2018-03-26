<?php

namespace Acme\Task\Command\Database;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * SchemaDrop Command
 *
 * @package Acme\Task\Command\Database
 */
class SchemaDropCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        parent::configure();

        $this
            ->setName('db:schema:drop')
            ->setDescription('Drop schema on database')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();

        $connection = $app['connection.sqlite'];
        /** @var \PDO $pdo */
        $pdo = $connection->getPdo();

        $pdo->query('DROP TABLE tasks_tags');
        $pdo->query('DROP TABLE task');
        $pdo->query('DROP TABLE tag');

        $output->write('Schema droped');
    }
}
