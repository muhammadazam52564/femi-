<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\PushNotification;
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home',                         [HomeController::class, 'index'])->name('home');
Route::get('/cron-job-notifications',       [MainController::class,     'cron_job_notifications']);


Route::get('/list', function () {
    return PushNotification::all();
});

Route::group(['prefix'=> 'admin', 'middleware'=>['isAdmin', 'auth', 'PreventBackHistory']], function()
{
    // Route::get('/orders',               [MainController::class, 'orders'])              ->name('admin.orders');
    // Route::get('/single/{id}',          [MainController::class, 'order_details'])       ->name('admin.get-single-order');
    // Route::get('/order/{id}',           [MainController::class, 'order'])               ->name('admin.order');
    Route::get('/dashboard',            [AdminController::class, 'index'])              ->name('admin.dashboard');
    
    Route::get('/users',                [MainController::class, 'customers'])           ->name('admin.users');
    Route::get('/u-st/{id}/{status}',   [MainController::class, 'user_status'])         ->name('admin.u-st');
    Route::get('/delete-user/{id}',     [MainController::class, 'delete_user'])         ->name('admin.delete-user');

    Route::get('/blogs',                [MainController::class, 'blogs'])               ->name('admin.blogs');
    Route::view('add-blog',             'admin.addBlog')                                ->name('admin.add-blog');
    Route::post('/add-blog',            [MainController::class, 'SaveBlog'])            ->name('admin.add-fact');
    Route::get('/edit-blog/{id}',       [MainController::class, 'EditBlog'])            ->name('admin.edit-blog');
    Route::post('/update-blog/{id}',    [MainController::class, 'UpdateBlog'])          ->name('admin.update-blog');
    Route::get('/delete-blog/{id}',     [MainController::class, 'deleteBlog'])          ->name('admin.delete-blog');
    
    Route::get('/facts',                [MainController::class, 'facts'])               ->name('admin.facts');
    Route::view('add-fact',             'admin.addFact')                                ->name('admin.add-fact');
    Route::post('/add-fact',            [MainController::class, 'SaveFact'])            ->name('admin.add-fact');
    Route::get('/edit-fact/{id}',       [MainController::class, 'EditFact'])            ->name('admin.edit-fact');
    Route::post('/update-fact/{id}',    [MainController::class, 'UpdateFact'])          ->name('admin.update-fact');
    Route::get('/delete-fact/{id}',     [MainController::class, 'deleteFact'])          ->name('admin.delete-fact');

    Route::get('/text-days',            [MainController::class, 'textDay'])              ->name('admin.text-day');
    Route::view('add-text-day',         'admin.addText')                                 ->name('admin.add-text-day');
    Route::post('/add-text-day',        [MainController::class, 'SaveTextDay'])          ->name('admin.add-text-day');
    Route::get('/edit-text-day/{id}',   [MainController::class, 'EditTextDay'])          ->name('admin.edit-text-day');
    Route::post('/update-text-day/{id}',[MainController::class, 'UpdateTextDay'])        ->name('admin.update-text-day');
    Route::get('/delete-text-day/{id}', [MainController::class, 'deleteTextDay'])        ->name('admin.delete-text-day');

    Route::get('/products',             [MainController::class, 'products'])            ->name('admin.products');
    Route::get('/add-product',          [MainController::class, 'new_product'])         ->name('admin.add-product');
    Route::post('/adds-product',        [MainController::class, 'add_product'])         ->name('admin.adds-product');
    Route::get('/delete-prod/{id}',     [MainController::class, 'deleteProduct'])       ->name('admin.delete-prod');
    Route::get('/orders',               [MainController::class, 'orders'])              ->name('admin.orders');
    Route::get('/orders/show/{orderId}',[MainController::class, 'showOrder'])           ->name('admin.showOrder');

    Route::get('/scads',                [MainController::class, 'scads'])               ->name('admin.scads');
    Route::view('add-scad',             'admin.addScad')                                ->name('admin.add-scad');
    Route::post('/add-scad',            [MainController::class, 'SaveScad'])            ->name('admin.add-scad');
    Route::get('/edit-scad/{id}',       [MainController::class, 'EditScad'])            ->name('admin.edit-scad');
    Route::post('/update-scad/{id}',    [MainController::class, 'UpdateScad'])          ->name('admin.update-scad');
    Route::get('/delete-scad/{id}',     [MainController::class, 'deleteScad'])          ->name('admin.delete-scad');
    
    Route::get('/appointments',         [MainController::class, 'appointments'])        ->name('admin.appointments');
    Route::get('/delete-appointment/{id}',[MainController::class, 'deleteAppointment'])   ->name('admin.delete-appointment');

    Route::view('/profile',             'admin.profile')                                ->name('admin.profile');
    Route::post('/profile',             [AdminController::class, 'profile'])            ->name('admin.profile');
    Route::get('/change-password',      [AdminController::class, 'change_password'])    ->name('admin.change-password');
    Route::post('/change-password',     [AdminController::class, 'update_password'])    ->name('admin.change-password');
    Route::get('/change-email',         [AdminController::class, 'change_email'])       ->name('admin.change-email');
});





Route::group(['prefix'=> 'agent', 'middleware'=>['isAgent', 'auth', 'PreventBackHistory']], function()
{
    Route::get('/dashboard', [AgentController::class, 'index'])->name('agent.dashboard');
});
Route::group(['prefix'=> 'user', 'middleware'=>['isUser', 'auth', 'PreventBackHistory']], function()
{
    Route::get('dashboard', [UserController::class, 'index'])->name('user.dashboard');
});
Route::group(['prefix'=> 'rider', 'middleware'=>['isRider', 'auth', 'PreventBackHistory']], function()
{
    Route::get('dashboard', [RiderController::class, 'index'])->name('rider.dashboard');
});
