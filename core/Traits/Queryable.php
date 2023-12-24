<?php

namespace Core\Traits;

use PDO;

trait Queryable
{
    protected static string|null $tableName = null;
    private static string $query = '';
    protected array $commands = [];

    /**
     * @param array $colums (e.g. ['name', 'surname'], ['users.name as u_ name']) => SELECT name, surname ...
     * @return static
     */
    public static function select(array $colums = ['*']): static
    {
        static::resetQuery();
        static::$query = "SELECT " . implode(',', $colums) . " FROM " . static::$tableName;

        $obj = new static;
        $obj->commands[] = 'select';

        return $obj;
    }

    protected static function resetQuery(): void
    {
        static::$query = '';
    }

    public function get(): array
    {
        return db()->query(static::$query)->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public static function all(): array
    {
        return static::select()->get();
    }

    public static function find(int $id): static|false
    {
        return static::findBy('id', $id);
    }

    public static function findBy(string $column, $value): static|false
    {
        $query = db()->prepare("SELECT * FROM " . static::$tableName . " WHERE $column = :value");
        $query->bindParam("value", $value);
        $query->execute();

        return $query->fetchObject(static::class);

    }

    public static function create(array $fields): false|int
    {
        $params = static::prepareQueryParams($fields);

//        dd($params);
        $query = db()->prepare("INSERT INTO " . static::$tableName . " ($params[keys]) VALUES ($params[placeholders])");

//        dd($query);


        if (!$query->execute($fields)) {
            return false;
        }

        return (int)db()->lastInsertId();
    }

    protected static function prepareQueryParams(array $fields): array
    {
        $keys = array_keys($fields);
        $placeholders = preg_filter('/^/', ':', $keys);

        return [
            'keys' => implode(', ', $keys),
            'placeholders' => implode(', ', $placeholders)
        ];

    }

    public static function destroy(int $id): bool
    {
        $query = db()->prepare("DELETE FROM " . static::$tableName . " WHERE id = :id");
        $query->bindParam("id", $id);
        return $query->execute();
    }

    //Note::select()->where('user_id', '=', 4)->ordery('desc');

}