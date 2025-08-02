<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Rota raiz
Route::get('/', function () {return view('welcome');});

// Rota da dashboard
Route::get('/dashboard', [UserController::class, 'index'])->name('timeline')->middleware('auth');

// Rota de perfil
Route::get('/profile/{id}', [UserController::class, 'show'])->name('profile')->middleware('auth');

// Rota de criação
Route::get('/create', [UserController::class, 'create'])->name('create')->middleware('auth');

// Rota de armazenamento
Route::post('/store', [UserController::class, 'store'])->name('store')->middleware('auth');

// editar perfil
Route::get('/edit', [UserController::class, 'editProfile'])->name('edit-profile')->middleware('auth');

//salvar edições do profile
Route::put('/profile/{id}', [UserController::class, 'updateProfile'])->name('profile.update')->middleware('auth');

//acicionar amigo
Route::post('/add-friend/{friend}', [UserController::class, 'addFriend'])->name('user.addFriend')->middleware('auth');

//cancelar solicitação de amizade
Route::post('/friend-request/{friend}/cancel', [UserController::class, 'cancel'])->name('friendRequest.cancel')->middleware('auth');

// Aceitar solicitação de amizade
Route::post('/accept-friend-request/{friend}', [UserController::class, 'acceptFriendRequest'])
    ->name('user.acceptFriendRequest')
    ->middleware('auth');

// Recusar solicitação de amizade
Route::post('/decline-friend-request/{friend}', [UserController::class, 'declineFriendRequest'])
    ->name('user.declineFriendRequest')
    ->middleware('auth');

//nova postagem
Route::post('/posts', [UserController::class, 'store'])->name('posts.store')->middleware('auth');

//comentario na postagem
Route::post('/comments', [UserController::class, 'storeComment'])->name('comments.store')->middleware('auth');

//mensagens
Route::get('/messages', [UserController::class, 'showMessages'])->name('user.messages')->middleware('auth');

Route::get('/messages/send', [UserController::class, 'sendMessage'])->name('user.messages.send')->middleware('auth');

//apagar postagem
Route::delete('/posts/{post}', [UserController::class, 'destroy'])->name('posts.destroy');

// Rota para exibir postagens com fotos
Route::get('/profile/{id}/photos', [UserController::class, 'showPhotos'])->name('profile.photos');

// Rota para exibir postagens com vídeos
Route::get('/profile/{id}/videos', [UserController::class, 'showVideos'])->name('profile.videos');




