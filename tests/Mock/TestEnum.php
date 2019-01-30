<?php

namespace Kartavik\Yii2\Tests\Mock;

use MyCLabs\Enum\Enum;

/**
 * Class TestEnum
 * @package Kartavik\Yii2\Tests\Mock
 *
 * @method static TestEnum FIRST()
 * @method static TestEnum SECOND()
 * @method static TestEnum THIRD()
 * @method static TestEnum NULLABLE()
 */
final class TestEnum extends Enum
{
    public const NULLABLE = null;
    public const FIRST = "first";
    public const SECOND = 'second';
    public const THIRD = 'third';
}
