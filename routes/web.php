<?php

use App\Http\Controllers\PetitionController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('petitions',[PetitionController::class, 'index']);
Route::get('{id}/petition',[PetitionController::class, 'show']);
Route::get('{id}/vote',[VoteController::class]);
