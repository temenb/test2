<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_task()
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'New Task',
            'description' => 'Task description',
            'status' => 'todo'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'title', 'description', 'status', 'assigneeId', 'createdAt'
            ]);
    }

    public function test_it_can_list_tasks()
    {
        // Создать несколько задач
        $this->postJson('/api/tasks', [
            'title' => 'Task 1',
            'description' => 'Desc 1',
            'status' => 'todo'
        ]);

        $this->postJson('/api/tasks', [
            'title' => 'Task 2',
            'description' => 'Desc 2',
            'status' => 'done'
        ]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'title', 'description', 'status', 'assigneeId', 'createdAt']
            ]);
    }

    public function test_it_can_update_task_status()
    {
        $createResponse = $this->postJson('/api/tasks', [
            'title' => 'Task to update',
            'description' => 'Desc',
            'status' => 'todo'
        ]);

        $taskId = $createResponse->json('id');

        $updateResponse = $this->patchJson("/api/tasks/{$taskId}/status", [
            'status' => 'in_progress'
        ]);

        $updateResponse->assertStatus(200)
            ->assertJsonFragment(['status' => 'in_progress']);
    }

    public function test_it_can_assign_a_task_to_a_user()
    {
        $createResponse = $this->postJson('/api/tasks', [
            'title' => 'Task to assign',
            'description' => 'Desc',
            'status' => 'todo'
        ]);

        $taskId = $createResponse->json('id');
        $assigneeId = Str::uuid()->toString();

        $assignResponse = $this->patchJson("/api/tasks/{$taskId}/assign", [
            'assigneeId' => $assigneeId
        ]);

        $assignResponse->assertStatus(200)
            ->assertJsonFragment(['assigneeId' => $assigneeId]);
    }

    public function test_it_can_filter_tasks_by_status()
    {
        $this->postJson('/api/tasks', [
            'title' => 'Task 1',
            'description' => 'Desc 1',
            'status' => 'todo'
        ]);

        $this->postJson('/api/tasks', [
            'title' => 'Task 2',
            'description' => 'Desc 2',
            'status' => 'done'
        ]);

        $response = $this->getJson('/api/tasks?status=done');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['status' => 'done']);
    }

    public function test_it_can_filter_tasks_by_assignee()
    {
        $assigneeId = Str::uuid()->toString();

        $this->postJson('/api/tasks', [
            'title' => 'Task 1',
            'description' => 'Desc 1',
            'status' => 'todo',
            'assigneeId' => $assigneeId
        ]);

        $this->postJson('/api/tasks', [
            'title' => 'Task 2',
            'description' => 'Desc 2',
            'status' => 'todo'
        ]);

        $response = $this->getJson('/api/tasks?assigneeId=' . $assigneeId);

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['assigneeId' => $assigneeId]);
    }

    public function test_it_can_filter_tasks_by_status_and_assignee()
    {
        $assigneeId = Str::uuid()->toString();

        $this->postJson('/api/tasks', [
            'title' => 'Task 1',
            'description' => 'Desc 1',
            'status' => 'in_progress',
            'assigneeId' => $assigneeId
        ]);

        $this->postJson('/api/tasks', [
            'title' => 'Task 2',
            'description' => 'Desc 2',
            'status' => 'todo',
            'assigneeId' => $assigneeId
        ]);

        $response = $this->getJson('/api/tasks?status=in_progress&assigneeId=' . $assigneeId);

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'status' => 'in_progress',
                'assigneeId' => $assigneeId
            ]);
    }
}
