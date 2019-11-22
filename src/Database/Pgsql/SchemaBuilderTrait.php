<?php
/**
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 * @license MIT
 * @see https://github.com/myclabs/php-enum PHP enum implementation
 * @version 1.0
 */

declare(strict_types=1);

namespace Kartavik\Yii2\Database\Pgsql;

use Kartavik\Yii2\Database\EnumTrait;
use MyCLabs\Enum\Enum;
use yii\db;

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
     * @return db\ColumnSchemaBuilder
     * @throws \yii\base\NotSupportedException
     */
    public function enum(string $name, $values = null): db\ColumnSchemaBuilder
    {
        $values = $this->fetchEnums($values);

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
