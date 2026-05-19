<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Produk;

class ProdukPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role->name, ['admin', 'kasir'], true);
    }


/**
 * Determine whether the user can view the model.
 */
public function view(User $user, Produk $produk): bool
{
    return in_array($user->role->name, ['admin', 'kasir'], true);
}

/**
 * Determine whether the user can create models.
 */
public function create(User $user): bool
{
    return $user->role->name === 'admin';
}

/**
 * Determine whether the user can update the model.
 */
public function update(User $user, Produk $produk): bool
{
    return $user->role->name === 'admin';
}

/**
 * Determine whether the user can delete the model.
 */
public function delete(User $user, Produk $produk): bool
{
    return $user->role->name === 'admin';
}
}
