<?php

namespace Kartavik\Yii2\Tests\Mock;

use Kartavik\Yii2;
use yii\db;

/**
 * Class Record
 * @package Kartavik\Yii2\Tests\Mock
 *
 * @property int $id
 * @property TestEnum $first
 * @property TestEnum $second
 */
class Record extends db\ActiveRecord
{
    public static function tableName()
    {
        return 'record';
    }

    public function init()
    {
        $this->trigger(Yii2\Behaviors\EnumMappingBehavior::EVENT_TO_ENUMS);
    }

    public function behaviors(): array
    {
        return [
            'enum' => [
                'class' => Yii2\Behaviors\EnumMappingBehavior::class,
                'map' => [
                    'first' => TestEnum::class,
                    'second' => TestEnum::class
                ],
            ],
        ];
    }

    public function rules()
    {
        return [
            [
                ['first', 'second'],
                Yii2\Validators\EnumValidator::class,
                'targetEnum' => TestEnum::class,
            ]
        ];
    }
}
