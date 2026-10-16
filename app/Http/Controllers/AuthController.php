<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectToRoleDashboard(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        if ($user && $this->isLegacyPlaintextPassword($user, $credentials['password'])) {
            $user->update([
                'password' => Hash::make($credentials['password']),
            ]);

            Auth::login($user);
            $request->session()->regenerate();

            return $this->redirectToRoleDashboard($user);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return $this->redirectToRoleDashboard(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function isLegacyPlaintextPassword(User $user, string $password): bool
    {
        $storedPassword = (string) $user->getAuthPassword();

        if ($storedPassword === '') {
            return false;
        }

        if (preg_match('/^\$2[aby]\$/', $storedPassword) === 1) {
            return Hash::check($password, $storedPassword);
        }

        return hash_equals($storedPassword, $password);
    }

    protected function redirectToRoleDashboard(User $user): RedirectResponse
    {
        return match ($this->normalizeRole($user->role)) {
            'admin' => redirect()->route('admin.dashboard'),
            'kepala_gudang' => redirect()->route('gudang.dashboard'),
            'supplier' => redirect()->route('supplier.dashboard'),
            'customer' => redirect()->route('customer.dashboard'),
            default => redirect()->route('login')->withErrors([
                'email' => 'Role pengguna tidak valid.',
            ]),
        };
    }

    protected function normalizeRole(?string $role): ?string
    {
        return match (strtolower(trim((string) $role))) {
            'admin' => 'admin',
            'gudang', 'kepala_gudang', 'kepala gudang' => 'kepala_gudang',
            'supplier', 'suplier' => 'supplier',
            'customer', 'costumer' => 'customer',
            default => null,
        };
    }
}
