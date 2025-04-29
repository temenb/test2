<?php

namespace App\Repositories;

use App\Entity\Task;

class InMemoryTaskRepository implements TaskRepositoryInterface
{
    private array $tasks = [];

    public function save(Task $task): void
    {
        $this->tasks[$task->id] = $task;
    }

    public function find(string $id): ?Task
    {
        return $this->tasks[$id] ?? null;
    }

    public function all(array $filters = []): array
    {
        $result = $this->tasks;
        if (isset($filters['status'])) {
            $result = array_filter($result, fn($task) => $task->status === $filters['status']);
        }
        if (isset($filters['assigneeId'])) {
            $result = array_filter($result, fn($task) => $task->assigneeId === $filters['assigneeId']);
        }
        return array_values($result);
    }
}
