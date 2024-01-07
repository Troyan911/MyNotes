<?php

namespace Core\Traits;

use Enums\SQL;
use PDO;

trait Queryable
{
    protected static string|null $tableName = null;
    protected static string $query = '';
    private array $commands = [];

    /**
     * @param array $colums (e.g. ['name', 'surname'], ['users.name as u_ name']) => SELECT name, surname ...
     * @return static
     */
    public static function select(array $colums = ['*']): static
    {
        static::resetQuery();
        static::$query = "SELECT " . implode(', ', $colums) . " FROM " . static::$tableName . " ";

        $obj = new static;
        $obj->commands[] = 'select';

        return $obj;
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

        $query->closeCursor();

        return static::find(db()->lastInsertId());
    }


    public static function destroy(int $id): bool
    {
        $query = db()->prepare("DELETE FROM " . static::$tableName . " WHERE id = :id");
        $query->bindParam("id", $id);
        return $query->execute();
    }

    public function update(array $fields): static
    {
        $query = "UPDATE " . static::$tableName . " SET " . $this->updatePlaceholders(array_keys($fields)) . " WHERE id = :id";
        $query = db()->prepare($query);

        $fields['id'] = $this->id;
        $query->execute($fields);

        return static::find($this->id);
    }

    protected function updatePlaceholders(array $keys): string
    {
        $string = "";
        $lastKey = array_key_last($keys);

        foreach($keys as $index => $key) {
            $string .= "$key = :$key" . ($lastKey === $index ? "" : ", ");
        }

        return $string;
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

    protected static function resetQuery(): void
    {
        static::$query = '';
    }

    protected function where(string $column, string $operator, $value = null): static
    {
        if ($this->prevent(['group', 'limit', 'order', 'having'])) {
            throw new \Exception(
                static::class .
                ": WHERE can not be after ['group', 'limit', 'order', 'having']"
            );
        }

        $obj = in_array('select', $this->commands) ? $this : static::select();

        if (
            !is_null($value) &&
            !is_bool($value) &&
            !is_numeric($value) &&
            !is_array($value) &&
            !in_array($operator, [SQL::IN_OPERATOR->value, SQL::NOT_IN_OPERATOR->value]) &&
            $value !== SQL::NULL->value
        ) {
            $value = "'$value'";
        }

        if (is_null($value)) {
            $value = 'NULL';
        }

        if (is_array($value)) {
            $value = array_map(fn($item) => is_string($item) && $item !== SQL::NULL->value ? "'$item'" : $item, $value);
            $value = '('. implode(', ', $value) .')';
        }

        if (!in_array('where', $obj->commands)) {
            static::$query .= "WHERE";
        }

        static::$query .= " $column $operator $value";
        $this->commands[] = 'where';

        return $obj;
    }

    public function andWhere(string $column, string $operator, $value = null): static
    {
        static::$query .= " AND" . (in_array('startCondition', $this->commands) ? ' (' : '');
        return $this->where($column, $operator, $value);
    }

    public function orWhere(string $column, string $operator, $value = null): static
    {
        static::$query .= " OR";
        return $this->where($column, $operator, $value);
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

    public function orderBy(array $columns): static
    {
        if (!$this->prevent(['select'])) {
            throw new \Exception(
                static::class .
                ": [ORDER BY] can not be called before [SELECT]"
            );
        }

        $this->commands[] = 'order';
        $lastKey = array_key_last($columns);
        static::$query .= " ORDER BY ";

        foreach ($columns as $column => $order) {
            static::$query .= "$column $order->value" . ($column === $lastKey ? "" : ", ");
        }

        return $this;
    }

    public function sql(): string
    {
        return static::$query;
    }

    public function exists(): bool
    {
        if (!$this->prevent(['select'])) {
            throw new \Exception(
                static::class .
                ": exists can not be called before ['select']"
            );
        }

        return !empty($this->get());
    }

    public function get(): array
    {
        return db()->query(static::$query)->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public function startCondition(): static
    {
        $this->commands[] = 'startCondition';
        return $this;
    }

    public function endCondition(): static
    {
        $this->commands[] = 'endCondition';
        static::$query .= ') ';

        return $this;
    }
}