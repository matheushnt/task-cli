<?php

namespace App\Command;

use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'task:list', description: 'Lista as tarefas')]
final class ListTasksCommand
{
    public function __construct(private readonly TaskRepository $taskRepository) {}

    public function __invoke(
        SymfonyStyle $io,

        #[Option(description: "Filtra por status: pending ou completed", shortcut: "s")]
        ?string $status = null,
    ) {
        $filter = null;

        if ($status !== null) {
            $filter = TaskStatus::tryFrom(strtolower($status));

            if ($filter === null) {
                $io->error("Status inválido \"$status\". Use: pending ou completed.");
                return Command::INVALID;
            }
        }

        $tasks = $this->taskRepository->findAll($filter);

        if ($tasks === []) {
            $io->info("Nenhuma tarefa encontrada.");
            return Command::SUCCESS;
        }

        $io->table(
            ["ID", "Título", "Status", "Criada Em"],
            array_map(fn($task): array => [
                $task->id,
                $task->title,
                $task->status->value,
                $task->created_at->setTimezone("America/Sao_Paulo")->format("d/m/Y H:i")
            ], $tasks)
        );

        return Command::SUCCESS;
    }
}
