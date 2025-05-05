<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Enums\CartStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'total_sum' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement([CartStatus::Pending->value, CartStatus::Completed->value]),
            'completed_at' => $this->faker->optional()->dateTime(),
        ];
    }

    public function completed()
    {
        return $this->state([
            'status' => CartStatus::Completed->value,
            'completed_at' => now(),
        ]);
    }
    
    public function pending()
    {
        return $this->state([
            'status' => CartStatus::Pending->value,
            'completed_at' => null,
        ]);
    }
}