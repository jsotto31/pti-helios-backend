<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(20)->create();

        // User::factory()->create([
        //     'name' => 'Joshua Sotto',
        //     'email' => 'joshua@example.com',
        //     'password' => Hash::make("a"),
        // ]);

        $user = User::find(1);
        $token = $user->createToken('api-token')->plainTextToken;
        dd($token);

        // $this->call(DailyTimeRecordSeeder::class);
        // $this->call(ApprovalSequenceSetupSeeder::class);
        // $this->call(LeaveApplicationSeeder::class);
    }
}
