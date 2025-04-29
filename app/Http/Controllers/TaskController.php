// app/Http/Controllers/TaskController.php
<?php

namespace App\Http\Controllers;

use App\Dto\TaskDto;
use App\Enums\TaskStatus;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TaskController extends Controller
{
    public function __construct(private TaskService $service) {}

    public function create(Request $request)
    {
        $dto = new TaskDto(
            $request->input('title'),
            $request->input('description'),
            TaskStatus::from($request->input('status')),
            $request->input('assigneeId')
        );
        return response()->json($this->service->createTask($dto));
    }

    public function list(Request $request)
    {
        $status = $request->input('status') ? TaskStatus::from($request->input('status')) : null;
        $assigneeId = $request->input('assigneeId');
        return response()->json($this->service->getTasks($status, $assigneeId));
    }

    public function updateStatus(Request $request, string $id)
    {
        $status = TaskStatus::from($request->input('status'));
        $task = $this->service->updateStatus($id, $status);
        return response()->json($task);
    }

    public function assign(Request $request, string $id)
    {
        $assigneeId = $request->input('assigneeId');
        $task = $this->service->assignTask($id, $assigneeId);
        return response()->json($task);
    }
}
