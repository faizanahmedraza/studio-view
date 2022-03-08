<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\User::create([
            'role_id' => 2,
            'role_type' => 1,
            'first_name' => 'Studio Admin',
            'email' => 'admin@studio.com',
            'is_admin' => 1,
            'password' => bcrypt('admin123'),
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s')
        ]);
    }
}
