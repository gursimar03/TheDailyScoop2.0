<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group([
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('/logout',  [AuthController::class, 'logout']);

    Route::get('/user',  function (Request $request) {
                                return $request->user();
                         });
    Route::get("like/{id}", function(Request $request, $id) {
        $request->user()->likes()->toggle($id);
    });
});


Route::get("/articles", function() {
    return \App\Models\Article::all();
});

Route::get("/article/{id}", function($id) {
    return \App\Models\Article::findOrFail($id);
});

Route::post('/article', function(Request $request) {
    
    $article = new \App\Models\Article();
    $article->title = $request->title;
    $article->body = $request->body;
    $article->save();
    return $article;
});

Route::get('toparticles', function() {
    
    $articles = \App\Models\Article::all();
    $topArticles = [];
    foreach($articles as $article) {
        $topArticles[$article->id] = $article->likes()->count();
    }
    arsort($topArticles);
    $topArticles = array_slice($topArticles, 0, 10, true);
    $topArticles = array_keys($topArticles);
    $topArticles = \App\Models\Article::find($topArticles);
    return $topArticles;
});


Route::get("/countarticlelikes/{id}", function(Request $request, $id) {
    return \App\Models\Article::findOrFail($id)->likes()->count();
});

