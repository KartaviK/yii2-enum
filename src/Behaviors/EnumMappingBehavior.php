<?php
/**
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 * @license MIT
 * @see https://github.com/myclabs/php-enum
 * @version 1.0
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
     *      FirstYourEnum::class => [
     *          'attribute1,
     *          'attribute2',
     *      ],
     *      SecondYourEnum::class => [
     *          'attribute3,
     *          'attribute4',
     *      ],
     * ]
     * ```
     *
     * @var array
     */
    public $enumsAttributes;

    public function events(): array
    {
        return [
            EnumMappingBehavior::EVENT_TO_ENUMS => 'toEnums',
            EnumMappingBehavior::EVENT_TO_VALUES => 'toValues',
            db\ActiveRecord::EVENT_AFTER_FIND => 'toEnums',
            db\ActiveRecord::EVENT_AFTER_INSERT => 'toEnums',
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

        $fetchedAttributesEnums = $this->fetchAttributes();

        foreach ($fetchedAttributesEnums as $enum => $attributes) {
            foreach ($attributes as $attribute => $value) {
                if ($value instanceof $enum) {
                    /** @var \MyCLabs\Enum\Enum $value */
                    $this->owner->$attribute = $value->getValue();
                }
            }
        }
    }

    /**
     * @throws base\InvalidConfigException
     */
    public function toEnums(): void
    {
        $this->validateAttributes();

        $fetchedAttributesEnums = $this->fetchAttributes();

        foreach ($fetchedAttributesEnums as $enum => $attributes) {
            foreach ($attributes as $attribute => $value) {
                if (!$value instanceof Enum) {
                    $this->owner->$attribute = new $enum($value);
                }
            }
        }
    }

    protected function fetchAttributes(): array
    {
        $attributes = [];

        foreach ($this->enumsAttributes as $enum => $attrs) {
            foreach ($attrs as $attribute) {
                $attributes[$enum][$attribute] = $this->owner->$attribute;
            }
        }

        return $attributes;
    }

    /**
     * @throws base\InvalidConfigException
     */
    protected function validateAttributes(): void
    {
        foreach ($this->enumsAttributes as $enum => $attribute) {
            if (!\class_exists($enum) || !\in_array(Enum::class, \class_parents($enum), true)) {
                throw new base\InvalidConfigException("Class [$enum] must exist and implement " . Enum::class);
            }
            if (!\is_array($attribute)) {
                $this->enumsAttributes[$enum] = [$attribute];
            }
        }
    }
}
