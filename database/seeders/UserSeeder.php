<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed data akun pengguna.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'supplier',
                'email' => 'supplier@gmail.com',
                'role' => 'supplier',
            ],
            [
                'name' => 'customer',
                'email' => 'customer@gmail.com',
                'role' => 'customer',
            ],
            [
                'name' => 'kepalagudang',
                'email' => 'kepalagudang@gmail.com',
                'role' => 'kepala_gudang',
            ],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
            ],
            [
                'name' => 'erfan',
                'email' => 'erfan@gmail.com',
                'role' => 'admin',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => Hash::make('123456'),
                ]
            );
        }

        $this->command->info('Seeder berhasil: 5 akun pengguna siap digunakan.');
    }
}
