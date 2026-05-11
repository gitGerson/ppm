<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CurriculumItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class CurriculumItemPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_curriculum_item');
    }

    public function view(AuthUser $authUser, CurriculumItem $curriculumItem): bool
    {
        return $authUser->can('view_curriculum_item');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_curriculum_item');
    }

    public function update(AuthUser $authUser, CurriculumItem $curriculumItem): bool
    {
        return $authUser->can('update_curriculum_item');
    }

    public function delete(AuthUser $authUser, CurriculumItem $curriculumItem): bool
    {
        return $authUser->can('delete_curriculum_item');
    }

    public function restore(AuthUser $authUser, CurriculumItem $curriculumItem): bool
    {
        return $authUser->can('restore_curriculum_item');
    }

    public function forceDelete(AuthUser $authUser, CurriculumItem $curriculumItem): bool
    {
        return $authUser->can('force_delete_curriculum_item');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_curriculum_item');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_curriculum_item');
    }

    public function replicate(AuthUser $authUser, CurriculumItem $curriculumItem): bool
    {
        return $authUser->can('replicate_curriculum_item');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_curriculum_item');
    }

}