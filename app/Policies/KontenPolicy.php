<?php

namespace App\Policies;

use App\Models\Konten;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;


class KontenPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_role');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Konten $konten): bool
    {
        return $user->can('view_role');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_konten');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Konten $konten): bool
    {
        return $user->can('update_konten');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Konten $konten): bool
    {
        return $user->can('delete_konten');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Konten $konten): bool
    {
        return $user->can('{{ Restore }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Konten $konten): bool
    {
        return $user->can('force_delete_transaksi');
    }
}
