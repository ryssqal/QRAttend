<?php

namespace Database\Seeders;

use App\Models\PengurusMajlis;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PengurusMajlisSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PengurusMajlis::firstOrCreate([
            'email' => 'faizul@gmail.com',
        ], [
            'name' => 'Faizul',
            'password' => Hash::make('faizul123'),
            'role' => 'pengurusMajlis',
        ]);

        PengurusMajlis::firstOrCreate([
            'email' => 'newpengurus@gmail.com',
        ], [
            'name' => 'New Pengurus',
            'password' => Hash::make('password123'),
            'role' => 'pengurusMajlis',
        ]);
    }
}
