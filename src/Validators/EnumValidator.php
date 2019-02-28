<?php
/**
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 * @license MIT
 * @see https://github.com/myclabs/php-enum PHP enum implementation
 * @version 1.0
 */

namespace Kartavik\Yii2\Validators;

use MyCLabs\Enum\Enum;
use yii\validators;

/**
 * Class EnumValidator
 *
 * ```php
 * public function rules(): array
 * {
 *      return [
 *          [
 *              ['attribute1', 'attribute2'],
 *              Kartavik\Yii2\Validators\EnumValidator::class,
 *              'targetEnum' => YourEnum::class
 *          ]
 *      ]
 * }
 * ```
 *
 * @package Kartavik\Yii2\Validators
 * @since 1.0
 */
class EnumValidator extends validators\Validator
{
    /** @var string|Enum */
    public $targetEnum;

    /** @var bool */
    public $useKey = false;

    public function validateAttribute($model, $attribute): bool
    {
        $value = $model->$attribute;

        if ($value instanceof $this->targetEnum) {
            return true;
        }

        if ($this->useKey && $this->targetEnum::isValidKey($value)) {
            return true;
        } elseif ($this->targetEnum::isValid($value)) {
            return true;
        }

        $model->addError($attribute, "Attribute [{$attribute}] must be instance or be part of {$this->targetEnum}");

        return false;
    }
}
