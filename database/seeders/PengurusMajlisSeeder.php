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
        PengurusMajlis::create([
            'name' => 'Faizul',
            'email' => 'faizul@gmail.com',
            'password' => Hash::make('faizul123'),
            'role' => 'pengurusMajlis',
        ]);
    }
}
