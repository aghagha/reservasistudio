<?php

use App\TipeUser;
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
        $data = array(
            array('tipe_user_tipe' => 'Pelanggan'),
            array('tipe_user_tipe' => 'Studio'),
            array('tipe_user_tipe' => 'Admin')
        );
        TipeUser::insert($data);
    }
}
