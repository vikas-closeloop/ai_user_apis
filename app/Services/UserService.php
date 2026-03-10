<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class UserService
{
    public function createUser(array $data): User
    {
        /** @var User $user */
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return $user;
    }

    public function updateUser(int $id, array $data): User
    {
        $user = $this->getUserById($id);

        $user->fill(Arr::only($data, ['name', 'email']));
        $user->save();

        return $user;
    }

    public function deleteUser(int $id): void
    {
        $user = $this->getUserById($id);
        $user->delete();
    }

    /**
     * @param  array{
     *   page?: int|string,
     *   per_page?: int|string,
     *   sort_dir?: 'asc'|'desc'|string,
     *   search?: string
     * }  $filters
     */
    public function getUsers(array $filters): LengthAwarePaginator
    {
        $query = User::query();

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $query->where(function (Builder $q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $sortDir = strtolower((string) ($filters['sort_dir'] ?? 'desc'));
        $sortDir = in_array($sortDir, ['asc', 'desc'], true) ? $sortDir : 'desc';

        $perPage = (int) ($filters['per_page'] ?? 15);
        $perPage = $perPage > 0 ? min($perPage, 100) : 15;

        return $query
            ->orderBy('created_at', $sortDir)
            ->paginate($perPage);
    }

    public function getUserById(int $id): User
    {
        /** @var User $user */
        $user = User::query()->findOrFail($id);

        return $user;
    }
}

