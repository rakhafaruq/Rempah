<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Donor;
use App\Models\Donation;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // DONATUR
        $donatur = User::create([
            'name' => 'Global Bakery',
            'email' => 'globalbakery@gmail.com',
            'phone' => '08123456789',
            'password' => Hash::make('password123'),
            'role' => 'donatur'
        ]);

        $donor = Donor::create([
            'user_id' => $donatur->id,
            'nama_toko' => 'Global Bakery',
            'alamat' => 'Bandung'
        ]);

        // RELAWAN
        User::create([
            'name' => 'Luthfi',
            'email' => 'luthfi@gmail.com',
            'phone' => '0811111111',
            'password' => Hash::make('password123'),
            'role' => 'relawan'
        ]);

        User::create([
            'name' => 'Yudhistira',
            'email' => 'yudhistira@gmail.com',
            'phone' => '0822222222',
            'password' => Hash::make('password123'),
            'role' => 'relawan'
        ]);

        // DONASI
        Donation::create([
            'title' => 'Roti Tawar & Roti Manis Sisa',
            'description' => 'Sisa hari ini',
            'donor_id' => $donor->id,
            'location' => 'Bandung',
            'pickup_deadline' => now()->addHours(3),
            'total_portion' => 20,
            'remaining_portion' => 20,
            'status' => 'tersedia'
        ]);

        Donation::create([
            'title' => 'Nasi Bungkus Sisa',
            'description' => 'Masih layak',
            'donor_id' => $donor->id,
            'location' => 'Bandung',
            'pickup_deadline' => now()->addHours(2),
            'total_portion' => 10,
            'remaining_portion' => 10,
            'status' => 'tersedia'
        ]);
    }
}