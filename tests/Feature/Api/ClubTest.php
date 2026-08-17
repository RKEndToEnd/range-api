<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Club;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClubTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_clubs_can_be_listed(): void
    {
        $organization = Organization::factory()->create();

        Club::factory()
            ->count(3)
            ->for($organization)
            ->create();

        $response = $this->getJson(
            "/api/v1/organizations/{$organization->id}/clubs"
        );

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    /**
     * @return void
     */
    public function test_club_can_be_created(): void
    {
        $organization = Organization::factory()->create();

        $data = [
            'name' => 'Test Club',
            'slug' => 'test-club',
            'email' => 'club@example.com',
            'phone' => '123456789',
            'address' => 'Test Street 1',
            'city' => 'Bialystok',
            'postal_code' => '15-001',
            'country' => 'PL',
        ];

        $response = $this->postJson(
            "/api/v1/organizations/{$organization->id}/clubs",
            $data
        );

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'Test Club')
            ->assertJsonPath('data.slug', 'test-club')
            ->assertJsonPath('data.organization_id', $organization->id)
            ->assertJsonPath('data.status', 'active');

        $this->assertDatabaseHas('clubs', [
            'organization_id' => $organization->id,
            'name' => 'Test Club',
            'slug' => 'test-club',
            'status' => 'active',
        ]);
    }

    /**
     * @return void
     */
    public function test_club_can_be_shown(): void
    {
        $organization = Organization::factory()->create();

        $club = Club::factory()
            ->for($organization)
            ->create();

        $response = $this->getJson(
            "/api/v1/organizations/{$organization->id}/clubs/{$club->id}"
        );

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $club->id)
            ->assertJsonPath('data.name', $club->name)
            ->assertJsonPath('data.slug', $club->slug)
            ->assertJsonPath(
                'data.organization_id',
                $organization->id
            );
    }

    /**
     * @return void
     */
    public function test_club_can_be_updated(): void
    {
        $organization = Organization::factory()->create();

        $club = Club::factory()
            ->for($organization)
            ->create();

        $data = [
            'name' => 'Updated Club',
            'slug' => 'updated-club',
        ];

        $response = $this->putJson(
            "/api/v1/organizations/{$organization->id}/clubs/{$club->id}",
            $data
        );

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Club')
            ->assertJsonPath('data.slug', 'updated-club');

        $this->assertDatabaseHas('clubs', [
            'id' => $club->id,
            'organization_id' => $organization->id,
            'name' => 'Updated Club',
            'slug' => 'updated-club',
        ]);
    }

    /**
     * @return void
     */
    public function test_club_can_be_deleted(): void
    {
        $organization = Organization::factory()->create();

        $club = Club::factory()
            ->for($organization)
            ->create();

        $response = $this->deleteJson(
            "/api/v1/organizations/{$organization->id}/clubs/{$club->id}"
        );

        $response->assertNoContent();

        $this->assertDatabaseMissing('clubs', [
            'id' => $club->id,
        ]);
    }

    /**
     * @return void
     */
    public function test_club_cannot_be_accessed_through_another_organization(): void
    {
        $organization = Organization::factory()->create();

        $anotherOrganization = Organization::factory()->create();

        $club = Club::factory()
            ->for($organization)
            ->create();

        $response = $this->getJson(
            "/api/v1/organizations/{$anotherOrganization->id}/clubs/{$club->id}"
        );

        $response->assertNotFound();
    }
}
