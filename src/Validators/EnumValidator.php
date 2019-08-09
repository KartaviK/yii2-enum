<?php
/**
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 * @license MIT
 * @see https://github.com/myclabs/php-enum PHP enum implementation
 * @version 1.0
 */

declare(strict_types=1);

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

    /**
     * @param mixed $value
     * @return array|null
     */
    protected function validateValue($value): ?array
    {
        $valid = $value instanceof $this->targetEnum
            || $this->useKey && $this->targetEnum::isValidKey($value)
            || $this->targetEnum::isValid($value);

        return !$valid ? [$this->getMessage(), []] : \null;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return \Yii::t(static::class, "Attribute [{attribute}] must be instance or be part of {$this->targetEnum}");
    }
}
