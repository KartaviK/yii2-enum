<?php

use yii\helpers\ArrayHelper;
use yii\db\Connection;

$localConfig = __DIR__ . DIRECTORY_SEPARATOR . 'config-local.php';
$dbType = getenv('DB_TYPE');
$host = getenv('DB_HOST');
$name = getenv("DB_NAME");
$port = getenv("DB_PORT");
if ($dbType === 'mariadb') {
    $dbType = 'mysql';
}
$dsn = "{$dbType}:host={$host};dbname={$name};port={$port}";
$config = [
    'id' => 'yii2-enum',
    'basePath' => \dirname(__DIR__),
    'components' => [
        'db' => array_merge(
            [
                'class' => Connection::class,
                'dsn' => $dsn,
                'username' => \getenv("DB_USERNAME"),
                'password' => \getenv("DB_PASSWORD") ?: null,
            ],
            $dbType == 'mariadb'
                ?
                [
                    'driverName' => 'mysql',
                    'schemaMap' => [
                        'mysql' => SamIT\Yii2\MariaDb\Schema::class
                    ]
                ]
                : []
        ),
    ],
];

return ArrayHelper::merge(
    $config,
    \is_file($localConfig) ? require $localConfig : []
);
