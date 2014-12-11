<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('ContentTableSeeder');
        $this->call('CategoriesTableSeeder');
        $this->call('CategoryTypesTableSeeder');
        $this->call('ContentRatingTableSeeder');
        $this->call('VideosTableSeeder');
        $this->command->info('All tables seeded!');
    }

}