<?php

namespace App\Command;

use App\Repository\TaskRepository;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'task:delete', description: 'Excluir uma tarefa')]
final class DeleteTaskCommand
{
    public function __construct(private readonly TaskRepository $taskRepository) {}

    public function __invoke(
        SymfonyStyle $io,

        #[Argument(description: "ID da tarefa", name: "id")]
        int $id,
    ) {
        if ($this->taskRepository->findById($id) === null) {
            $io->error(sprintf("A tarefa com ID %d não foi encontrada.", $id));
            return Command::INVALID;
        }

        $result = $this->taskRepository->delete($id);

        if (!$result) {
            $io->error(sprintf("Não foi possível excluir a tarefa com ID %d.", $id));
            return Command::INVALID;
        }

        $io->success(sprintf("A tarefa com ID %d foi excluida com sucesso.", $id));

        return Command::SUCCESS;
    }
}
