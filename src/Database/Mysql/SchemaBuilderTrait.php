<?php

namespace Kartavik\Yii2\Database\Mysql;

use Kartavik\Yii2\Database\EnumTrait;
use MyCLabs\Enum\Enum;
use yii\db\ColumnSchemaBuilder;

/**
 * Trait SchemaBuilderTrait
 * @package Kartavik\Yii2\Database\Mysql
 *
 * @mixin \yii\db\Migration
 */
trait SchemaBuilderTrait
{
    use EnumTrait;

    /**
     * @param array|string|Enum $values
     *
     * @return ColumnSchemaBuilder
     * @throws \yii\base\NotSupportedException
     */
    public function enum($values): ColumnSchemaBuilder
    {
        return $this->getDb()
            ->getSchema()
            ->createColumnSchemaBuilder(
                $this->formatEnumValues(
                    $this->convertEnums($values)
                )
            );
    }
}
