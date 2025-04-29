<?php

namespace App\Repositories;

use App\Entity\Task;

interface TaskRepositoryInterface
{
    public function save(Task $task): void;
    public function find(string $id): ?Task;
    public function all(array $filters = []): array;
}
