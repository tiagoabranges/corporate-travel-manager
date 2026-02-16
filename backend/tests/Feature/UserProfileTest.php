<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_update_profile()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->putJson('/api/me', [
            'name' => 'Novo Nome',
            'email' => 'novo@email.com',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Novo Nome',
                     'email' => 'novo@email.com',
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Novo Nome',
            'email' => 'novo@email.com',
        ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_update_profile()
    {
        $response = $this->putJson('/api/me', [
            'name' => 'Teste',
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function user_cannot_update_email_to_existing_email()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create([
            'email' => 'existente@email.com'
        ]);

        $this->actingAs($user1, 'api');

        $response = $this->putJson('/api/me', [
            'email' => 'existente@email.com'
        ]);

        $response->assertStatus(422);
    }
}
