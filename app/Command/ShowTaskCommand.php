<?php

namespace App\Command;

use App\Repository\TaskRepository;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'task:show', description: 'Exibir detalhes de uma tarefa')]
final class ShowTaskCommand
{
    public function __construct(private readonly TaskRepository $taskRepository) {}

    public function __invoke(
        SymfonyStyle $io,

        #[Argument(description: "ID da tarefa", name: "id")]
        int $id,
    ) {
        $task = $this->taskRepository->findById($id);

        if ($task === null) {
            $io->error(sprintf("A tarefa com ID %d não foi encontrada.", $id));
            return Command::INVALID;
        }

        $io->table(
            ["ID", "Título", "Status", "Criada Em"],
            [[
                $task->id,
                $task->title,
                $task->status->value,
                $task->created_at->setTimezone("America/Sao_Paulo")->format("d/m/Y H:i"),
            ]]
        );

        return Command::SUCCESS;
    }
}
