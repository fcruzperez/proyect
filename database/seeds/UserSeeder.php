<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');

      DB::table('users')->truncate();

      DB::table('users')->insert([
        [
          'id' => 1,
          'name' => 'admin',
          'email' => 'admin@test.com',
          'password' => bcrypt('11111111'),
          'role' => 'admin',
          'mobile' => '123456789'
        ],
      ]);

      DB::table('users')->insert([
        [
          'id' => 2,
          'name' => 'client',
          'email' => 'client@test.com',
          'paypal_email' => 'client@test.com',
          'password' => bcrypt('11111111'),
          'role' => 'client',
          'mobile' => '123456789'
        ],
      ]);

      DB::table('users')->insert([
        [
          'id' => 3,
          'name' => 'designer',
          'email' => 'designer@test.com',
          'paypal_email' => 'designer@test.com',
          'password' => bcrypt('11111111'),
          'role' => 'designer',
          'mobile' => '123456789'
        ],
      ]);

      DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
