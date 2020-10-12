<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(OptionsTableSeeder::class);
        $this->call(ThemeTableSeeder::class);
        $this->call(ThemeMetaTableSeeder::class);
        $this->call(WidgetGroupsTableSeeder::class);
        $this->call(WidgetTableSeeder::class);
        $this->call(CountriesTableSeeder::class);

        // Map translation data from origin tables
        $this->call(MapPostTranslationData::class);
        $this->call(MapTermTranslationData::class);

        // Workspace
        $this->call(WorkspaceTableSeeder::class);
    }
}
