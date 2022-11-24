<?php

use Illuminate\Database\Seeder;

class Setting extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\Setting::create([
            'name' => 'كروتي',
            'summary' => 'كروتي',
        ]);
    }
}
