<?php

namespace Database\Seeders;

use Database\Factories\CdrFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        CdrFactory::times(3)->create();
    }
}
