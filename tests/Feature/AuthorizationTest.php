<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_moderator_cannot_delete_page(): void
    {
        $this->seed();

        $moderator = User::where('email', 'moderator@example.com')->first();

        $page = Page::create([
            'title' => 'Test Page',
            'body' => 'Test body',
            'status' => 'draft',
        ]);

        $token = $moderator->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->deleteJson("/api/pages/{$page->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'deleted_at' => null,
        ]);
    }

    public function test_admin_can_delete_page(): void
{
    $this->seed();

    $admin = User::where('email', 'admin@example.com')->first();

    $page = Page::create([
        'title' => 'Admin Test Page',
        'body' => 'Test body',
        'status' => 'draft',
    ]);

    $token = $admin->createToken('test-token')->plainTextToken;

    $response = $this->withToken($token)
        ->deleteJson("/api/pages/{$page->id}");

    $response->assertStatus(200);

    $this->assertSoftDeleted('pages', [
        'id' => $page->id,
    ]);
}
public function test_user_cannot_access_pages_without_authentication(): void
{
    $response = $this->getJson('/api/pages');

    $response->assertStatus(401);
}
}