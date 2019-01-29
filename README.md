# Yii2 enum

Based on [myclabs\php-enum](https://github.com/myclabs/php-enum) package;

## Installation

```bash
composer require kartavk/yii2-enum
```

## Usage

Example enum:

```php
<?php

class YourEnum extends \MyCLabs\Enum\Enum
{
    public const FIRST_VALUE = 'first';
    public const SECOND_VALUE = 'second';
}
```

### Behavior

It can be used for classes that extends ActiveRecord

```php
<?php

use YourEnum;

/**
 * @property YourEnum $attribute1
 * @property YourEnum $attribute2
 */
class Record extends \yii\db\ActiveRecord
{
    public function behaviors(): array
    {
        return [
            'enum' => [
                'class' => \Kartavik\Yii2\Behaviors\EnumBehavior::class,
                'enumsAttributes' => [
                    YourEnum::class => [
                        'attribute1',
                        'attribute2',
                    ]
                ]
            ]
        ];
    }
}

$record = new Record([
    'attribute1' => YourEnum::FIRST_VALUE, // use const
    'attribute2' => YourEnum::SECOND_VALUE() // use method
]);

$record->save(); // will return true
```

You can trigger it by your self

```php
<?php

use YourEnum;

class SomeService extends \yii\base\Model
{
    /** @var YourEnum */
    public $value;
    
    public function behaviors(): array
        {
            return [
                'enum' => [
                    'class' => \Kartavik\Yii2\Behaviors\EnumBehavior::class,
                    'enumsAttributes' => [
                        YourEnum::class => [
                            'value',
                        ]
                    ]
                ]
            ];
        }
    
    public function init(): void
    {
        $this->trigger(\Kartavik\Yii2\Behaviors\EnumBehavior::EVENT_TO_ENUMS);
    }
}
```

## Validator

This validator used `MyCLabs\Enum\Enum::isValid($value)` method and also checked value on instance of your enum;

```php
<?php

use YourEnum;

/**
 * @property YourEnum $attribute1
 * @property YourEnum $attribute2
 */
class Record extends \yii\db\ActiveRecord
{
    public function behaviors(): array
    {
        return [
            'enum' => [
                'class' => \Kartavik\Yii2\Behaviors\EnumBehavior::class,
                'enumsAttributes' => [
                    YourEnum::class => [
                        'attribute1',
                        'attribute2',
                    ]
                ]
            ]
        ];
    }
    
    public function rules(){
        return [
            [
                ['attribute1', 'attribute2'],
                \Kartavik\Yii2\Validators\EnumValidator::class,
                'targetEnum' => YourEnum::class,
            ]
        ];
    }
}

$record = new Record([
    'attribute1' => YourEnum::FIRST_VALUE, // use const
    'attribute2' => YourEnum::SECOND_VALUE() // use method
]);

$record->validate(); // will return true
```

## Suggest
- [horat1us/yii2-base](https://github.com/Horat1us/yii2-base)
 ([ConstRangeValidator](https://github.com/Horat1us/yii2-base/blob/master/src/Validators/ConstRangeValidator.php))- 
 if you need better validation for ranges of constants.

## Author
- [Roman Varkuta](mailto:roman.varkuta@gmail.com)

## License
[MIT](./LICENSE)
