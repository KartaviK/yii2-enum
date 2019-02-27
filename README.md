
<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Enum integration for Yii Framework 2.0</h1>
    <br>
</p>

# Yii2 enum

[![Latest Stable Version](https://poser.pugx.org/kartavik/yii2-enum/v/stable?format=flat)](https://packagist.org/packages/kartavik/yii2-enum)
[![Build Status](https://travis-ci.com/KartaviK/yii2-enum.svg?branch=master)](https://travis-ci.com/KartaviK/yii2-enum)
[![codecov](https://codecov.io/gh/KartaviK/yii2-enum/branch/master/graph/badge.svg)](https://codecov.io/gh/KartaviK/yii2-enum)

Based on [myclabs\php-enum](https://github.com/myclabs/php-enum) package;

## Installation

```bash
composer require kartavik/yii2-enum
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

### Validator

This validator used `MyCLabs\Enum\Enum::isValid($value)` method and also checked value on instance of your enum;

```php
<?php

use YourEnum;
use Kartavik\Yii2;

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
                'class' => Yii2\Behaviors\EnumMappingBehavior::class,
                'map' => [
                    'attribute1' => YourEnum::class,
                    'attribute2' => YourEnum::class,
                ],
                // Set attribute type if need explicitly specify it
                'attributesType' => [
                    'attribute1' => 'integer',
                    'attribute2' => 'float'
                ],
            ]
        ];
    }
    
    public function rules(){
        return [
            [
                ['attribute1', 'attribute2'],
                Yii2\Validators\EnumValidator::class,
                'targetEnum' => YourEnum::class,
            ]
        ];
    }
}

$record = new Record([
    'attribute1' => YourEnum::FIRST_VALUE, // use constant
    'attribute2' => YourEnum::SECOND_VALUE(), // use method
]);

$record->trigger(Yii2\Behaviors\EnumMappingBehavior::EVENT_TO_ENUMS); // trigger if you put values not instance of Enum

$record->validate(); // will return true
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
                'class' => \Kartavik\Yii2\Behaviors\EnumMappingBehavior::class,
                'map' => [
                    'attribute1' => YourEnum::class,
                    'attribute2' => YourEnum::class,
                ]
            ]
        ];
    }
}

$record = new Record([
    'attribute1' => YourEnum::FIRST_VALUE, // use const
    'attribute2' => YourEnum::SECOND_VALUE() // use method
]);

$this->trigger(\Kartavik\Yii2\Behaviors\EnumMappingBehavior::EVENT_TO_ENUMS);
$record->save(); // will return true

$record = Record::find()->where(['id' => $record->id])->all()[0];

print_r($record->attribute1); // Will output YourEnum object with value `first`

Record::updateAll(['attribute1' => YourEnum::SECOND_VALUE()]); // Updating records with new enum

$record = Record::find()->where(['id' => $record->id])->all()[0];

print_r($record->attribute1); // Will output YourEnum object with value `second`
```

### Migration

#### PgSql

```php
<?php

use Kartavik\Yii2\Database\Pgsql;

class CreateRecordTable extends Pgsql\Migration
{
    // You can use trait do receive all methods for enum
    use Pgsql\MigrationTrait;
    
    public function safeUp()
    {
         // simple usage
        $this->addEnum('test_enum_from_array', [/** your values */]);
         // use your enum that extends MyCLabs\Enum\Enum
         // use it for package, for big project this usage is not good practice
        $this->addEnum('test_enum_from_enum', YourEnum::class);
        
         // if enum already exists
        $column = $this->enum('test_enum_from_array');
         // you can do not to use addEnum to create it
        $column = $this->enum('test_enum_dynamic_from_array', [/** your values */]);
         // use your enum that extends MyCLabs\Enum\Enum
         // use it for package, for big project this usage is not good practice
        $column = $this->enum('test_enum_dynamic_from_enum', YourEnum::class);
        
        $this->createTable('record', [
            'enum' => $column->null()->default('default')
        ]);
    }
}
```

#### MySql

```php
<?php

use Kartavik\Yii2\Database\Mysql;

class CreateRecordTable extends Mysql\Migration
{
    // You can use trait do receive all methods for enum
    use Mysql\MigrationTrait;
    
    public function safeUp()
    {
        $this->createTable('record', [
            'enum_column_from_array' => $this->enum(['1', '2', '3', '4']),
            // use your enum that extends MyCLabs\Enum\Enum
            // use it for package, for big project this usage is not good practice
            'enum_column_from_enum' => $this->enum(YourEnum::class),
        ]);
    }
}
```

## Suggest
- [horat1us/yii2-base](https://github.com/Horat1us/yii2-base)
 ([ConstRangeValidator](https://github.com/Horat1us/yii2-base/blob/master/src/Validators/ConstRangeValidator.php))- 
 if you need better validation for ranges of constants.

## Author
- [Roman Varkuta](mailto:roman.varkuta@gmail.com)

## License
[MIT](./LICENSE)
