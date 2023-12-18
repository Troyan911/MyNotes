<?php

namespace Core\Traits;

use PDO;

trait Queryable
{
    protected static string|null $tableName = null;
    private static string $query= '';
    protected array $commands = [];

    /**
     * @param array $colums (e.g. ['name', 'surname'], ['users.name as u_ name']) => SELECT name, surname ...
     * @return static
     */
    public static function select(array $colums = ['*']) : static {
        static::resetQuery();
        static::$query = "SELECT " . implode(',', $colums) . " FROM " . static::$tableName;

        $obj = new static;
        $obj->commands[] = 'select';

        return $obj;
    }
    protected static function resetQuery() {
        static::$query = '';
    }

    public function get() {
        return db()->query(static::$query)->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    //Note::select()->where('user_id', '=', 4)->ordery('desc');

}