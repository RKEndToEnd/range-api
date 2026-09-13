<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * @var array|string[]
     */
    private array $permissions = [
        'organization.view',
        'organization.update',

        'users.view',
        'users.create',
        'users.update',
        'users.delete',

        'roles.view',
        'roles.create',
        'roles.update',
        'roles.delete',

        'permissions.view',
        'permissions.assign',

        'clubs.view',
        'clubs.create',
        'clubs.update',
        'clubs.delete',

        'associations.view',
        'associations.create',
        'associations.update',
        'associations.delete',
    ];

    /**
     * @var array|string[]
     */
    private array $organizationAdminPermissions = [
        'organization.view',
        'organization.update',

        'users.view',
        'users.create',
        'users.update',
        'users.delete',

        'clubs.view',
        'clubs.create',
        'clubs.update',
        'clubs.delete',

        'associations.view',
        'associations.create',
        'associations.update',
        'associations.delete',
    ];

    /**
     * @return void
     */
    public function run(): void
    {
        $this->createPermissions();
        $this->createSuperAdmin();

        Organization::query()->each(
            function (Organization $organization): void {
                $this->createOrganizationRoles($organization);
            }
        );
    }

    /**
     * @return void
     */
    private function createPermissions(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
    }

    /**
     * @return void
     */
    private function createSuperAdmin(): void
    {
        app(PermissionRegistrar::class)
            ->setPermissionsTeamId(null);

        $role = Role::findOrCreate(
            'super_admin',
            'web'
        );

        $role->syncPermissions(
            Permission::query()->get()
        );
    }

    /**
     * @param Organization $organization
     * @return void
     */
    private function createOrganizationRoles(
        Organization $organization
    ): void {
        app(PermissionRegistrar::class)
            ->setPermissionsTeamId($organization->id);

        $owner = Role::findOrCreate(
            'organization_owner',
            'web'
        );

        $owner->syncPermissions(
            Permission::query()->get()
        );

        $admin = Role::findOrCreate(
            'organization_admin',
            'web'
        );

        $admin->syncPermissions(
            Permission::query()
                ->whereIn(
                    'name',
                    $this->organizationAdminPermissions
                )
                ->get()
        );
    }
}
