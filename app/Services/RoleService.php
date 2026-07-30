<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class RoleService
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository
    ) {}

    /**
     * Get All Roles
     */
    public function getAll(): Collection
    {
        return $this->roleRepository->getAll();
    }

    /**
     * Get Role By UUID
     */
    public function findByUuid(string $uuid): ?Role
    {
        return $this->roleRepository->findByUuid($uuid);
    }

    /**
     * Create Role
     */
    public function create(array $data): Role
    {
        return $this->roleRepository->create($data);
    }

    /**
     * Update Role
     */
    public function update(string $uuid, array $data): bool
    {
        $role = $this->findByUuid($uuid);

        if (!$role) {
            throw ValidationException::withMessages([
                'role' => 'Role tidak ditemukan.'
            ]);
        }

        return $this->roleRepository->update($role, $data);
    }

    /**
     * Delete Role
     */
    public function delete(string $uuid): bool
    {
        $role = $this->findByUuid($uuid);

        if (!$role) {
            throw ValidationException::withMessages([
                'role' => 'Role tidak ditemukan.'
            ]);
        }

        if ($role->code === 'SUPER_ADMIN') {
            throw ValidationException::withMessages([
                'role' => 'Role SUPER_ADMIN tidak boleh dihapus.'
            ]);
        }

        return $this->roleRepository->delete($role);
    }
}