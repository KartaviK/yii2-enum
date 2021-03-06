<?php

/**
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 * @license MIT
 * @see https://github.com/myclabs/php-enum PHP enum implementation
 * @version 1.0
 */

declare(strict_types=1);

namespace Kartavik\Yii2\Database;

use MyCLabs\Enum\Enum;

/**
 * Trait EnumTrait
 * @package Kartavik\Yii2\Database
 */
trait EnumTrait
{
    /**
     * @param iterable|string|Enum|null $enums
     *
     * @return array
     */
    private function fetchEnums($enums): array
    {
        if (\is_string($enums) && \in_array(Enum::class, \class_parents($enums))) {
            /** @var Enum $enums */
            $enums = $enums::toArray();
        }

        return (array)$enums;
    }

    /**
     * @param iterable $values
     * @return string
     */
    private function formatEnumValues(iterable $values): string
    {
        $values = \implode(
            ', ',
            \array_map(
                function ($value) {
                    return "'{$value}'";
                },
                \array_filter(
                    \array_map(function ($value) {
                        return (string)$value;
                    }, (array)$values),
                    function ($value) {
                        return !empty($value);
                    }
                )
            )
        );

        return "ENUM({$values})";
    }
}
