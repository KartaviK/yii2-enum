<?php

namespace Kartavik\Yii2\Tests\Mock;

use MyCLabs\Enum\Enum;

/**
 * Class NumericEnum
 * @package Kartavik\Yii2\Tests\Mock
 *
 * @method static NumericEnum NUMERIC()
 * @method static NumericEnum REAL()
 */
final class NumericEnum extends Enum
{
    public const NUMERIC = 123;
    public const REAL = 123.456;
}
