<?php

namespace Kartavik\Yii2\Database\Pgsql;

use MyCLabs\Enum\Enum;
use yii\db;

/**
 * Trait MigrationTrait
 * @package Kartavik\Yii2\Database\Pgsql
 */
trait MigrationTrait
{
    use SchemaBuilderTrait;

    /**
     * @param string $name
     * @param array|Enum $enums
     *
     * @throws db\Exception
     */
    public function addEnum(string $name, $enums): void
    {
        $enums = $this->convertEnums($enums);

        if (!\is_array($enums)) {
            throw new db\Exception('Variable [values] must be an array or be instance of ' . Enum::class);
        }

        $enums = $this->formatEnumValues($enums);

        $transaction = $this->getDb()->beginTransaction();
        $command = $transaction->db->createCommand("CREATE TYPE $name AS $enums");
        try {
            echo "    > create type $name ... ";
            $command->execute();
            echo 'done';
        } catch (\Exception $exception) {
            echo "already exist, skipping";
            $transaction->rollBack();
        }
        echo PHP_EOL;
    }

    /**
     * @param string $name
     *
     * @throws db\Exception
     */
    public function dropEnum(string $name): void
    {
        $this->getDb()->createCommand("DROP TYPE IF EXISTS $name")->execute();
    }
}
