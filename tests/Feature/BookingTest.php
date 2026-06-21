<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function agentUser(): User
    {
        return User::factory()->create(['role' => 'agent']);
    }

    public function test_agent_can_create_booking(): void
    {
        $user = $this->agentUser();

        $response = $this->actingAs($user)->postJson('/api/bookings', [
            'customer_name' => 'Ali Rezaei',
            'customer_email' => 'ali@test.com',
            'flight_number' => 'IR455',
            'origin' => 'THR',
            'destination' => 'DXB',
            'travel_date' => '2026-08-01',
            'departure_time' => '08:30',
            'arrival_time' => '11:00',
            'seat_class' => 'economy',
            'total_price' => 2500000,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.flight_number', 'IR455')
            ->assertJsonPath('data.status', 'pending');
    }

    public function test_agent_cannot_see_other_agents_bookings(): void
    {
        $agent1 = $this->agentUser();
        $agent2 = $this->agentUser();

        Booking::factory()->create(['user_id' => $agent2->id]);

        $response = $this->actingAs($agent1)->getJson('/api/bookings');

        
        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_cannot_cancel_already_cancelled_booking(): void
    {
        $user = $this->agentUser();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => 'cancelled',
        ]);

        $response = $this->actingAs($user)->postJson("/api/bookings/{$booking->id}/cancel");

        $response->assertStatus(422);
    }
}