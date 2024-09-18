<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['normal', 'important', 'urgent']),
            'progression' => $this->faker->randomElement(['waiting', 'in progress', 'done']),
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
