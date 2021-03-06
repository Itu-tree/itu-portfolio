<?php

namespace App\Http\Controllers;

use App\Article;
use App\Tag;
use App\Usecases\Tag\UpdateArticleTags;
use Illuminate\Http\Request;
use League\HTMLToMarkdown\HtmlConverter;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::inStatus(['public'])->latest('updated_at')->get();
        return view('guest.article.index', ['articles' => $articles]);
    }

    public function show(Article $article)
    {
        $articles = Article::inStatus(['public'])->latest('updated_at')->limit(5)->get();
        $tags = Tag::inStatus(['public'])->get();
        return view('guest.article.show', ['article' => $article, 'articles' => $articles, 'tags' => $tags]);
    }

    public function showAdmin(Article $article)
    {
        $articles = Article::latest('updated_at')->limit(5)->get();
        $tags = Tag::inStatus(['public', 'draft'])->get();
        return view('guest.article.show', ['article' => $article, 'articles' => $articles, 'tags' => $tags]);
    }

    public function manage()
    {
        $articles = Article::latest('updated_at')->get();
        return view('guest.article.index', ['articles' => $articles, 'article_state' => 'all']);
    }

    public function managePublic()
    {
        $articles = Article::inStatus(['public'])->latest('updated_at')->get();
        return view('guest.article.index', ['articles' => $articles, 'article_state' => 'public']);
    }

    public function manageDraft(Article $article)
    {
        $articles = Article::inStatus(['draft'])->latest('updated_at')->get();
        return view('guest.article.index', ['articles' => $articles, 'article_state' => 'draft']);
    }

    public function create()
    {
        $article = Article::init();
        return view('auth.article.edit', ['article' => $article, 'tags' => Tag::all()]);
    }

    public function edit(Article $article)
    {
        return view('auth.article.edit', ['article' => $article, 'tags' => Tag::all()]);
    }

    public function update(Request $request, Article $article, UpdateArticleTags $updateTag)
    {
        $converter = new HtmlConverter();
        $article->fill($request->only('title', 'body', 'state') + ['mdbody' => $converter->convert($request->body)])->save();
        $updateTag($article->id, $article->tags()->pluck('name')->toArray(), $request->tags[0] == null ? [] : $request->tags);
        return redirect(route('admin.article.manage'));
    }

    public function delete(Article $article)
    {
        if ($article->state == "draft") {
            $article->delete();
        }
        return redirect(route('admin.article.manage-draft'));
    }
}
