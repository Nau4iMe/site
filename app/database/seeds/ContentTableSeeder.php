<?php

class ContentTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::disableQueryLog();
        Eloquent::unguard();
        
        DB::table('content')->delete();
        $seed = require_once('data/content.php');
        DB::table('content')->insert($seed);

        $this->command->info('Content table seeded!');
    }

}