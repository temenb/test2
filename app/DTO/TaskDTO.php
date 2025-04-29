<?php

namespace App\DTO;

use App\Enums\TaskStatus;

class TaskDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public TaskStatus $status,
        public ?string $assigneeId = null
    ) {}
}
