<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    use ResponseJson;

    public function getMessages($receiver_id)
    {
        $sender_id = Auth::id();

        $messages = Message::where('sender_id', $sender_id)
            ->where('receiver_id', $receiver_id)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($messages->isNotEmpty())
            return $this->jsonResponse($messages, 'data', Response::HTTP_OK);

        return $this->jsonResponse(null, 'data', Response::HTTP_NOT_FOUND, 'No Messages');
    }
}