<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => 105,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'email_verified_at' => now(),
            'type' => 'Admin',
            'company_id' => 'COM-21-001',
            'role_id' => '22',
            'store_id' => 'CSL-22-000007',
            'branch_id' => 'BR-21-001',
            'created_by' => '72',
            'updated_by' => '72'
        ]);
    }
}
