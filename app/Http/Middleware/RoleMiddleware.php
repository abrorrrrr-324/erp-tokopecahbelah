<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $requestRole = $this->normalizeRole($request->user()->role);
        $allowedRoles = array_map(fn (string $role) => $this->normalizeRole($role), $roles);

        if (! in_array($requestRole, $allowedRoles, true)) {
            return redirect()->route('login')->withErrors([
                'email' => 'Anda tidak memiliki akses ke halaman ini.',
            ]);
        }

        return $next($request);
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
