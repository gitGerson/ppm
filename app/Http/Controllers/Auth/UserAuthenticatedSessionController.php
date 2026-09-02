<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserAuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.user-login');
    }

    public function store(UserLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function register(UserRegisterRequest $request): RedirectResponse
    {
        User::query()->create($request->validated());

        return redirect()
            ->route('login')
            ->with('status', 'Pendaftaran berhasil. Silakan masuk menggunakan akun Anda.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        auth()->guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
