<?php

namespace Kartavik\Yii2\Behaviors;

use yii\base;
use yii\db;
use MyCLabs\Enum\Enum;

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
            db\ActiveRecord::EVENT_AFTER_FIND => 'after',
            db\ActiveRecord::EVENT_AFTER_INSERT => 'after',
            db\ActiveRecord::EVENT_BEFORE_INSERT => 'before',
            db\ActiveRecord::EVENT_BEFORE_UPDATE => 'before',
        ];
    }

    /**
     * @throws base\InvalidConfigException
     */
    public function before(): void
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
    public function after(): void
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
