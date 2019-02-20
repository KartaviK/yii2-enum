<?php

namespace Kartavik\Yii2\Tests\Migration;

use yii\db\Migration;

/**
 * Class M190129183210CreateRecordTable
 */
class M190129183210CreateRecordTable extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if ($this->getDb()->getDriverName() === 'pgsql') {
            $this->execute("CREATE TYPE test_enum as ENUM ('first', 'second', 'third', '123', '123.456')");
            $enum = 'test_enum';
        } else {
            $enum = "ENUM ('first', 'second', 'third', '123', '123.456')";
        }

        $this->createTable('record', [
            'id' => $this->primaryKey(),
            'first' => $enum,
            'second' => $enum,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('record');
        if ($this->getDb()->getDriverName() === 'pgsql') {
            $this->execute('DROP TYPE test_enum');
        }
    }
}
