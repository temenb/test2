<?php

namespace App\Services;

use App\Dto\TaskDto;
use App\Enums\TaskStatus;
use App\Entity\Task;
use App\Repositories\TaskRepositoryInterface;

class TaskService
{
    public function __construct(private TaskRepositoryInterface $repository) {}

    public function createTask(TaskDto $dto): Task
    {
        $task = new Task($dto->title, $dto->description, $dto->status, $dto->assigneeId);
        $this->repository->save($task);
        return $task;
    }

    public function getTasks(?TaskStatus $status = null, ?string $assigneeId = null): array
    {
        return array_filter($this->repository->all(), function (Task $task) use ($status, $assigneeId) {
            return (!$status || $task->status === $status)
                && (!$assigneeId || $task->assigneeId === $assigneeId);
        });
    }

    public function updateStatus(string $id, TaskStatus $status): ?Task
    {
        $task = $this->repository->find($id);
        if ($task) {
            $task->status = $status;
        }
        return $task;
    }

    public function assignTask(string $id, string $assigneeId): ?Task
    {
        $task = $this->repository->find($id);
        if ($task) {
            $task->assigneeId = $assigneeId;
        }
        return $task;
    }
}
