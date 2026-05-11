<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Pengumuman;
use Illuminate\Auth\Access\HandlesAuthorization;

class PengumumanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_pengumuman');
    }

    public function view(AuthUser $authUser, Pengumuman $pengumuman): bool
    {
        return $authUser->can('view_pengumuman');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_pengumuman');
    }

    public function update(AuthUser $authUser, Pengumuman $pengumuman): bool
    {
        return $authUser->can('update_pengumuman');
    }

    public function delete(AuthUser $authUser, Pengumuman $pengumuman): bool
    {
        return $authUser->can('delete_pengumuman');
    }

    public function restore(AuthUser $authUser, Pengumuman $pengumuman): bool
    {
        return $authUser->can('restore_pengumuman');
    }

    public function forceDelete(AuthUser $authUser, Pengumuman $pengumuman): bool
    {
        return $authUser->can('force_delete_pengumuman');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_pengumuman');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_pengumuman');
    }

    public function replicate(AuthUser $authUser, Pengumuman $pengumuman): bool
    {
        return $authUser->can('replicate_pengumuman');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_pengumuman');
    }

}