<?php
/**
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 * @license MIT
 * @see https://github.com/myclabs/php-enum PHP enum implementation
 * @version 1.0
 */

namespace Kartavik\Yii2\Database\Mysql;

use Kartavik\Yii2\Database\EnumTrait;
use MyCLabs\Enum\Enum;
use yii\db;

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
     * @return db\Connection
     */
    abstract protected function getDb();

    /**
     * @param array|string|Enum $values
     *
     * @return db\ColumnSchemaBuilder
     * @throws \yii\base\NotSupportedException
     */
    public function enum($values): db\ColumnSchemaBuilder
    {
        return $this->getDb()
            ->getSchema()
            ->createColumnSchemaBuilder(
                $this->formatEnumValues(
                    $this->fetchEnums($values)
                )
            );
    }
}
