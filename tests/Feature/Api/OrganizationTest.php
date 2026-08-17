<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_organizations_can_be_listed(): void
    {
        Organization::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/organizations');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    /**
     * @return void
     */
    public function test_organization_can_be_created(): void
    {
        $data = [
            'name' => 'Test Organization',
            'slug' => 'test-organization',
        ];

        $response = $this->postJson(
            '/api/v1/organizations',
            $data
        );

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'Test Organization')
            ->assertJsonPath('data.slug', 'test-organization')
            ->assertJsonPath('data.status', 'active');

        $this->assertDatabaseHas('organizations', [
            'name' => 'Test Organization',
            'slug' => 'test-organization',
            'status' => 'active',
        ]);
    }

    /**
     * @return void
     */
    public function test_organization_can_be_shown(): void
    {
        $organization = Organization::factory()->create();

        $response = $this->getJson(
            "/api/v1/organizations/{$organization->id}"
        );

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $organization->id)
            ->assertJsonPath('data.name', $organization->name)
            ->assertJsonPath('data.slug', $organization->slug);
    }

    /**
     * @return void
     */
    public function test_organization_can_be_updated(): void
    {
        $organization = Organization::factory()->create();

        $data = [
            'name' => 'Updated Organization',
            'slug' => 'updated-organization',
        ];

        $response = $this->putJson(
            "/api/v1/organizations/{$organization->id}",
            $data
        );

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Organization')
            ->assertJsonPath('data.slug', 'updated-organization');

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'name' => 'Updated Organization',
            'slug' => 'updated-organization',
        ]);
    }

    /**
     * @return void
     */
    public function test_organization_can_be_deleted(): void
    {
        $organization = Organization::factory()->create();

        $response = $this->deleteJson(
            "/api/v1/organizations/{$organization->id}"
        );

        $response->assertNoContent();

        $this->assertDatabaseMissing('organizations', [
            'id' => $organization->id,
        ]);
    }
}
