<?php

namespace Kartavik\Yii2\Database;

use yii\db\ColumnSchemaBuilder;
use yii\db\Exception;

/**
 * Class Migration
 * @package Kartavik\Yii2\Database
 */
abstract class Migration extends \yii\db\Migration
{
    use Pgsql\MigrationTrait, Mysql\MigrationTrait {
        Pgsql\MigrationTrait::enum as protected pgsqlEnum;
        Mysql\MigrationTrait::enum as protected mysqlEnum;
    }

    /**
     * @param array $values
     * @param string|null $name
     *
     * @return ColumnSchemaBuilder
     * @throws Exception
     * @throws \yii\base\NotSupportedException
     */
    protected function enum($values, string $name = null): ColumnSchemaBuilder
    {
        if ($this->getDb()->getDriverName() === 'pgsql') {
            if (!isset($name)) {
                throw new Exception('Name for enum must be set');
            }

            return $this->pgsqlEnum($name, $values);
        }

        if ($this->getDb()->getDriverName() === 'mysql') {
            return $this->mysqlEnum($values);
        }

        throw new Exception("Enum type not supported for {$this->getDb()->getDriverName()}");
    }
}
