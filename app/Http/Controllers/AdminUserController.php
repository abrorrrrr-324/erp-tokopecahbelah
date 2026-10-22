<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function customerIndex(): View
    {
        return view('admin.customer.index', [
            'customers' => $this->usersByRole('customer'),
            'stats' => $this->statsForRole('customer'),
        ]);
    }

    public function customerCreate(): View
    {
        return view('admin.customer.create', [
            'customer' => new User,
        ]);
    }

    public function customerShow(User $user): View
    {
        abort_unless($user->role === 'customer', 404);

        return view('admin.customer.show', [
            'customer' => $user,
        ]);
    }

    public function customerEdit(User $user): View
    {
        abort_unless($user->role === 'customer', 404);

        return view('admin.customer.edit', [
            'customer' => $user,
        ]);
    }

    public function storeCustomer(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama customer wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        return redirect()
            ->route('admin.customer')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function updateCustomer(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'customer', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.customer')->with('success', 'Data customer berhasil diperbarui.');
    }

    public function destroyCustomer(User $user): RedirectResponse
    {
        abort_unless($user->role === 'customer', 404);

        $user->delete();

        return redirect()->route('admin.customer')->with('success', 'Customer berhasil dihapus.');
    }

    public function supplierIndex(): View
    {
        return view('admin.supplier.index', [
            'suppliers' => $this->usersByRole('supplier'),
            'stats' => $this->statsForRole('supplier'),
        ]);
    }

    public function supplierCreate(): View
    {
        return view('admin.supplier.create', [
            'supplier' => new User,
        ]);
    }

    public function supplierShow(User $user): View
    {
        abort_unless($user->role === 'supplier', 404);

        return view('admin.supplier.show', [
            'supplier' => $user,
        ]);
    }

    public function supplierEdit(User $user): View
    {
        abort_unless($user->role === 'supplier', 404);

        return view('admin.supplier.edit', [
            'supplier' => $user,
        ]);
    }

    public function storeSupplier(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'supplier',
        ]);

        return redirect()->route('admin.supplier')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function updateSupplier(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'supplier', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.supplier')->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroySupplier(User $user): RedirectResponse
    {
        abort_unless($user->role === 'supplier', 404);

        $user->delete();

        return redirect()->route('admin.supplier')->with('success', 'Supplier berhasil dihapus.');
    }

    protected function usersByRole(string $role)
    {
        return User::query()
            ->where('role', $role)
            ->latest()
            ->get();
    }

    protected function statsForRole(string $role): array
    {
        $users = $this->usersByRole($role);

        return [
            'total' => $users->count(),
            'active' => $users->count(),
            'newThisMonth' => User::query()
                ->where('role', $role)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'review' => 0,
            'lastUpdated' => $users->first()?->updated_at?->translatedFormat('d M Y') ?? 'Belum ada',
        ];
    }
}
