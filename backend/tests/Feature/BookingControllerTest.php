<?php

use App\Models\Booking;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe("Get List of Bookings", function() {
    it("retrieves a list of bookings if the authenticated user is admin", function() {
        $user = User::factory()->create(['role' => 'admin']);
        Booking::factory(10)->create();

        $response = actingAs($user)->getJson('/api/bookings');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    });

    it("retrieves a list of bookings of the authenticated user if role is user", function() {
        $user = User::factory()->create(['role' => 'user']);
        $booking1 = Booking::factory()->create(['user_id' => $user->id, 'date' => '2025-12-17']);
        $booking2 = Booking::factory()->create(['user_id' => $user->id, 'date' => '2025-12-18']);
        $booking3 = Booking::factory()->create(['user_id' => $user->id, 'date' => '2025-12-19']);
        Booking::factory(10)->create();

        $response = actingAs($user)->getJson('/api/bookings');

        expect(Booking::count(), 20);
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonFragments([
            ['user_id' => $user->id, 'date' => '2025-12-17'],
            ['user_id' => $user->id, 'date' => '2025-12-18'],
            ['user_id' => $user->id, 'date' => '2025-12-19'],
        ]);
    });
});

describe('Store booking', function() {
    it("stores a new booking of the autheticated user", function() {
        $user = User::factory()->create(['role' => 'user']);

        $response = actingAs($user)->postJson('/api/bookings', [
            'description' => 'test-description',
            'date' => '2025-12-17',
            'start_time' => '01:00:00',
            'end_time' => '02:00:00',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'user_id' => $user->id,
            'description' => 'test-description',
            'date' => '2025-12-17',
            'start_time' => '01:00:00',
            'end_time' => '02:00:00',
        ]);
    });

    it("returns a 422 error when passing an invalid date", function() {
        $user = User::factory()->create(['role' => 'user']);

        $response = actingAs($user)->postJson('/api/bookings', [
            'date' => 'some-invalid-date',
            'start_time' => '01:00',
            'end_time' => '02:00',
        ]);

        $response->assertStatus(422);
    });

    it("returns a 422 error when passing an invalid time", function() {
        $user = User::factory()->create(['role' => 'user']);

        $response = actingAs($user)->postJson('/api/bookings', [
            'date' => '2025-12-17',
            'start_time' => 'invalid-time',
            'end_time' => 'invalid-time',
        ]);

        $response->assertStatus(422);
    });
});

describe("View Booking", function() {
    it("view a booking if the authenticated user owns it", function() {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'date' => '2025-12-17'
        ]);

        $response = actingAs($user)->getJson('/api/bookings/' . $booking->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'user_id' => $user->id,
            'date' => '2025-12-17'
        ]);
    });

    it("returns a 403 error if the authenticated user doesnt owns the booking", function() {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'date' => '2025-12-17'
        ]);

        $response = actingAs($user)->getJson('/api/bookings/' . $booking->id);

        $response->assertStatus(403);
    });
});

describe("Udpate Booking", function() {
    it("updates a booking if the authenticated user owns it", function() {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'date' => '2025-12-17'
        ]);

        $response = actingAs($user)->patchJson('/api/bookings/' . $booking->id, [
            'date' => '2025-12-18'
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'user_id' => $user->id,
            'date' => '2025-12-18'
        ]);
    });

    it("returns a 403 error if the authenticated user doesnt owns the booking", function() {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'date' => '2025-12-17'
        ]);

        $response = actingAs($user)->patchJson('/api/bookings/' . $booking->id, [
            'date' => '2025-12-18'
        ]);

        $response->assertStatus(403);
    });
});

describe("Delete Booking", function() {
    it("deletes a booking if the authenticated user owns it", function() {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'date' => '2025-12-17'
        ]);

        $response = actingAs($user)->deleteJson('/api/bookings/' . $booking->id);

        $response->assertStatus(204);
    });

    it("returns a 403 error if the authenticated user doesnt owns the booking", function() {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'date' => '2025-12-17'
        ]);

        $response = actingAs($user)->deleteJson('/api/bookings/' . $booking->id);

        $response->assertStatus(403);
    });
});
