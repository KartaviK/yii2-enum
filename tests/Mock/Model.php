<?php

namespace Kartavik\Yii2\Tests\Mock;

use Kartavik\Yii2;
use yii\base;

/**
 * Class Model
 * @package Kartavik\Yii2\Tests\Mock
 */
class Model extends base\Model
{
    /** @var TestEnum */
    public $first;

    /** @var TestEnum */
    public $second;

    public $third;

    public function behaviors(): array
    {
        return [
            'enum' => [
                'class' => Yii2\Behaviors\EnumMappingBehavior::class,
                'map' => [
                    'first' => TestEnum::class,
                    'second' => TestEnum::class,
                    'third' => TestEnum::class,
                ],
                'useKeyFor' => [
                    'third',
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
                'targetEnum' => TestEnum::class,
            ],
            [
                'third',
                Yii2\Validators\EnumValidator::class,
                'targetEnum' => TestEnum::class,
                'useKey' => true
            ]
        ];
    }
}
