<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit');
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Profil berhasil diperbarui.');
    }
}
