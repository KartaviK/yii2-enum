<?php

namespace Kartavik\Yii2\Database\Pgsql;

/**
 * Interface MigrationInterface
 * @package Kartavik\Yii2\Database\Pgsql
 */
interface MigrationInterface
{
    public function addEnum(string $name, $enums): void;

    public function dropEnum(string $name): void;
}
