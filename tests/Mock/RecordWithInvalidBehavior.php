<?php

namespace Kartavik\Yii2\Tests\Mock;

use Kartavik\Yii2\Behaviors\EnumMappingBehavior;
use yii\db\ActiveRecord;

/**
 * Class RecordWithInvalidBehavior.
 * @package Kartavik\Yii2\Tests\Mock
 */
class RecordWithInvalidBehavior extends ActiveRecord
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
}
