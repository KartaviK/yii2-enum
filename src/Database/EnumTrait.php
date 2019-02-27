<?php

namespace Kartavik\Yii2\Database;

use MyCLabs\Enum\Enum;

/**
 * Trait EnumTrait
 * @package Kartavik\Yii2\Database
 */
trait EnumTrait
{
    /**
     * @param array|Enum $enums
     *
     * @return array
     */
    private function convertEnums($enums): array
    {
        if (\is_string($enums) && \class_exists($enums) && \in_array(Enum::class, \class_parents($enums))) {
            /** @var Enum $enums */
            $enums = $enums::toArray();
        }

        return (array)$enums;
    }

    private function formatEnumValues(array $values): string
    {
        $values = \implode(
            ', ',
            \array_map(
                function ($value) {
                    return "'$value'";
                },
                \array_filter(
                    \array_map(function ($value) {
                        return (string)$value;
                    }, $values),
                    function ($value) {
                        return !empty($value);
                    }
                )
            )
        );

        return "ENUM({$values})";
    }
}
