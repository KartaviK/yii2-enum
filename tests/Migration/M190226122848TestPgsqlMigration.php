<?php

namespace Kartavik\Yii2\Tests\Migration;

use Kartavik\Yii2\Database\Pgsql;
use Kartavik\Yii2\Tests\Mock\TestEnum;

/**
 * Class M190226122848TestPgsqlMigration
 */
class M190226122848TestPgsqlMigration extends Pgsql\Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->getDb()->getDriverName() === 'pgsql') {
            $this->addEnum('my_enum_type_created_from_array', ['first', 'second', 'third']);
            $this->addEnum('my_enum_type_created_from_enum', TestEnum::class);
            $this->addEnum('my_enum_type_created_from_enum', TestEnum::class);

            $this->createTable('test_table', [
                'id' => $this->primaryKey(),
                'column_first_enum_from_array' => $this->enum('my_enum_type_created_from_array'),
                'column_second_enum_from_enum' => $this->enum('my_enum_type_created_from_enum'),
                'column_third_enum_from_array_dynamic' =>
                    $this->enum('my_enum_type_created_from_array_dynamic', ['1', '2', '3'])
                        ->null()
                        ->defaultValue('1'),
                'column_third_enum_from_enum_dynamic' =>
                    $this->enum('my_enum_type_created_from_enum_dynamic', TestEnum::class)
                        ->notNull()
                        ->defaultValue('second'),
                'column_fourth_enum_with_nullable_value' =>
                    $this->enum('my_enum_type_created_from_enum_with_null_dynamic', TestEnum::class),
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->getDb()->getDriverName() === 'pgsql') {
            $this->dropTable('test_table');
            $this->dropEnum('my_enum_type_created_from_array');
            $this->dropEnum('my_enum_type_created_from_enum');
            $this->dropEnum('my_enum_type_created_from_array_dynamic');
            $this->dropEnum('my_enum_type_created_from_enum_dynamic');
            $this->dropEnum('my_enum_type_created_from_enum_with_null_dynamic');
        }
    }
}
