<?php

class DatabaseSeeder extends Seeder {

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
        $data = array(
            array('id' => '1','type' => 'system','title' => 'запазен','description' => 'System reserved','created_at' => '2009-10-18 13:07:09','updated_at' => '0000-00-00 00:00:00'),
            array('id' => '2','type' => 'main','title' => 'категория','description' => 'Основната навигация на сайта','created_at' => '2009-10-18 13:07:09','updated_at' => '0000-00-00 00:00:00'),
            array('id' => '3','type' => 'top','title' => 'хоризонтална навигация','description' => 'Хоризонталната навигация на сайта','created_at' => '2009-10-18 13:07:09','updated_at' => '0000-00-00 00:00:00'),
            array('id' => '4','type' => 'links','title' => 'допълнителна навигация','description' => 'Препратки, банери и всичко останало','created_at' => '2009-10-18 13:07:09','updated_at' => '0000-00-00 00:00:00')
        );
        DB::table('category_types')->insert($data);
        $this->command->info('Category Types table seeded!');

        DB::table('categories')->delete();
        $data = array(
            'title'     => 'root',
            '_lft'      => 1,
            '_rgt'      => 2,
            'parent_id' => null,
            'slug'      => 'root',
            'level'     => 0,
            'path'      => '/',
            'type'      => 'system',
            'created_at'=> '2009-10-18 13:07:09'
        );
        DB::table('categories')->insert($data);

        $this->command->info('Categories table seeded!');

    }

}