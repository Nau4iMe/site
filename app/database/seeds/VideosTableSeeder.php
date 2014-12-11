<?php

class VideosTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::disableQueryLog();
        Eloquent::unguard();
        
        DB::table('videos')->delete();
        $seed = require_once('data/videos.php');
        DB::table('videos')->insert($seed);

        $this->command->info('Videos table seeded!');
    }

}