<?php

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// all listings
Route::get('/', [ListingController::class, 'index']);

// show create listing form
Route::get('/listings/create', [ListingController::class,'create'])->middleware('auth');

// store listing data
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

// show edit form
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');

// update listing
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

// delete listing
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

//manage Listings
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

// single listing
Route::get('/listings/{listing}', [ListingController::class, 'show']);

//show registration form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

// create new user
ROute::post('/users', [UserController::class, 'store']);

//log out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// show login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// login user
ROute::post('/users/authenticate', [UserController::class, 'authenticate']);


// Common resources routes
// index - Show all listings
// show - Show single listing
// create - Show form to create listing
// store - Store(create) new listing
// edit - Show form to edit listing
// update - Update listing
// destoroy - Destroy(Delete) listing
