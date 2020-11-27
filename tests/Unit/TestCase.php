<?php

namespace Kartavik\Yii2\Tests\Unit;

use Horat1us\Yii\PHPUnit\MigrateFixture;
use yii\helpers;

/**
 * Class TestCase
 * @package Kartavik\Yii2\Tests\Unit
 */
class TestCase extends \Horat1us\Yii\PHPUnit\TestCase
{
    public function globalFixtures()
    {
        $fixtures = [
            [
                'class' => MigrateFixture::class,
                'migrationNamespaces' => [
                    'Kartavik\\Yii2\\Tests\\Migration',
                ],
            ]
        ];
        return helpers\ArrayHelper::merge(parent::globalFixtures(), $fixtures);
    }
}
