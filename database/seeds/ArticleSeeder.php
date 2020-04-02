<?php

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Article::class, 100)->create()->each(function ($article) {
            $article->slug = Str::slug($article->id . '-' . $article->title);
            return $article->save();
        });

        factory(Article::class)->create([
            'user_id' => 1,
            'title' => 'A bird in hand is worth two in the bush.',
            'created_at' => now(),
            'updated_at' => now()
        ])->each(function ($article) {
            $article->slug = Str::slug($article->id . '-' . $article->title);
            return $article->save();
        });
    }
}
