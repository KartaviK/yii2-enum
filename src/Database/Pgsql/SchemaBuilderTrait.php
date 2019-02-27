<?php

namespace Kartavik\Yii2\Database\Pgsql;

use Kartavik\Yii2\Database\EnumTrait;
use MyCLabs\Enum\Enum;
use yii\db\ColumnSchemaBuilder;

/**
 * Trait SchemaBuilderTrait
 * @package Kartavik\Yii2\Database\Pgsql
 *
 * @mixin \yii\db\Migration
 */
trait SchemaBuilderTrait
{
    use EnumTrait;

    /**
     * @param string $name
     * @param array|Enum|null $values
     *
     * @return ColumnSchemaBuilder
     * @throws \yii\base\NotSupportedException
     */
    public function enum(string $name, $values = null): ColumnSchemaBuilder
    {
        $values = $this->convertEnums($values);

        if (!empty($values)) {
            $this->addEnum($name, $values);
        }

        $column = $this->getDb()->getSchema()->createColumnSchemaBuilder($name);

        if (\in_array(null, $values, true)) {
            $column->null();
        } else {
            $column->notNull();
        }

        return $column;
    }
}
