<?php

abstract class Model {
    protected PDO $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findBy(string $column, mixed $value): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1");
        $stmt->execute([$value]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function all(string $orderBy = 'id', string $dir = 'ASC'): array {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy} {$dir}");
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $cols = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$cols}) VALUES ({$placeholders})");
        $stmt->execute(array_values($data));
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $set = implode(',', array_map(fn($col) => "{$col}=?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey}=?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey}=?");
        return $stmt->execute([$id]);
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }

    public function paginate(int $page, int $perPage, string $where = '', array $params = []): array {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM {$this->table}";
        if ($where) $sql .= " WHERE {$where}";
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
