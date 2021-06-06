<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

function dashboard($year) {
    $categories = \App\Models\Category::where("user_id", \Auth::user()->id)->orderBy("priority")->get();

    return view('dashboard', ["year" => $year, "categories" => $categories]);
}


Route::get('/', function () {
    if(\Auth::user() != null) {
        return redirect("/dashboard");
    }

    return view('welcome');
});

Route::get('/dashboard', function () {
    $year = intval((new \Datetime())->format("Y"));
    return dashboard($year);
})->middleware(['auth'])->name('dashboard');

Route::get('/year/{year}', function ($year) {
    $year = intval($year);
    if($year < 2000 || $year > 2200) {
        $year = intval((new \Datetime())->format("Y"));
    }
    if($year == intval((new \Datetime())->format("Y"))) {
        return redirect("/dashboard");
    }

    return dashboard($year);
})->middleware(['auth'])->name('year');

Route::get('/categories', function () {
    return view('categories');
})->middleware(['auth'])->name('categories');

require __DIR__.'/auth.php';
