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

    public function behaviors(): array
    {
        return [
            'enum' => [
                'class' => Yii2\Behaviors\EnumBehavior::class,
                'enumsAttributes' => [
                    TestEnum::class => [
                        'first',
                    ],
                ],
            ],
            'enum2' => [
                'class' => Yii2\Behaviors\EnumBehavior::class,
                'enumsAttributes' => [
                    TestEnum::class => 'second',
                ]
            ]
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
