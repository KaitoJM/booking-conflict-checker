<?php

use App\Models\Booking;
use App\Models\User;

use function Pest\Laravel\actingAs;

it("can detect conflict booking dates", function() {
    $user = User::factory()->create(['role' => 'user']);

    $booking1 = Booking::factory()->create([
        'date' => '2025-12-17',
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
    ]);

    $booking2 = Booking::factory()->create([
        'date' => '2025-12-17',
        'start_time' => '02:00:00',
        'end_time' => '03:00:00',
    ]);

    $response = actingAs($user)->getJson('/api/bookings-conflicts?date=2025-12-17&start_time=10:00:00&end_time=12:00:00');

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'id' => $booking1->id,
        'date' => $booking1->date,
        'start_time' => $booking1->start_time,
        'end_time' => $booking1->end_time,
    ]);
    $response->assertJsonFragment([
        'has_conflicts' => true
    ]);
});

it("can detect overlaps booking dates", function() {
    $user = User::factory()->create(['role' => 'user']);

    $booking1 = Booking::factory()->create([
        'date' => '2025-12-17',
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
    ]);

    $booking2 = Booking::factory()->create([
        'date' => '2025-12-17',
        'start_time' => '02:00:00',
        'end_time' => '03:00:00',
    ]);

    $response = actingAs($user)->getJson('/api/bookings-conflicts?date=2025-12-17&start_time=10:30:00&end_time=12:45:00');

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'id' => $booking1->id,
        'date' => $booking1->date,
        'start_time' => $booking1->start_time,
        'end_time' => $booking1->end_time,
    ]);
    $response->assertJsonFragment([
        'has_overlaps' => true
    ]);
});

it("can detect gaps booking dates", function() {
    $user = User::factory()->create(['role' => 'user']);

    $booking1 = Booking::factory()->create([
        'date' => '2025-12-17',
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
    ]);

    $booking2 = Booking::factory()->create([
        'date' => '2025-12-17',
        'start_time' => '14:00:00',
        'end_time' => '15:00:00',
    ]);

    $response = actingAs($user)->getJson('/api/bookings-conflicts?date=2025-12-17&start_time=12:30:00&end_time=13:45:00');

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'start_time' => $booking1->end_time,
        'end_time' => $booking2->start_time,
    ]);
    $response->assertJsonFragment([
        'total_gaps' => 1
    ]);
});
