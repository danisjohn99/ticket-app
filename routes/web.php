<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
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
    return view('welcome');
});

Auth::routes();

Route::get('home', [HomeController::class, 'index'])->name('home');

//USER ROUTES
Route::get('users-list', [UserController::class, 'users'])->middleware('can:isAdmin')->name('users.list');
Route::get('users-list-api', [UserController::class, 'usersApi'])->middleware('can:isAdmin')->name('users.list.api');
Route::post('create-user', [UserController::class, 'createUser'])->middleware('can:isAdmin')->name('create.user');

//TICKET ROUTES
Route::post('store-ticket', [TicketController::class, 'storeTicket'])->name('store.ticket');
Route::patch('update/ticket', [TicketController::class, 'updateTicket'])->name('update.ticket');
Route::get('user/tickets', [TicketController::class, 'ticketList'])->name('ticket.list');
Route::get('view/ticket/{ticketid}', [TicketController::class, 'viewTicket'])->name('view.ticket');
Route::post('store-ticket-comment', [TicketController::class, 'storeTicketComment'])->name('store.ticket.comment');
Route::delete('delete/ticket/{ticketid}', [TicketController::class, 'deleteTicket'])->name('delete.ticket');
Route::patch('restore/ticket', [TicketController::class, 'restoreTicket'])->name('restore.ticket');
