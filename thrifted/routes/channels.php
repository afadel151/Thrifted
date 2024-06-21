<?php

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
Broadcast::channel('chats.{chatId}', function (Message $message, int $chatId) {
    return $message->chat_id === Chat::findOrNew($chatId)->id;
});