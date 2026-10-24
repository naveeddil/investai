<?php

use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ContactSubmissionController;
use App\Http\Controllers\InsightController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\RiskExplanationController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WaitlistSignupController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');

Route::post('/contact', ContactSubmissionController::class)
    ->middleware('throttle:public-forms')
    ->name('contact.store');

Route::post('/waitlist', WaitlistSignupController::class)
    ->middleware('throttle:public-forms')
    ->name('waitlist.store');

Route::post('/tools/risk-explanation', RiskExplanationController::class)
    ->middleware('throttle:ai-explanations')
    ->name('tools.risk-explanation');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/insights', [InsightController::class, 'index'])->name('insights.index');
Route::get('/insights/{slug}', [InsightController::class, 'show'])->name('insights.show');

Route::post('/consultation', ConsultationController::class)
    ->middleware('throttle:public-forms')
    ->name('consultation.store');

Route::get('/{slug}', [PublicPageController::class, 'show'])
    ->where('slug', '^(?!admin$|api$|storage$|up$|insights$)[a-z0-9]+(?:-[a-z0-9]+)*$')
    ->name('pages.show');
