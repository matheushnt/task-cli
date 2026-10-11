<?php

namespace App\Entity;

use App\Enum\TaskStatus;
use Carbon\CarbonImmutable;

class Task
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly TaskStatus $status,
        public readonly CarbonImmutable $created_at,
    ) {}

    public static function fromRow(array $row): self
    {
        return new self(
            id: $row["id"],
            title: $row["title"],
            status: TaskStatus::from(strtolower($row["status"])),
            created_at: CarbonImmutable::parse($row["created_at"])
        );
    }
}
