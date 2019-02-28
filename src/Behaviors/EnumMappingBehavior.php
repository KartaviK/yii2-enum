<?php
/**
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 * @license MIT
 * @see https://github.com/myclabs/php-enum
 * @version 2.0
 */

namespace Kartavik\Yii2\Behaviors;

use MyCLabs\Enum\Enum;
use yii\base;
use yii\db;

/**
 * Class EnumMappingBehavior
 *
 * ```php
 * return [
 *      'enum' => [
 *          'class' => \Kartavik\Yii2\Behaviors\EnumMappingBehavior::class,
 *          'enumsAttributes' => [
 *              FirstYourEnum::class => [
 *                  'attribute1,
 *                  'attribute2',
 *              ],
 *              SecondYourEnum::class => [
 *                  'attribute3,
 *                  'attribute4',
 *              ],
 *          ],
 *      ],
 * ];
 * ```
 *
 * @package Kartavik\Yii2\Behaviors
 * @since 1.0
 */
class EnumMappingBehavior extends base\Behavior
{
    public const EVENT_TO_ENUMS = 'toEnums';
    public const EVENT_TO_VALUES = 'toValues';

    /**
     * Key used for EnumMappingBehavior class, value is array of attributes that must be converted into this enum
     *
     * ```php
     * [
     *      'attribute1' => FirstYourEnum::class,
     *      'attribute2' => SecondYourEnum::class,
     * ]
     * ```
     *
     * @var array
     * @since 2.0
     */
    public $map;

    /**
     * In some cases database enum type can work only with string, so this parameter help behaviour to cast variable to
     * needs type
     *
     * ```php
     * [
     *      'attribute1' => 'integer',
     *      'attribute2' => 'float',
     * ]
     * ```
     *
     * @var array
     * @since 1.1
     */
    public $attributesType = [];

    /**
     * If you want use keys for mapping just add related attribute to this property
     *
     * ```php
     * [
     *      'attribute1', // values for this attrbiute will be as key in enum
     * ]
     * ```
     *
     * @var array
     */
    public $useKeyFor = [];

    public function events(): array
    {
        return [
            EnumMappingBehavior::EVENT_TO_ENUMS => 'toEnums',
            EnumMappingBehavior::EVENT_TO_VALUES => 'toValues',
            db\ActiveRecord::EVENT_AFTER_FIND => 'toEnums',
            db\ActiveRecord::EVENT_AFTER_INSERT => 'toEnums',
            db\ActiveRecord::EVENT_AFTER_UPDATE => 'toEnums',
            db\ActiveRecord::EVENT_BEFORE_INSERT => 'toValues',
            db\ActiveRecord::EVENT_BEFORE_UPDATE => 'toValues'
        ];
    }

    /**
     * @throws base\InvalidConfigException
     */
    public function toValues(): void
    {
        $this->validateAttributes();

        foreach ($this->map as $attribute => $enum) {
            $value = $this->owner->$attribute;

            if ($value instanceof $enum) {
                /** @var \MyCLabs\Enum\Enum $value */
                $enumValue = $this->isUseKey($attribute) ? $value->getKey() : $value->getValue();
                $this->castTypeIfExist($enumValue, $attribute);
                $this->owner->$attribute = $enumValue;
            }
        }
    }

    /**
     * @throws base\InvalidConfigException
     * @throws \UnexpectedValueException
     */
    public function toEnums(): void
    {
        $this->validateAttributes();

        foreach ($this->map as $attribute => $enum) {
            $value = $this->owner->$attribute;

            if (!$value instanceof Enum) {
                $this->castTypeIfExist($value, $attribute);
                $this->owner->$attribute = isset($this->owner->$attribute) && $this->isUseKey($attribute)
                    ? \call_user_func([$enum, $value])
                    : new $enum($value);
            }
        }
    }

    protected function castTypeIfExist(?string &$variable, string $attribute): void
    {
        if (isset($this->attributesType[$attribute])) {
            settype($variable, $this->attributesType[$attribute]);
        }
    }

    /**
     * @throws base\InvalidConfigException
     */
    protected function validateAttributes(): void
    {
        foreach ($this->map as $attribute => $enum) {
            if (!\class_exists($enum) || !\in_array(Enum::class, \class_parents($enum), true)) {
                throw new base\InvalidConfigException("Class [$enum] must exist and implement " . Enum::class);
            }
        }
    }

    protected function isUseKey(string $attribute): bool
    {
        return isset(array_flip($this->useKeyFor)[$attribute]);
    }
}
