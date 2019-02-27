<?php

namespace Kartavik\Yii2\Tests\Migration;

use Kartavik\Yii2\Database;
use Kartavik\Yii2\Tests\Mock\TestEnum;

/**
 * Class M190227140142TestAbstractMigration
 */
class M190227140142TestAbstractMigration extends Database\Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('test_table_for_asbtract_migration', [
            'enum_from_array' => $this->enum(['1', '2', '3'], 'test_name_enum'),
            'enum_from_enum' => $this->enum(TestEnum::class, 'test_name_enum_second'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('test_table_for_asbtract_migration');
    }
}
