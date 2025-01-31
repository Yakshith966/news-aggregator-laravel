<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        User::updateOrCreate(
            ['email' => 'test@example.com'], 
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
            ]
        );

        $categories = Category::factory(5)->create();
        $authors = Author::factory(5)->create();
        $sources = Source::factory(5)->create();

        for ($i = 0; $i < 10; $i++) {
            Article::factory(10)->create([
                'category_id' => $categories->random()->id,
                'author_id' => $authors->random()->id,
                'source_id' => $sources->random()->id,
            ]);
        }
    }
}
