<?php

namespace App\Repository;

use App\Database;
use App\Entity\Task;
use App\Enum\TaskStatus;

final class TaskRepository
{
    public function __construct(private readonly Database $db) {}


    public function findAll(?TaskStatus $status = null): array
    {
        $sql = " SELECT * FROM tasks t";

        if ($status !== null) {
            $sql .= " WHERE t.status = :status";
            return $this->db->query($sql, Task::class, ["status" => $status->value])->fetchAll();
        } else {
            return $this->db->query($sql, Task::class)->fetchAll();
        }
    }
}
