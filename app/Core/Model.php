<?php
namespace App\Core;

abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [];
    protected static array $hidden = ['password'];

    protected static function db(): Database
    {
        return Database::getInstance();
    }

    public static function all(array $columns = ['*']): array
    {
        $cols = implode(', ', $columns);
        $sql = "SELECT {$cols} FROM " . static::$table . " ORDER BY " . static::$primaryKey . " DESC";
        return static::hideFields(static::db()->fetchAll($sql));
    }

    public static function find(int $id): ?array
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";
        $result = static::db()->fetch($sql, [$id]);
        return $result ? static::hideField($result) : null;
    }

    public static function findBy(string $column, $value): ?array
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE {$column} = ?";
        $result = static::db()->fetch($sql, [$value]);
        return $result ? static::hideField($result) : null;
    }

    public static function where(string $column, $value, string $operator = '='): array
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE {$column} {$operator} ?";
        return static::hideFields(static::db()->fetchAll($sql, [$value]));
    }

    public static function whereMultiple(array $conditions): array
    {
        $whereClauses = [];
        $params = [];

        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $whereClauses[] = "{$column} {$value[0]} ?";
                $params[] = $value[1];
            } else {
                $whereClauses[] = "{$column} = ?";
                $params[] = $value;
            }
        }

        $sql = "SELECT * FROM " . static::$table . " WHERE " . implode(' AND ', $whereClauses);
        return static::hideFields(static::db()->fetchAll($sql, $params));
    }

    public static function create(array $data): int
    {
        $filtered = static::filterFillable($data);
        return static::db()->insert(static::$table, $filtered);
    }

    public static function update(int $id, array $data): int
    {
        $filtered = static::filterFillable($data);
        return static::db()->update(static::$table, $filtered, static::$primaryKey . " = ?", [$id]);
    }

    public static function delete(int $id): int
    {
        return static::db()->delete(static::$table, static::$primaryKey . " = ?", [$id]);
    }

    public static function count(string $where = '1=1', array $params = []): int
    {
        return static::db()->count(static::$table, $where, $params);
    }

    public static function paginate(int $page = 1, int $perPage = 15, string $where = '1=1', array $params = []): array
    {
        $offset = ($page - 1) * $perPage;
        $total = static::count($where, $params);
        $totalPages = ceil($total / $perPage);

        $sql = "SELECT * FROM " . static::$table . " WHERE {$where} ORDER BY " . static::$primaryKey . " DESC LIMIT {$perPage} OFFSET {$offset}";
        $data = static::hideFields(static::db()->fetchAll($sql, $params));

        return [
            'data' => $data,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'has_more' => $page < $totalPages
        ];
    }

    public static function raw(string $sql, array $params = []): array
    {
        return static::db()->fetchAll($sql, $params);
    }

    public static function rawOne(string $sql, array $params = []): ?array
    {
        return static::db()->fetch($sql, $params);
    }

    protected static function filterFillable(array $data): array
    {
        if (empty(static::$fillable)) {
            return $data;
        }

        return array_intersect_key($data, array_flip(static::$fillable));
    }

    protected static function hideFields(array $rows): array
    {
        return array_map(function ($row) {
            return static::hideField($row);
        }, $rows);
    }

    protected static function hideField(array $row): array
    {
        foreach (static::$hidden as $field) {
            unset($row[$field]);
        }
        return $row;
    }

    public static function exists(int $id): bool
    {
        return static::find($id) !== null;
    }

    public static function firstOrCreate(array $attributes, array $values = []): array
    {
        $conditions = [];
        $params = [];
        
        foreach ($attributes as $key => $value) {
            $conditions[] = "{$key} = ?";
            $params[] = $value;
        }
        
        $sql = "SELECT * FROM " . static::$table . " WHERE " . implode(' AND ', $conditions);
        $existing = static::db()->fetch($sql, $params);
        
        if ($existing) {
            return static::hideField($existing);
        }
        
        $data = array_merge($attributes, $values);
        $id = static::create($data);
        
        return static::find($id);
    }
}
