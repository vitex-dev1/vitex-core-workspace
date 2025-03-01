<?php

use Faker\Factory as Faker;
use App\Models\Role;
use App\Repositories\RoleRepository;

trait MakeRoleTrait
{
    /**
     * Create fake instance of Role and save it in database
     *
     * @param array $roleFields
     * @return Role
     */
    public function makeRole($roleFields = [])
    {
        /** @var RoleRepository $roleRepo */
        $roleRepo = App::make(RoleRepository::class);
        $theme = $this->fakeRoleData($roleFields);
        return $roleRepo->create($theme);
    }

    /**
     * Get fake instance of Role
     *
     * @param array $roleFields
     * @return Role
     */
    public function fakeRole($roleFields = [])
    {
        return new Role($this->fakeRoleData($roleFields));
    }

    /**
     * Get fake data of Role
     *
     * @param array $roleFields
     * @return array
     */
    public function fakeRoleData($roleFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'active' => $fake->word,
            'name' => $fake->word,
            'description' => $fake->text,
            'permission' => $fake->text
        ], $roleFields);
    }
}
