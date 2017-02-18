<?php

use Illuminate\Database\Seeder;

class TipeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipe_users')->insert([
            'tipe_user_tipe' => 'Pengguna'
        ]);

        DB::table('tipe_users')->insert([
            'tipe_user_tipe' => 'Studio'
        ]);
    }
}
