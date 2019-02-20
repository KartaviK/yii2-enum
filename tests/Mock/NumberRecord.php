<?php

namespace Kartavik\Yii2\Tests\Mock;

use Kartavik\Yii2;
use yii\db;

/**
 * Class NumberRecord
 * @package Kartavik\Yii2\Tests\Mock
 *
 * @property int $id
 * @property TestEnum $first
 * @property TestEnum $second
 */
class NumberRecord extends db\ActiveRecord
{
    public static function tableName()
    {
        return 'record';
    }

    public function behaviors(): array
    {
        return [
            'enum' => [
                'class' => Yii2\Behaviors\EnumMappingBehavior::class,
                'map' => [
                    'first' => NumericEnum::class,
                    'second' => NumericEnum::class,
                ],
                'attributesType' => [
                    'first' => 'integer',
                    'second' => 'float'
                ]
            ],
        ];
    }

    public function rules()
    {
        return [
            [
                ['first', 'second'],
                Yii2\Validators\EnumValidator::class,
                'targetEnum' => NumericEnum::class,
            ]
        ];
    }
}
