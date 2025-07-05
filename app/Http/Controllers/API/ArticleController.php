<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $articles = Article::with(['category', 'author'])->get(); // eager load relationships

            if ($articles->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No articles found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Articles retrieved successfully',
                'data' => $articles
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'category_id' => 'required|integer|exists:categories,id',
                'article_title' => 'required|string|max:255|unique:articles',
                'article_slug' => 'required|string|max:255|unique:articles',
                'article_description' => 'required|string',
                'article_status' => 'required|in:published,draft',
                'article_image' => 'nullable|string',
            ], [
                'category_id.required' => 'Category is required',
                'article_title.required' => 'Article title is required',
                'article_title.unique' => 'Article title already exists',
                'article_slug.required' => 'Article slug is required',
                'article_slug.unique' => 'Article slug already exists',
                'article_status.required' => 'Article status is required',
                'article_status.in' => 'Article status must be published or draft',
            ]);

            $article = Article::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Article created successfully',
                'data' => $article
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $article = Article::with(['category', 'author'])->find($id);

            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => "Article not found with ID: $id"
                ], 404);
            }

            $missingRelations = [];
            if (!$article->category) {
                $missingRelations['category'] = 'Missing related category';
            }
            if (!$article->author) {
                $missingRelations['author'] = 'Missing related author';
            }

            return response()->json([
                'success' => true,
                'message' => 'Article retrieved successfully',
                'data' => $article,
                'missing_relations' => $missingRelations,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve article',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'category_id' => 'sometimes|integer|exists:categories,id',
                'article_title' => 'sometimes|string|max:255|unique:articles,article_title,' . $article->id,
                'article_slug' => 'sometimes|string|max:255|unique:articles,article_slug,' . $article->id,
                'article_description' => 'sometimes|string|nullable',
                'article_status' => 'sometimes|in:published,draft',
                'article_image' => 'nullable|string',
                'author_id' => 'sometimes|integer|exists:users,id',
            ], [
                'article_title.unique' => 'Article title already exists',
                'article_slug.unique' => 'Article slug already exists',
                'article_status.in' => 'Article status must be published or draft'
            ]);

            $article->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Article updated successfully',
                'data' => $article
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $article = Article::find($id);

            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article not found',
                ], 404);
            }

            $article->delete();

            return response()->json([
                'success' => true,
                'message' => 'Article deleted successfully',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete article',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function getByCategory($categoryId): JsonResponse
    {
        try {
            $articles = Article::where('category_id', $categoryId)
                ->with(['category', 'author']) // optional, but recommended
                ->get();

            if ($articles->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No articles found for category ID: ' . $categoryId
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Articles retrieved successfully by category ID: ' . $categoryId,
                'data' => $articles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve articles by category',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getByAuthor(int $authorId): JsonResponse
    {
        try {
            $articles = Article::where('author_id', $authorId)
                ->with('category')
                ->get();

            if ($articles->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No articles found for author ID: ' . $authorId
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Articles retrieved successfully for author ID: ' . $authorId,
                'data' => $articles
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve articles for author',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
