<?php

namespace App\Observers;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleObserver
{
    public function creating(Article $article)
    {
        if (Auth::check()) {
            $article->author_id = Auth::id();
        }
    }
}
