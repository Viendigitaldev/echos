<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Connection;
use PDO;

abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';

    /** @return array<int, array<string, mixed>> */
    public static function all(string $orderBy = ''): array
    {
        $sql = 'SELECT * FROM ' . static::$table;
        if ($orderBy !== '') {
            $sql .= ' ORDER BY ' . $orderBy;
        }

        return Connection::get()->query($sql)->fetchAll();
    }

    /** @return array<string, mixed>|null */
    public static function find(int $id): ?array
    {
        $stmt = Connection::get()->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @param array<string, mixed> $conditions
     * @return array<string, mixed>|null
     */
    public static function findWhere(array $conditions): ?array
    {
        $rows = static::allWhere($conditions);

        return $rows[0] ?? null;
    }

    /**
     * @param array<string, mixed> $conditions
     * @return array<int, array<string, mixed>>
     */
    public static function allWhere(array $conditions, string $orderBy = ''): array
    {
        $clauses = [];
        foreach (array_keys($conditions) as $column) {
            $clauses[] = "{$column} = :{$column}";
        }

        $sql = 'SELECT * FROM ' . static::$table;
        if ($clauses !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
        }
        if ($orderBy !== '') {
            $sql .= ' ORDER BY ' . $orderBy;
        }

        $stmt = Connection::get()->prepare($sql);
        $stmt->execute($conditions);

        return $stmt->fetchAll();
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function insert(array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_map(static fn (string $c) => ':' . $c, $columns);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            static::$table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $stmt = Connection::get()->prepare($sql);
        $stmt->execute($data);

        return (int) Connection::get()->lastInsertId();
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function update(int $id, array $data): void
    {
        $assignments = [];
        foreach (array_keys($data) as $column) {
            $assignments[] = "{$column} = :{$column}";
        }

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s = :id',
            static::$table,
            implode(', ', $assignments),
            static::$primaryKey
        );

        $stmt = Connection::get()->prepare($sql);
        $stmt->execute([...$data, 'id' => $id]);
    }

    public static function delete(int $id): void
    {
        $stmt = Connection::get()->prepare('DELETE FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function count(string $where = '', array $params = []): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . static::$table;
        if ($where !== '') {
            $sql .= ' WHERE ' . $where;
        }

        $stmt = Connection::get()->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }
}
