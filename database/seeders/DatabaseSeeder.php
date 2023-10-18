<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\MainCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function countTotalPrice($cart)
    {
        $totalPrice = 0;
        foreach ($cart->books as $book) {
            $totalPrice += $book->price * $book->pivot->count;
        }

        return $totalPrice;
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\User::factory(60)->create();
        \App\Models\Address::factory(100)->create();
        $carts = \App\Models\Cart::factory(300)->create();
        MainCategory::factory()->psychology()->create();
        MainCategory::factory()->philosophical()->create();
        MainCategory::factory()->shortStory()->create();
        MainCategory::factory()->political()->create();
        MainCategory::factory()->historical()->create();
        Category::factory()->selfKnowledge()->create();
        Category::factory()->americanLiterature()->create();
        Category::factory()->fantasy()->create();
        Category::factory()->iranPolitical()->create();
        Category::factory()->parthians()->create();
        Category::factory()->psychoanalysis()->create();
        Category::factory()->frenchLiterature()->create();
        Category::factory()->adventure()->create();
        Category::factory()->russianPolitical()->create();
        Category::factory()->historyOfGreece()->create();
        $categories = Category::all();
        \App\Models\Writer::factory(15)->create();
        $translators = \App\Models\Translator::factory(20)->create();
        $books = \App\Models\Book::factory(150)->create();
        // Creating the admin user
        \App\Models\User::factory()->create([
            'name' => 'admin',
            'lastname' => 'main',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        $articles = \App\Models\Article::factory(40)->create();
        \App\Models\Comment::factory(300)->create();
        $tags = \App\Models\Tag::factory(35)->create();
        // Attach random translators to each book
        foreach ($books as $book) {
            $hasTranslators = random_int(0, 1);
            if ($hasTranslators) {
                $book->translators()->attach($translators->random(rand(1, 3))->pluck('id')->toArray());
            }
            $book->tags()->attach($tags->random(rand(1, 5))->pluck('id')->toArray());
            $book->categories()->attach($categories->random(rand(1, 3))->pluck('id')->toArray());
        }
        // Attach random tags to the articles
        foreach ($articles as $article) {
            $article->tags()->attach(
                $tags->random(rand(1, 5))->pluck('id')->toArray()
            );
        }
        // attach books to carts and calculate the total price
        foreach ($carts as $cart) {
            $random_books = $books->random(rand(1, 4))->pluck('id')->toArray();
            foreach ($random_books as $book) {
                $cart->books()->attach($book, ['count' => random_int(1, 3)]);
            }
            $cart->total_price = $this->countTotalPrice($cart);
            $cart->save();
        }
    }
}
