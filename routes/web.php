<?php

use Illuminate\Support\Facades\Route;

// Use fallback() instead of /{any?} catch-all.
// Route::fallback() ONLY matches when NO other route (including API routes) matches.
// This ensures API routes at /api/v1/... are never intercepted.
Route::fallback(function () {
    return view('welcome');
});
