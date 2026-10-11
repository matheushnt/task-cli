<?php

namespace App\Command;

use App\Repository\TaskRepository;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'task:add', description: 'Criar uma nova tarefa')]
final class AddTaskCommand
{
    private const int MAX_TITLE_LENGTH = 150;

    public function __construct(private readonly TaskRepository $taskRepository) {}

    public function __invoke(
        SymfonyStyle $io,
        #[Argument(description: "Título da tarefa", name: "title")]
        string $title,
    ) {
        $title = trim($title);

        if (empty($title)) {
            $io->error("O título não pode ser vazio.");
            return Command::INVALID;
        }

        if (mb_strlen($title) > self::MAX_TITLE_LENGTH) {
            $io->error(sprintf("O título deve ter no máximo %d caracteres.", self::MAX_TITLE_LENGTH));
            return Command::INVALID;
        }

        $task = $this->taskRepository->create($title);
        $io->success(sprintf('Tarefa #%d criada: %s', $task->id, $task->title));

        return Command::SUCCESS;
    }
}
