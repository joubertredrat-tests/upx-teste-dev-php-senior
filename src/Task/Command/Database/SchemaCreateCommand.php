<?php

namespace Acme\Task\Command\Database;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * SchemaCreate Command
 *
 * @package Acme\Task\Command\Database
 */
class SchemaCreateCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        parent::configure();

        $this
            ->setName('db:schema:create')
            ->setDescription('Create schema on database')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();

        $connection = $app['connection.sqlite'];
        /** @var \PDO $pdo */
        $pdo = $connection->getPdo();

        $pdo->query('CREATE TABLE `task` (
                `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                `title` TEXT NOT NULL,
                `description` TEXT,
                `is_done` INTEGER NOT NULL,
                `created` TEXT NOT NULL,
                `updated` TEXT
            );'
        );

        $pdo->query('CREATE TABLE `tag` (
                `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                `name` TEXT NOT NULL,
                `text_color` TEXT NOT NULL,
                `background_color` TEXT NOT NULL,
                `created` TEXT NOT NULL,
                `updated` TEXT
            );'
        );

        $pdo->query('CREATE TABLE `tasks_tags` (
                `task_id` INTEGER NOT NULL,
                `tag_id` INTEGER NOT NULL,
                FOREIGN KEY (`task_id`) REFERENCES `task`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`tag_id`) REFERENCES `tag`(`id`) ON DELETE CASCADE
            );'
        );

        $output->write('Schema created');
    }
}
