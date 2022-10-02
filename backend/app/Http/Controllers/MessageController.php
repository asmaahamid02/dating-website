<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    use ResponseJson;
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getMessages($sender_id, $receiver_id)
    {
        // $sender_id = auth()->id();

        $sender = User::where('id', $sender_id)->where('is_visible', 1)->first();
        $recipient = User::where('id', $receiver_id)->where('is_visible', 1)->first();

        if ($recipient && $sender) {
            $messages = Message::where('sender_id', $sender_id)
                ->where('receiver_id', $receiver_id)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($messages->isNotEmpty())
                return $this->jsonResponse($messages, 'data', Response::HTTP_OK);

            return $this->jsonResponse(null, 'data', Response::HTTP_NOT_FOUND, 'No Messages');
        }
        return $this->jsonResponse(null, 'data', Response::HTTP_NOT_FOUND, 'Users not found');
    }

    public function sendMessage(Request $request, $receiver_id)
    {

        $validator = Validator::make($request->all(), [
            'message' => 'required|min:1'
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse($validator->errors(), 'data', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $sender_id = Auth::id();

        $message = $request->message;
        $recipient = User::where('id', $receiver_id)->where('is_visible', 1)->first();

        if ($recipient) {
            Message::create([
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'message' => $message
            ]);

            return $this->jsonResponse($message, 'data', Response::HTTP_OK);
        }

        return $this->jsonResponse(null, 'data', Response::HTTP_NOT_FOUND, 'User not found');
    }
}