<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ==================== HALAMAN LOGIN ====================

    // Menampilkan login atau mengarahkan user yang sudah login
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectToRoleDashboard(Auth::user());
        }

        return view('auth.login');
    }

    // Memvalidasi login dan memperbarui password lama jika diperlukan
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        // Login dengan password lama yang masih berupa teks biasa
        if ($user && $this->isLegacyPlaintextPassword($user, $credentials['password'])) {
            $user->update(['password' => Hash::make($credentials['password'])]);
            Auth::login($user);
            $request->session()->regenerate();

            return $this->redirectToRoleDashboard($user);
        }

        // Login normal menggunakan autentikasi Laravel
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return $this->redirectToRoleDashboard(Auth::user());
        }

        // Pesan jika email atau password salah
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // ==================== LOGOUT ====================

    // Keluar dari akun dan mengamankan sesi
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ==================== VALIDASI PASSWORD LAMA ====================

    // Memeriksa password teks biasa atau password yang sudah di-hash
    protected function isLegacyPlaintextPassword(User $user, string $password): bool
    {
        $storedPassword = (string) $user->getAuthPassword();

        if ($storedPassword === '') {
            return false;
        }

        // Jika password sudah menggunakan format bcrypt, verifikasi hash
        if (preg_match('/^\$2[aby]\$/', $storedPassword) === 1) {
            return Hash::check($password, $storedPassword);
        }

        // Jika masih berupa teks biasa, bandingkan secara aman
        return hash_equals($storedPassword, $password);
    }

    // ==================== REDIRECT DASHBOARD ====================

    // Mengarahkan pengguna berdasarkan role masing-masing
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

    // Menyeragamkan penulisan role agar sesuai dengan sistem
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
