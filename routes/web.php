<?php
use App\Http\Controllers\CampaignsController;
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

Route::get('/', function () {
    return redirect('campaigns');
});
Route::get('campaigns', [CampaignsController::class, 'index'])
    ->name('campaigns');

Route::get('campaigns/create', [CampaignsController::class, 'create'])
    ->name('campaigns.create');

Route::post('campaigns', [CampaignsController::class, 'store'])
    ->name('campaigns.store');

Route::get('campaigns/{campaign}/edit', [CampaignsController::class, 'edit'])
    ->name('campaigns.edit');

Route::put('campaigns/{campaign}', [CampaignsController::class, 'update'])
    ->name('campaigns.update');

Route::delete('campaigns/{campaign}', [CampaignsController::class, 'destroy'])
    ->name('campaigns.destroy');

