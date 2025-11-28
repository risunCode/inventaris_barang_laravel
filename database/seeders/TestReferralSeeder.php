<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReferralCode;
use App\Models\User;

class TestReferralSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@inventaris.com')->first();
        
        if ($admin) {
            // Create test referral codes
            ReferralCode::create([
                'code' => 'TEST123',
                'description' => 'Test Code for Debug',
                'role' => 'user',
                'created_by' => $admin->id,
                'is_active' => true,
                'max_uses' => 10,
            ]);

            ReferralCode::create([
                'code' => 'STAFF456', 
                'description' => 'Staff Test Code',
                'role' => 'staff',
                'created_by' => $admin->id,
                'is_active' => true,
                'max_uses' => 5,
            ]);

            $this->command->info('Test referral codes created!');
        } else {
            $this->command->error('Admin user not found!');
        }
    }
}
