<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DetailSantri;
use Illuminate\Auth\Access\HandlesAuthorization;

class DetailSantriPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_detail_santri');
    }

    public function view(AuthUser $authUser, DetailSantri $detailSantri): bool
    {
        return $authUser->can('view_detail_santri');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_detail_santri');
    }

    public function update(AuthUser $authUser, DetailSantri $detailSantri): bool
    {
        return $authUser->can('update_detail_santri');
    }

    public function delete(AuthUser $authUser, DetailSantri $detailSantri): bool
    {
        return $authUser->can('delete_detail_santri');
    }

    public function restore(AuthUser $authUser, DetailSantri $detailSantri): bool
    {
        return $authUser->can('restore_detail_santri');
    }

    public function forceDelete(AuthUser $authUser, DetailSantri $detailSantri): bool
    {
        return $authUser->can('force_delete_detail_santri');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_detail_santri');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_detail_santri');
    }

    public function replicate(AuthUser $authUser, DetailSantri $detailSantri): bool
    {
        return $authUser->can('replicate_detail_santri');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_detail_santri');
    }

}