<?php

namespace Tests\Unit;

use App\DTO\TaskDTO;
use App\Enums\TaskStatus;
use App\Repositories\InMemoryTaskRepository;
use App\Services\TaskService;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    public function test_create_task()
    {
        $repository = new InMemoryTaskRepository();
        $service = new TaskService($repository);

        $task = $service->createTask(new TaskDTO(
            'Test Task',
            'Some description',
            TaskStatus::TODO
        ));

        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals(TaskStatus::TODO, $task->status);
    }
}
