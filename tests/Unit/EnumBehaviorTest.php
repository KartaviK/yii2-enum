<?php

namespace Kartavik\Yii2\Tests\Unit;

use Kartavik\Yii2\Behaviors\EnumMappingBehavior;
use Kartavik\Yii2\Tests\Mock;
use yii\base;
use yii\db\ActiveRecord;

/**
 * Class EnumBehaviorTest
 * @package Kartavik\Yii2\Tests\Unit
 */
class EnumBehaviorTest extends TestCase
{
    public function testModelValidate(): void
    {
        $model = new Mock\Model([
            'first' => Mock\TestEnum::FIRST(),
            'second' => Mock\TestEnum::SECOND(),
        ]);

        $this->assertTrue($model->validate());

        $this->assertEquals(Mock\TestEnum::FIRST(), $model->first);
        $this->assertEquals(Mock\TestEnum::SECOND(), $model->second);
    }

    public function testInsertRecord(): void
    {
        $record = new Mock\Record([
            'first' => Mock\TestEnum::FIRST(),
            'second' => Mock\TestEnum::SECOND(),
        ]);

        $this->assertTrue($record->save());

        $this->assertEquals(Mock\TestEnum::FIRST(), $record->first);
        $this->assertEquals(Mock\TestEnum::SECOND(), $record->second);
    }

    public function testFindRecord(): void
    {
        $record = new Mock\Record([
            'first' => Mock\TestEnum::FIRST,
            'second' => Mock\TestEnum::SECOND,
        ]);

        $this->assertEquals(Mock\TestEnum::FIRST, $record->first);
        $this->assertEquals(Mock\TestEnum::SECOND, $record->second);

        $this->assertTrue($record->save());

        $this->assertEquals(Mock\TestEnum::FIRST(), $record->first);
        $this->assertEquals(Mock\TestEnum::SECOND(), $record->second);

        /** @var Mock\Record $find */
        $find = Mock\Record::find()->andWhere(['id' => $record->id])->all()[0];

        $this->assertNotSame($record, $find);

        $this->assertEquals(Mock\TestEnum::FIRST(), $find->first);
        $this->assertEquals(Mock\TestEnum::SECOND(), $find->second);
    }

    public function testUpdateRecord(): void
    {
        $record = new Mock\Record([
            'first' => Mock\TestEnum::FIRST(),
            'second' => Mock\TestEnum::SECOND(),
        ]);

        $this->assertEquals(Mock\TestEnum::FIRST(), $record->first);
        $this->assertEquals(Mock\TestEnum::SECOND(), $record->second);

        $this->assertTrue($record->save());

        $this->assertEquals(Mock\TestEnum::FIRST(), $record->first);
        $this->assertEquals(Mock\TestEnum::SECOND(), $record->second);

        $newEnum = Mock\TestEnum::THIRD();

        $this->assertGreaterThanOrEqual(1, Mock\Record::updateAll(['first' => $newEnum]));

        /** @var Mock\Record $find */
        $find = Mock\Record::find()->andWhere(['id' => $record->id])->all()[0];

        $this->assertEquals($newEnum, $find->first);
    }

    public function testInvalidEnumClass(): void
    {
        $this->expectException(base\InvalidConfigException::class);
        $this->expectExceptionMessage('Class [invalidEnumClass] must exist and implement MyCLabs\Enum\Enum');

        $model = new class(['value' => 'value']) extends ActiveRecord
        {
            public static function tableName()
            {
                return 'record';
            }

            /** @var string */
            public $value;

            public function behaviors(): array
            {
                return [
                    'enum' => [
                        'class' => EnumMappingBehavior::class,
                        'map' => [
                            'value' => 'invalidEnumClass',
                        ]
                    ]
                ];
            }

            public function rules(): array
            {
                return[
                    ['value', 'string'],
                ];
            }
        };

        $model->save();
    }

    public function testTriggerEventToValues(): void
    {
        $record = new Mock\Record([
            'first' => Mock\TestEnum::FIRST(),
            'second' => Mock\TestEnum::SECOND(),
        ]);

        $this->assertEquals(Mock\TestEnum::FIRST(), $record->first);
        $this->assertEquals(Mock\TestEnum::SECOND(), $record->second);

        $record->trigger(EnumMappingBehavior::EVENT_TO_VALUES);

        $this->assertEquals(Mock\TestEnum::FIRST, $record->first);
        $this->assertEquals(Mock\TestEnum::SECOND, $record->second);

        $record->trigger(EnumMappingBehavior::EVENT_TO_ENUMS);

        $this->assertEquals(Mock\TestEnum::FIRST(), $record->first);
        $this->assertEquals(Mock\TestEnum::SECOND(), $record->second);
    }

    public function testCastNumbers(): void
    {
        $record = new Mock\NumberRecord([
            'first' => Mock\NumericEnum::NUMERIC(),
            'second' => Mock\NumericEnum::REAL(),
        ]);

        $this->assertEquals(Mock\NumericEnum::NUMERIC(), $record->first);
        $this->assertEquals(Mock\NumericEnum::REAL(), $record->second);

        $record->trigger(EnumMappingBehavior::EVENT_TO_VALUES);

        $this->assertEquals(Mock\NumericEnum::NUMERIC, $record->first);
        $this->assertEquals(Mock\NumericEnum::REAL, $record->second);

        $this->assertIsInt($record->first);
        $this->assertIsFloat($record->second);

        $record->trigger(EnumMappingBehavior::EVENT_TO_ENUMS);

        $this->assertEquals(Mock\NumericEnum::NUMERIC(), $record->first);
        $this->assertEquals(Mock\NumericEnum::REAL(), $record->second);

        $this->assertTrue($record->save());

        $find = Mock\NumberRecord::find()->andWhere(['id' => $record->id])->all()[0];

        $this->assertEquals(Mock\NumericEnum::NUMERIC(), $find->first);
        $this->assertEquals(Mock\NumericEnum::REAL(), $find->second);

        $find->trigger(EnumMappingBehavior::EVENT_TO_VALUES);

        $this->assertEquals(Mock\NumericEnum::NUMERIC, $find->first);
        $this->assertEquals(Mock\NumericEnum::REAL, $find->second);

        $this->assertIsInt($find->first);
        $this->assertIsFloat($find->second);
    }

    public function testUseKey(): void
    {
        $record = new Mock\NumberRecord([
            'first' => Mock\NumericEnum::NUMERIC(),
            'second' => Mock\NumericEnum::REAL(),
            'third' => Mock\TestEnum::SECOND(),
        ]);

        $this->assertEquals(Mock\NumericEnum::NUMERIC(), $record->first);
        $this->assertEquals(Mock\NumericEnum::REAL(), $record->second);
        $this->assertEquals(Mock\TestEnum::SECOND(), $record->third);

        $record->trigger(EnumMappingBehavior::EVENT_TO_VALUES);

        $this->assertEquals(Mock\NumericEnum::NUMERIC, $record->first);
        $this->assertEquals(Mock\NumericEnum::REAL, $record->second);
        $this->assertEquals(Mock\TestEnum::SECOND()->getKey(), $record->third);

        $this->assertIsInt($record->first);
        $this->assertIsFloat($record->second);
        $this->assertIsString($record->third);

        $record->trigger(EnumMappingBehavior::EVENT_TO_ENUMS);

        $this->assertEquals(Mock\NumericEnum::NUMERIC(), $record->first);
        $this->assertEquals(Mock\NumericEnum::REAL(), $record->second);
        $this->assertEquals(Mock\TestEnum::SECOND(), $record->third);

        $this->assertTrue($record->save());

        $find = Mock\NumberRecord::find()->andWhere(['id' => $record->id])->all()[0];

        $this->assertEquals(Mock\NumericEnum::NUMERIC(), $find->first);
        $this->assertEquals(Mock\NumericEnum::REAL(), $find->second);
        $this->assertEquals(Mock\TestEnum::SECOND(), $find->third);

        $find->trigger(EnumMappingBehavior::EVENT_TO_VALUES);

        $this->assertEquals(Mock\NumericEnum::NUMERIC, $find->first);
        $this->assertEquals(Mock\NumericEnum::REAL, $find->second);
        $this->assertEquals(Mock\TestEnum::SECOND()->getKey(), $find->third);

        $this->assertIsInt($find->first);
        $this->assertIsFloat($find->second);
        $this->assertIsString($find->third);
    }
}
