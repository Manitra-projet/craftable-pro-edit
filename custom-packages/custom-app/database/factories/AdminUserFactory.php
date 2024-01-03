<?php

namespace CustomPackages\CustomApp\Database\Factories;

use CustomPackages\CustomApp\Models\AdminUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class CustomAppUserFactory extends Factory
{
    protected $model = AdminUser::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'password' => Hash::make($this->faker->password),
            'email_verified_at' => $this->faker->dateTime,
            'remember_token' => $this->faker->md5,
            'locale' => $this->faker->randomElement(['en', 'de', 'fr']),
        ];
    }
}
