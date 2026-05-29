<?php

namespace App\Policies;

use App\Models\Slider;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SliderPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_slider');
    }

    public function view(AuthUser $authUser, Slider $slider): bool
    {
        return $authUser->can('view_slider');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_slider');
    }

    public function update(AuthUser $authUser, Slider $slider): bool
    {
        return $authUser->can('update_slider');
    }

    public function delete(AuthUser $authUser, Slider $slider): bool
    {
        return $authUser->can('delete_slider');
    }

    public function restore(AuthUser $authUser, Slider $slider): bool
    {
        return $authUser->can('restore_slider');
    }

    public function forceDelete(AuthUser $authUser, Slider $slider): bool
    {
        return $authUser->can('force_delete_slider');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_slider');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_slider');
    }

    public function replicate(AuthUser $authUser, Slider $slider): bool
    {
        return $authUser->can('replicate_slider');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_slider');
    }
}
