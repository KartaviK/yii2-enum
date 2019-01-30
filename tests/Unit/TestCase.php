<?php

namespace Kartavik\Yii2\Tests\Unit;

use yii\phpunit;
use yii\helpers;

/**
 * Class TestCase
 * @package Kartavik\Yii2\Tests\Unit
 */
class TestCase extends phpunit\TestCase
{

    public function globalFixtures()
    {
        $fixtures = [
            [
                'class' => phpunit\MigrateFixture::class,
                'migrationNamespaces' => [
                    'Kartavik\\Yii2\\Tests\\Migration',
                ],
            ]
        ];
        return helpers\ArrayHelper::merge(parent::globalFixtures(), $fixtures);
    }
}
