<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name'     => 'VIREON Admin',
            'email'    => 'admin@vireon.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);
        User::create([
            'name'     => 'Test User',
            'email'    => 'user@vireon.com',
            'password' => Hash::make('user123'),
            'role'     => 'user',
        ]);
    }
}