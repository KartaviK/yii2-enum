<?php

namespace Kartavik\Yii2\Tests\Unit;

use Kartavik\Yii2\Tests\Mock;

/**
 * Class EnumValidatorTest
 * @package Kartavik\Yii2\Tests\Unit
 */
class EnumValidatorTest extends TestCase
{
    public function testSuccess(): void
    {
        $model = new Mock\Model([
            'first' => Mock\TestEnum::FIRST(),
            'second' => Mock\TestEnum::SECOND,
        ]);

        $this->assertTrue($model->validate());
    }

    public function testFailed(): void
    {
        $model = new Mock\Model([
            'first' => Mock\TestEnum::FIRST(),
            'second' => 'invalid',
        ]);

        $this->assertFalse($model->validate());

        $this->assertEquals(
            [
                'second' => [
                    "Attribute [second] must be instance Kartavik\Yii2\Tests\Mock\TestEnum"
                ],
            ],
            $model->getErrors()
        );
    }
}
