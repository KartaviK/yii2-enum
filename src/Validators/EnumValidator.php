<?php

namespace Kartavik\Yii2\Validators;

use MyCLabs\Enum\Enum;
use yii\validators;

/**
 * Class EnumValidator
 * @package Kartavik\Yii2\Validators
 */
class EnumValidator extends validators\Validator
{
    /** @var string|Enum */
    public $targetEnum;

    public function validateAttribute($model, $attribute): bool
    {
        $value = $model->$attribute;

        if ($value instanceof $this->targetEnum) {
            return true;
        }

        $model->addError($attribute, "Attribute [{$attribute}] must be instance {$this->targetEnum}");

        return false;
    }
}
