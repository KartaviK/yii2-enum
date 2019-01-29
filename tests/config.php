<?php

use yii\helpers\ArrayHelper;
use yii\db\Connection;

$localConfig = __DIR__ . DIRECTORY_SEPARATOR . 'config-local.php';
$dbType = getenv('DB_TYPE');
$host = getenv('DB_HOST');
$name = getenv("DB_NAME");
$port = getenv("DB_PORT");
$dsn = "{$dbType}:host={$host};dbname={$name};port={$port}";
$config = [
    'id' => 'yii2-enum',
    'basePath' => \dirname(__DIR__),
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => $dsn,
            'username' => \getenv("DB_USERNAME"),
            'password' => \getenv("DB_PASSWORD") ?: null,
        ],
    ],
];

return ArrayHelper::merge(
    $config,
    \is_file($localConfig) ? require $localConfig : []
);
