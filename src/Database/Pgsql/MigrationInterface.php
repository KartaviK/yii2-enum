<?php
/**
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 * @license MIT
 * @see https://github.com/myclabs/php-enum PHP enum implementation
 * @version 1.0
 */

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
