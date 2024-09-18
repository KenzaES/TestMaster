<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    //user variable

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(); 
    }

    public function test_add_task()
    {
        // Create a user and act as that user
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum'); // Ensure sanctum is used
    
        // Data for the new task
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test description',
            'status' => 'normal',
            'progression' => 'waiting',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
        ];
    
        // Perform the request
        $response = $this->post('/api/add/tasks', $taskData);
    
        // Assert the response status
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'task' => [
                         'id', 'title', 'description', 'status', 'progression', 'start_date', 'end_date', 'user_id'
                     ]
                 ]);
    }
    


    public function test_get_tasks(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('api/tasks');

        $response->assertStatus(200)
                 ->assertJson([$task->toArray()]);
    }

    public function test_get_task(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('api/tasks/' . $task->id);

        $response->assertStatus(200)
                 ->assertJson($task->toArray());
    }

    public function test_update_task(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $updatedData = [
            'title' => 'Updated Task Title',
            'description' => 'Updated description',
            'status' => 'important',
            'progression' => 'in progress',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
        ];

        $response = $this->actingAs($this->user)->put('api/edit/tasks/' . $task->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Task updated successfully',
                     'task' => $updatedData
                 ]);

        $this->assertDatabaseHas('tasks', $updatedData);
    }

    public function test_delete_task(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->delete('api/tasks/' . $task->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Task deleted successfully']);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
