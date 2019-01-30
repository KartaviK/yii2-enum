<?php

if (\file_exists(\dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env')) {
    $dotEnv = \Dotenv\Dotenv::create(\dirname(__DIR__));
    $dotEnv->load();
}

\Yii::setAlias('@Kartavik/Yii2', \dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');
\Yii::setAlias('@Kartavik/Yii2/Tests', \dirname(__DIR__) . DIRECTORY_SEPARATOR . 'tests');
\Yii::setAlias('@configFile', __DIR__ . DIRECTORY_SEPARATOR . 'config.php');
