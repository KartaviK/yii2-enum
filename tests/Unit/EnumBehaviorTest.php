<?php

namespace Kartavik\Yii2\Tests\Unit;

use Kartavik\Yii2\Behaviors\EnumBehavior;
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
            'second' => Mock\TestEnum::SECOND,
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
            'first' => Mock\TestEnum::FIRST(),
            'second' => Mock\TestEnum::SECOND(),
        ]);

        $this->assertTrue($record->save());

        /** @var Mock\Record $find */
        $find = Mock\Record::find()->andWhere(['id' => $record->id])->all()[0];
        $this->assertNotSame($record, $find);

        $this->assertEquals(Mock\TestEnum::FIRST(), $find->first);
        $this->assertEquals(Mock\TestEnum::SECOND(), $find->second);
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
                        'class' => EnumBehavior::class,
                        'enumsAttributes' => [
                            'invalidEnumClass' => [
                                'value',
                            ]
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
}
