<?php

use App\Livewire\ToiletReservations;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/zachody'));
Route::livewire('/zachody', ToiletReservations::class)->name('toilet-reservations');
