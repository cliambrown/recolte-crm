<?php

namespace Database\Factories;

use App\Models\Org;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrgFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Org::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'created_by_user_id' => 1,
            'name' => $this->faker->company(),
            'street_address' => $this->faker->streetAddress(),
            'street_address_2' => (mt_rand(0, 10) > 8 ? $this->faker->secondaryAddress() : null),
            'city' => $this->faker->city(),
            'province' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'po_box' => (mt_rand(0, 10) > 8 ? 'PO Box '.mt_rand(11111, 99999) : null),
            'website' => $this->faker->url(),
            'phone' => $this->faker->e164PhoneNumber(),
            'email' => $this->faker->safeEmail(),
        ];
    }
}
