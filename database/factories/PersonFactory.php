<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Person::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $phoneObj = get_valid_phone_obj($this->faker->e164PhoneNumber());
        $phone = get_readable_phone($phoneObj);
        
        $pronounList = ['he/him', 'she/her', 'they/them'];
        $pronouns = $pronounList[array_rand($pronounList)];
        
        return [
            'created_by_user_id' => 1,
            'given_name' => $this->faker->firstName(),
            'family_name' => $this->faker->lastName(),
            'pronouns' => $pronouns,
            'street_address' => $this->faker->streetAddress(),
            'street_address_2' => (mt_rand(0, 10) > 8 ? $this->faker->secondaryAddress() : null),
            'city' => $this->faker->city(),
            'province' => $this->faker->state(),
            'country' => $this->faker->country(),
            'postal_code' => $this->faker->postcode(),
            'website' => $this->faker->url(),
            'phone' => $phone,
            'email' => $this->faker->safeEmail(),
        ];
    }
}
