<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'              =>  'Johnny Brak',
            'email'             =>  'jbrak@emails.com',
            'email_verified_at' =>  Carbon::now(),
            'password'          =>  Hash::make('secretPass12'),
        ]);
    }
}
