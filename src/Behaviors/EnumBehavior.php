<?php

namespace Kartavik\Yii2\Behaviors;

use MyCLabs\Enum\Enum;
use yii\base;
use yii\db;

/**
 * Class EnumBehavior
 *
 * ```php
 * return [
 *      'enum' => [
 *          'class' => \Kartavik\Yii2\Behaviors\EnumBehavior::class,
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
class EnumBehavior extends base\Behavior
{
    public const EVENT_TO_ENUMS = 'toEnums';
    public const EVENT_TO_VALUES = 'toValues';

    /**
     * Key used for EnumBehavior class, value is array of attributes that must be converted into this enum
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
            EnumBehavior::EVENT_TO_ENUMS => 'toEnums',
            EnumBehavior::EVENT_TO_VALUES => 'toValues',
            db\ActiveRecord::EVENT_AFTER_FIND => 'toValues',
            db\ActiveRecord::EVENT_AFTER_INSERT => 'toValues',
            db\ActiveRecord::EVENT_BEFORE_INSERT => 'toEnums',
            db\ActiveRecord::EVENT_BEFORE_UPDATE => 'toEnums'
        ];
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
    public function toValues(): void
    {
        $this->validateAttributes();

        $fetchedAttributesEnums = $this->fetchAttributes();

        foreach ($fetchedAttributesEnums as $enum => $attributes) {
            foreach ($attributes as $attribute => $value) {
                $this->owner->$attribute = new $enum($value);
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
