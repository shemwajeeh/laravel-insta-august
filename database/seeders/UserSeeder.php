<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        User::create([
            'name'      => 'Sota',
            'email'     => 'sota@gmail.com',
            'password'  => Hash::make('sota12345'),
            'role_id'   => 1
        ]);
        User::create([
            'name'      => 'Junya',
            'email'     => 'junya@gmail.com',
            'password'  => Hash::make('junya12345'),
            'role_id'   => 2
        ]);
        User::create([
            'name'      => 'Akane',
            'email'     => 'akane@gmail.com',
            'password'  => Hash::make('akane12345'),
            'role_id'   => 2
        ]);
        User::create([
            'name'      => 'Chise',
            'email'     => 'chise@gmail.com',
            'password'  => Hash::make('chise12345'),
            'role_id'   => 2
        ]);
        User::create([
            'name'      => 'Shinya',
            'email'     => 'shinya@gmail.com',
            'password'  => Hash::make('shinya12345'),
            'role_id'   => 2
        ]);
    }
}
