<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->email(),
            'flight_number' => 'IR' . $this->faker->numberBetween(100, 999),
            'origin' => 'THR',
            'destination' => 'DXB',
            'travel_date' => $this->faker->dateTimeBetween('+1 week', '+3 months')->format('Y-m-d'),
            'departure_time' => '08:30',
            'arrival_time' => '11:00',
            'seat_class' => 'economy',
            'total_price' => $this->faker->numberBetween(1000000, 5000000),
            'status' => 'pending',
        ];
    }
}
