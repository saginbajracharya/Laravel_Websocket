<?php
use BeyondCode\LaravelWebSockets\Dashboard\Http\Controllers\SendMessage;
use BeyondCode\LaravelWebSockets\Dashboard\Http\Controllers\AuthenticateDashboard;
use BeyondCode\LaravelWebSockets\Dashboard\Http\Controllers\ShowDashboard;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('home', function ($user, $id) {
    return 100;
});

Broadcast::channel('public-channel', function ($user, $roomId) {
    // Return true if the user is allowed to listen to this channel.
    return (int) $user->id === (int) $roomId;
});

Broadcast::channel('private-channel', function ($user) {
    // Return true if the user is authorized to listen to the private channel.
    return true;
});

Broadcast::channel('presence-channel', function ($user) {
    // Return the user data that should be available to others on the presence channel.
    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});

Broadcast::routes();

if (config('app.env') !== 'production') {
    Route::post('/broadcasting/auth', [AuthenticateDashboard::class, '__invoke']);

    Route::post('/laravel-websockets/auth', [AuthenticateDashboard::class, '__invoke']);

    Route::get('/laravel-websockets', [ShowDashboard::class, '__invoke']);

    Route::post('/laravel-websockets/send-message', [SendMessage::class, '__invoke']);
}
