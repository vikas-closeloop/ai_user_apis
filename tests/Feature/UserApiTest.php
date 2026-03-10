<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user(): void
    {
        $payload = [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/users', $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('users', [
            'name' => 'Alice',
            'email' => 'alice@example.com',
        ]);

        /** @var User $user */
        $user = User::query()->where('email', 'alice@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_update_user(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $payload = [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $payload);

        $response->assertOk();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);
    }

    public function test_delete_user(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_list_users_with_pagination_sorting_and_search(): void
    {
        User::factory()->create([
            'name' => 'Zed',
            'email' => 'zed@example.com',
            'created_at' => now()->subDays(2),
        ]);
        User::factory()->create([
            'name' => 'Amy',
            'email' => 'amy@example.com',
            'created_at' => now()->subDays(1),
        ]);
        User::factory()->create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'created_at' => now(),
        ]);

        $response = $this->getJson('/api/users?per_page=2&sort_dir=asc&search=example.com');

        $response->assertOk();
        $response->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);
        $this->assertCount(2, $response->json('data'));

        $data = $response->json('data');
        $this->assertSame('Zed', $data[0]['name']);
        $this->assertSame('Amy', $data[1]['name']);

        $searchResponse = $this->getJson('/api/users?search=amy@');
        $searchResponse->assertOk();
        $this->assertCount(1, $searchResponse->json('data'));
        $this->assertSame('amy@example.com', $searchResponse->json('data.0.email'));
    }

    public function test_validation_failure_on_create_user(): void
    {
        $response = $this->postJson('/api/users', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_email_uniqueness_check(): void
    {
        User::factory()->create([
            'email' => 'unique@example.com',
        ]);

        $createResponse = $this->postJson('/api/users', [
            'name' => 'Test',
            'email' => 'unique@example.com',
            'password' => 'password123',
        ]);

        $createResponse->assertUnprocessable();
        $createResponse->assertJsonValidationErrors(['email']);

        $u1 = User::factory()->create(['email' => 'first@example.com']);
        $u2 = User::factory()->create(['email' => 'second@example.com']);

        $updateResponse = $this->putJson("/api/users/{$u2->id}", [
            'email' => 'first@example.com',
        ]);

        $updateResponse->assertUnprocessable();
        $updateResponse->assertJsonValidationErrors(['email']);

        $selfUpdateResponse = $this->putJson("/api/users/{$u1->id}", [
            'email' => 'first@example.com',
        ]);

        $selfUpdateResponse->assertOk();
    }
}

