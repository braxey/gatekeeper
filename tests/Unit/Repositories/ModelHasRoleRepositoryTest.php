<?php

namespace Gillyware\Gatekeeper\Tests\Unit\Repositories;

use Gillyware\Gatekeeper\Models\ModelHasRole;
use Gillyware\Gatekeeper\Models\Role;
use Gillyware\Gatekeeper\Repositories\ModelHasRoleRepository;
use Gillyware\Gatekeeper\Tests\Fixtures\User;
use Gillyware\Gatekeeper\Tests\TestCase;
use Illuminate\Support\Facades\Config;

class ModelHasRoleRepositoryTest extends TestCase
{
    protected ModelHasRoleRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(ModelHasRoleRepository::class);
    }

    public function test_create_model_has_role()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $record = $this->repository->create($user, $role);

        $this->assertDatabaseHas(Config::get('gatekeeper.tables.model_has_roles'), [
            'model_type' => $user->getMorphClass(),
            'model_id' => $user->id,
            'role_id' => $role->id,
        ]);
        $this->assertInstanceOf(ModelHasRole::class, $record);
    }

    public function test_get_for_model_and_role()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $this->repository->create($user, $role);

        $results = $this->repository->getForModelAndRole($user, $role);

        $this->assertCount(1, $results);
        $this->assertInstanceOf(ModelHasRole::class, $results->first());
    }

    public function test_get_recent_for_model_and_role_including_trashed()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $record = $this->repository->create($user, $role);
        $record->delete();

        $recent = $this->repository->getRecentForModelAndRoleIncludingTrashed($user, $role);

        $this->assertNotNull($recent);
        $this->assertEquals($record->id, $recent->id);
    }

    public function test_delete_for_model_and_role()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $this->repository->create($user, $role);

        $this->assertTrue($this->repository->deleteForModelAndRole($user, $role));

        $this->assertSoftDeleted(Config::get('gatekeeper.tables.model_has_roles'), [
            'model_type' => $user->getMorphClass(),
            'model_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }
}
