<?php

namespace App\Repository;

use App\Database;
use App\Entity\Task;
use App\Enum\TaskStatus;
use RuntimeException;

final class TaskRepository
{
    public function __construct(private readonly Database $db) {}

    public function findById(int $id): ?Task
    {
        $sql = "SELECT * FROM tasks t WHERE t.id = :id";
        $row = $this->db->query($sql, ["id" => $id])->fetch();

        if ($row === false) {
            return null;
        }

        return Task::fromRow($row);
    }

    public function findAll(?TaskStatus $status = null): array
    {
        $sql = " SELECT * FROM tasks t";

        if ($status !== null) {
            $sql .= " WHERE t.status = :status";
            $rows = $this->db->query($sql, ["status" => $status->value])->fetchAll();
        } else {
            $rows = $this->db->query($sql)->fetchAll();
        }

        return array_map(fn(array $row): Task => Task::fromRow($row), $rows);
    }

    public function create(string $title): Task
    {
        $sql = "INSERT INTO tasks (title) VALUES (:title) RETURNING id, title, status, created_at";
        $row = $this->db->query($sql, ["title" => $title])->fetch();

        if ($row === false) {
            throw new RuntimeException("A tarefa criada não foi retornada pelo banco de dados.");
        }

        return Task::fromRow($row);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM tasks WHERE id = :id";
        $stmt = $this->db->query($sql, ["id" => $id]);

        return $stmt->rowCount() > 0;
    }
}
