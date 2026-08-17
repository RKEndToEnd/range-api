<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Association;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssociationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_associations_can_be_listed(): void
    {
        $organization = Organization::factory()->create();

        Association::factory()
            ->count(3)
            ->for($organization)
            ->create();

        $response = $this->getJson(
            "/api/v1/organizations/{$organization->id}/associations"
        );

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    /**
     * @return void
     */
    public function test_association_can_be_created(): void
    {
        $organization = Organization::factory()->create();

        $data = [
            'name' => 'Test Association',
            'slug' => 'test-association',
            'email' => 'association@example.com',
            'phone' => '123456789',
            'address' => 'Test Street 1',
            'city' => 'Bialystok',
            'postal_code' => '15-001',
            'country' => 'PL',
        ];

        $response = $this->postJson(
            "/api/v1/organizations/{$organization->id}/associations",
            $data
        );

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'Test Association')
            ->assertJsonPath('data.slug', 'test-association')
            ->assertJsonPath(
                'data.organization_id',
                $organization->id
            )
            ->assertJsonPath('data.status', 'active');

        $this->assertDatabaseHas('associations', [
            'organization_id' => $organization->id,
            'name' => 'Test Association',
            'slug' => 'test-association',
            'status' => 'active',
        ]);
    }

    /**
     * @return void
     */
    public function test_association_can_be_shown(): void
    {
        $organization = Organization::factory()->create();

        $association = Association::factory()
            ->for($organization)
            ->create();

        $response = $this->getJson(
            "/api/v1/organizations/{$organization->id}/associations/{$association->id}"
        );

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $association->id)
            ->assertJsonPath('data.name', $association->name)
            ->assertJsonPath('data.slug', $association->slug)
            ->assertJsonPath(
                'data.organization_id',
                $organization->id
            );
    }

    /**
     * @return void
     */
    public function test_association_can_be_updated(): void
    {
        $organization = Organization::factory()->create();

        $association = Association::factory()
            ->for($organization)
            ->create();

        $data = [
            'name' => 'Updated Association',
            'slug' => 'updated-association',
        ];

        $response = $this->putJson(
            "/api/v1/organizations/{$organization->id}/associations/{$association->id}",
            $data
        );

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Association')
            ->assertJsonPath('data.slug', 'updated-association');

        $this->assertDatabaseHas('associations', [
            'id' => $association->id,
            'organization_id' => $organization->id,
            'name' => 'Updated Association',
            'slug' => 'updated-association',
        ]);
    }

    /**
     * @return void
     */
    public function test_association_can_be_deleted(): void
    {
        $organization = Organization::factory()->create();

        $association = Association::factory()
            ->for($organization)
            ->create();

        $response = $this->deleteJson(
            "/api/v1/organizations/{$organization->id}/associations/{$association->id}"
        );

        $response->assertNoContent();

        $this->assertDatabaseMissing('associations', [
            'id' => $association->id,
        ]);
    }

    /**
     * @return void
     */
    public function test_association_cannot_be_accessed_through_another_organization(): void
    {
        $organization = Organization::factory()->create();

        $anotherOrganization = Organization::factory()->create();

        $association = Association::factory()
            ->for($organization)
            ->create();

        $response = $this->getJson(
            "/api/v1/organizations/{$anotherOrganization->id}/associations/{$association->id}"
        );

        $response->assertNotFound();
    }
}
