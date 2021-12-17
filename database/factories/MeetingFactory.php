<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MeetingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $dt = $this->faker->dateTime();
        $hasTime = mt_rand(0, 1);
        
        return [
            'created_by_user_id' => 1,
            'name' => ucfirst($this->faker->words(3, true)),
            'description' => $this->faker->paragraph(1),
            'venue' => $this->faker->words(1, true),
            'type' => mt_rand(0, 2),
            'occurred_on' => $dt,
            'occurred_on_datetime' => ($hasTime ? $dt : null),
        ];
    }
}
