<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\VisitController;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/change-language/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ar'])) {
        Session::put('locale', $lang);
        App::setLocale($lang);
    }
    return redirect()->back();
})->name('change-language');

Route::get('/admin/visits/{visit}/rate', [VisitController::class, 'showRateForm'])->name('admin.visits.rate');
Route::post('/admin/visits/{visit}/rate', [VisitController::class, 'submitRateForm'])->name('admin.visits.submitRate');
//Route::get('/visits/{record}', [\App\Filament\Client\Resources\VisitResource\Pages\ViewVisit::class, 'view'])->name('filament.visit-resource.visits.view');
