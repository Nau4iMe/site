<?php

class ContentRatingTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::disableQueryLog();
        Eloquent::unguard();
        
        DB::table('content_rating')->delete();
        $seed = require_once('data/content_rating.php');
        DB::table('content_rating')->insert($seed);

        $this->command->info('Content Rating table seeded!');
    }

}