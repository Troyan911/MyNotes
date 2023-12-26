<?php

namespace Core\Traits;

use PDO;
use PDOStatement;

trait Queryable
{
    protected static string|null $tableName = null;
    private static string $queryString = '';

//    private PDOStatement|null $query = null;
    protected array $commands = [];

    /**
     * @param array $colums (e.g. ['name', 'surname'], ['users.name as u_ name']) => SELECT name, surname ...
     * @return static
     */
    public static function select(array $colums = ['*']): static
    {
        static::resetQuery();
        static::$queryString = "SELECT " . implode(',', $colums) . " FROM " . static::$tableName;

        $obj = new static;
        $obj->commands[] = 'select';

        return $obj;
    }

    protected static function resetQuery(): void
    {
        static::$queryString = '';
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

    public static function create(array $fields): null|static
    {
        $params = static::prepareQueryParams($fields);

        $query = db()->prepare("INSERT INTO " . static::$tableName . " ($params[keys]) VALUES ($params[placeholders])");

        if (!$query->execute($fields)) {
            return null;
        }

        return static::find(db()->lastInsertId());
    }


    public static function destroy(int $id): bool
    {
        $query = db()->prepare("DELETE FROM " . static::$tableName . " WHERE id = :id");
        $query->bindParam("id", $id);
        return $query->execute();
    }

    public function get(): array
    {
        return db()->query(static::$queryString)->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    protected function where(string $column, string $operator, $value = null): static
    {
        if ($this->prevent(['group', 'limit', 'order', 'having'])) {
            throw new \Exception(
                static::class
                . ": WHERE can not be after ['group', 'limit', 'order', 'having']"
            );
        }

        $obj = in_array('select', $this->commands) ? $this : static::select();

        if (
            !is_null($value) &&
            !is_numeric($value) &&
            !is_bool($value) &&
            !is_array($value) &&
            !in_array($operator, ['IN', 'NOT IN'])
        ) {
            $value = "'$value'";
        }

        if (is_null($value)) {
            $value = 'NULL';
        }

        if (is_array($value)) {
            $value = array_map(fn($item) => is_string($item) ? "'$item'" : $item, $value);
//            $value = preg_filter("/([\D]+)/i", '$1', $value);
            $value = '(' . implode(', ', $value) . ')';
        }

        if (!in_array('where', $obj->commands)) {
            static::$queryString .= " WHERE";
        }

        static::$queryString .= " $column $operator $value";
        $this->commands[] = 'where';

        return $obj;
    }

    protected function prevent(array $allowedMethods): bool
    {
        foreach ($allowedMethods as $method) {
            if (in_array($method, $this->commands)) {
                return true;
            }
        }
        return false;
    }


    public static function __callStatic(string $name, array $args): mixed
    {
        if (in_array($name, ['where'])) {
            $obj = static::select();
            return call_user_func_array([$obj, $name], $args);
        }
    }

    public function __call(string $name, array $args): mixed
    {
        if (in_array($name, ['where'])) {
            return call_user_func_array([$this, $name], $args);
        }
    }

    public function sql(): string
    {
        return static::$queryString;
    }

    //Note::select()->where('user_id', '=', 4)->ordery('desc');

}