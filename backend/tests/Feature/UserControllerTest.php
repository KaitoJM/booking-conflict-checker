<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

describe("Get Users", function() {
    it("retrieve all list or users if user is authenticated", function() {
        $user = User::factory()->create(['role' => 'admin']);
        User::factory(10)->create();

        $response = actingAs($user)->getJson('/api/users');

        $response->assertStatus(200);
        $response->assertJsonCount(11, 'data');
    });

    it('retrieve users that matches the search key', function() {
        $user = User::factory()->create(['role' => 'admin']);
        $searched_user = User::factory()->create(['name' => 'Juan Dela Cruz', 'email' => 'juandelacruz@example.com']);
        User::factory(10)->create();

        $response = actingAs($user)->getJson('/api/users?search=Juan Dela Cruz');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    });
});

describe("Create User", function() {
    it("successfully register a new user when all fields are good", function() {
        $params = [
            'name' => 'Juan Dela Cruz',
            'email' => 'juandelacruz@example.com',
            'password' => 'password'
        ];

        $response = postJson('/api/register', $params);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'Juan Dela Cruz',
            'email' => 'juandelacruz@example.com',
            'role' => 'user'
        ]);
    });

    it('returns am error 422 if request has no name', function() {
        $params = [
            'email' => 'juandelacruz@example.com',
            'password' => 'password'
        ];

        $response = postJson('/api/register', $params);

        $response->assertStatus(422);
    });

    it('returns am error 422 if request has no email', function() {
        $params = [
            'name' => 'Juan Dela Cruz',
            'password' => 'password'
        ];

        $response = postJson('/api/register', $params);

        $response->assertStatus(422);
    });

    it('returns am error 422 if request has no password', function() {
        $params = [
            'name' => 'Juan Dela Cruz',
            'email' => 'juandelacruz@example.com',
        ];

        $response = postJson('/api/register', $params);

        $response->assertStatus(422);
    });
});

describe("Show User Data", function() {
    it("successfully retrieve user data when providing a good user ID", function() {
        $user = User::factory()->create(['role' => 'admin']);
        $user_details = User::factory()->create([
            'name' => 'Juan Dela Cruz',
            'email' => 'juandelacruz@example.com',
            'role' => 'user',
        ]);

        $response = actingAs($user)->getJson('/api/users/' . $user_details->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Juan Dela Cruz',
            'email' => 'juandelacruz@example.com',
            'role' => 'user',
        ]);
    });

    it("returns an error 404 if the specified id is not in the record", function() {
        $user = User::factory()->create(['role' => 'admin']);

        $response = actingAs($user)->getJson('/api/users/999999');

        $response->assertStatus(404);
    });
});

describe("Update User", function() {
    it("updates user data", function() {
        $user = User::factory()->create(['role' => 'admin']);
        $user_details = User::factory()->create([
            'name' => 'Juan Dela Cruz',
            'email' => 'juandelacruz@example.com',
            'role' => 'user',
        ]);

        $response = actingAs($user)->patchJson('/api/users/' . $user_details->id, [
            'name' => 'Jane Dela Cuz'
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Jane Dela Cuz',
            'email' => 'juandelacruz@example.com',
            'role' => 'user',
        ]);
    });
});

describe("Delete User", function() {
    it("deletes user data", function() {
        $user = User::factory()->create(['role' => 'admin']);
        $user_details = User::factory()->create();

        $response = actingAs($user)->deleteJson('/api/users/' . $user_details->id);

        $response->assertStatus(204);
    });
});
