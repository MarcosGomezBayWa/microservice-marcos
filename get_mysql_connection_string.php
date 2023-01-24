<?php

require __DIR__ . '/vendor/autoload.php';

$config = new Laminas\Config\Config(require __DIR__ . '/config/autoload/database.local.php');

echo sprintf(
    "mysql -u'%s' -p'%s' -h'%s' %s" . PHP_EOL,
    $config['doctrine']['connection']['orm_default']['params']['user'],
    $config['doctrine']['connection']['orm_default']['params']['password'],
    $config['doctrine']['connection']['orm_default']['params']['host'],
    $config['doctrine']['connection']['orm_default']['params']['dbname']
);
