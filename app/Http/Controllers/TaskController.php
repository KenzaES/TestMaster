<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class TaskController extends Controller
{
    // Store a new task
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:normal,important,urgent',
            'progression' => 'required|string|in:waiting,in progress,done',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Add user_id to the validated data
        $validatedData['user_id'] = Auth::id();

        // Create task
        $task = Task::create($validatedData);

        return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
    }

    // Read a single task
    public function show($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task, 200);
    }

    // Read all tasks
    public function index()
    {
        // Fetch tasks for the authenticated user
        $tasks = Task::where('user_id', Auth::id())->get();
        return response()->json($tasks, 200);
    }

    // Update a task
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Ensure the user owns the task
        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|string|in:normal,important,urgent',
            'progression' => 'sometimes|string|in:waiting,in progress,done',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
        ]);

        // Update task with validated data
        $task->update($validatedData);

        return response()->json(['message' => 'Task updated successfully', 'task' => $task], 200);
    }

    // Delete a task
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Ensure the user owns the task
        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete task
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully'], 200);
    }
}
