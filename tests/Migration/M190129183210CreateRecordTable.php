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
        $this->execute("CREATE TYPE test_enum as ENUM ('first', 'second', 'third')");
        $this->createTable('record', [
            'id' => $this->primaryKey(),
            'first' => 'test_enum',
            'second' => 'test_enum',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('record');
        $this->execute('DROP TYPE test_enum');
    }
}
