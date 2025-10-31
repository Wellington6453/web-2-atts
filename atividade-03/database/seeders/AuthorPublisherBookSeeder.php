<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;

class AuthorPublisherBookSeeder extends Seeder
{
    public function run()
    {
        // Certifica-se que existam categorias
        if (Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }

    // Gera 10 autores, cada um com 5 livros (temporariamente reduzido para teste)
    Author::factory(10)->create()->each(function ($author) {
            // Gera uma editora para cada autor
            $publisher = Publisher::factory()->create();

            // Cria 5 livros para cada autor, associando uma categoria existente
            $author->books()->createMany(
                Book::factory(5)->make([
                    'category_id' => Category::inRandomOrder()->first()->id,
                    'publisher_id' => $publisher->id,
                ])->toArray()
            );
        });
    }
}
