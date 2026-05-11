<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Pemesanan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PemesananPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_pemesanan');
    }

    public function view(AuthUser $authUser, Pemesanan $pemesanan): bool
    {
        return $authUser->can('view_pemesanan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_pemesanan');
    }

    public function update(AuthUser $authUser, Pemesanan $pemesanan): bool
    {
        return $authUser->can('update_pemesanan');
    }

    public function delete(AuthUser $authUser, Pemesanan $pemesanan): bool
    {
        return $authUser->can('delete_pemesanan');
    }

    public function restore(AuthUser $authUser, Pemesanan $pemesanan): bool
    {
        return $authUser->can('restore_pemesanan');
    }

    public function forceDelete(AuthUser $authUser, Pemesanan $pemesanan): bool
    {
        return $authUser->can('force_delete_pemesanan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_pemesanan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_pemesanan');
    }

    public function replicate(AuthUser $authUser, Pemesanan $pemesanan): bool
    {
        return $authUser->can('replicate_pemesanan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_pemesanan');
    }

}