<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tags As Tags;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tags::factory()->count(3)->create();
    }
}
