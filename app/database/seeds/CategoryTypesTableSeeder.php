<?php

class CategoryTypesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::disableQueryLog();
        Eloquent::unguard();

        DB::table('category_types')->delete();
        $seed = require_once('data/categorytypes.php');
        DB::table('category_types')->insert($seed);

        $this->command->info('category_types table seeded!');
    }

}