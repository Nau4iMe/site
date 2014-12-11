<?php

class CategoriesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::disableQueryLog();
        Eloquent::unguard();
        
        DB::table('categories')->delete();
        $seed = require_once('data/categories.php');
        DB::table('categories')->insert($seed);

        $this->command->info('Categories table seeded!');

    }

}