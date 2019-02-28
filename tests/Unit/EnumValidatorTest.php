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
            'third' => Mock\TestEnum::THIRD()->getKey(),
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
                    "Attribute [second] must be instance or be part of Kartavik\Yii2\Tests\Mock\TestEnum"
                ],
            ],
            $model->getErrors()
        );
    }

    public function testNullableAttribute(): void
    {
        $model = new Mock\Model([
            'first' => Mock\TestEnum::NULLABLE(),
            'second' => null,
        ]);

        $this->assertTrue($model->validate());
    }

    public function testSimilarValues(): void
    {
        $model = new Mock\NumberRecord([
            'first' => Mock\NumericEnum::NUMERIC,
            'second' => (string)Mock\NumericEnum::REAL,
        ]);

        $this->assertFalse($model->validate());

        $this->assertEquals(
            [
                'second' => [
                    "Attribute [second] must be instance or be part of Kartavik\Yii2\Tests\Mock\NumericEnum"
                ],
            ],
            $model->getErrors()
        );
    }
}
