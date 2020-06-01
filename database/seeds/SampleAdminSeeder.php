<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SampleAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            "name" => "Sample Admin",
            "email" => "sample.admin@example.com",
            "email_verified_at" => new \DateTime(),
            "created_at" => new \DateTime(),
            "updated_at" => new \DateTime(),
            "password" => '$2y$10$I5kVfeqHSPndcOZobJ416e3OFwd93ISGI.0CEVVYfaIOzudcAtXwS', // password123
            "role" => "admin"
        ]);
    }
}
