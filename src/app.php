<?php

date_default_timezone_set('UTC');

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$bootstrap = new \Acme\Task\Bootstrap($app);

return $app;
