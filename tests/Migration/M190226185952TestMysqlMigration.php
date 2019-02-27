<?php

namespace Kartavik\Yii2\Tests\Migration;

use Kartavik\Yii2\Database\Mysql;
use Kartavik\Yii2\Tests\Mock\TestEnum;

/**
 * Class M190226185952TestMysqlMigration
 */
class M190226185952TestMysqlMigration extends Mysql\Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->getDb()->getDriverName() === 'mysql') {
            $this->createTable('test_table', [
                'enum_column_from_array' => $this->enum(['1', '2', '3', '4']),
                'enum_column_from_enum' => $this->enum(TestEnum::class)
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->getDb()->getDriverName() === 'mysql') {
            $this->dropTable('test_table');
        }
    }
}
