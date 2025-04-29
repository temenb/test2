<?php

namespace App\Entity;

use App\Enums\TaskStatus;
use Ramsey\Uuid\Uuid;

class Task
{
    public string $id;
    public string $title;
    public string $description;
    public TaskStatus $status;
    public ?string $assigneeId;
    public string $createdAt;

    public function __construct(string $title, string $description, TaskStatus $status, ?string $assigneeId = null)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->assigneeId = $assigneeId;
        $this->createdAt = now()->toDateTimeString();
    }
}

