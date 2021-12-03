<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = ucwords($this->faker->words(mt_rand(3, 5), true));
        preg_match_all('/\b(\w)/', $name, $matches);
        $acronym = implode('', $matches[1]);
        
        return [
            'name' => $name,
            'short_name' => $acronym,
            'description' => $this->faker->paragraph(1),
        ];
    }
}
