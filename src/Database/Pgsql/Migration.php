<?php
/**
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 * @license MIT
 * @see https://github.com/myclabs/php-enum PHP enum implementation
 * @version 1.0
 */


namespace Kartavik\Yii2\Database\Pgsql;

use yii\db;

/**
 * Class Migration
 * @package Kartavik\Yii2\Database\Pgsql
 */
class Migration extends db\Migration implements MigrationInterface
{
    use MigrationTrait;
}
