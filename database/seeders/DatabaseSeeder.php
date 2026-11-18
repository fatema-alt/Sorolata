<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            HeritageCommerceSeeder::class,
            PageTemplateSeeder::class,
            CommerceEngineSeeder::class,
            AdminSeeder::class,
            EnterpriseSchemaSeeder::class,
        ]);
    }
}
