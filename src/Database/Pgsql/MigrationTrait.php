<?php

/**
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 * @license MIT
 * @see https://github.com/myclabs/php-enum PHP enum implementation
 * @version 1.0
 */

declare(strict_types=1);

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
     * @param array|Enum|string $enums
     *
     * @throws db\Exception
     */
    public function addEnum(string $name, $enums): void
    {
        $transaction = $this->getDb()->beginTransaction();
        $command = $transaction->db->createCommand(
            "CREATE TYPE $name AS {$this->formatEnumValues($this->fetchEnums($enums))}"
        );

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
