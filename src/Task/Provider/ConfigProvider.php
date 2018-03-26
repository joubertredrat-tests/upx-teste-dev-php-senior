<?php

namespace Acme\Task\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Config Provider
 *
 * @package Acme\Task\Provider
 */
class ConfigProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $rootDir = __DIR__ . '/../../../';
        $pimple['rootDir'] = $rootDir;
        $pimple['configDir'] = $rootDir . 'config/';
        $pimple['dbDir'] = $rootDir . 'db/';

        $configFile = $pimple['configDir'] . 'params.yml';

        if (!file_exists($configFile)) {
            throw new \RuntimeException('Config file not found');
        }

        $configData = Yaml::parseFile($configFile);

        $pimple['databaseConfig'] = $configData['params']['database'];
    }
}
