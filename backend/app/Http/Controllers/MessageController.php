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

    public function getMessages($sender_id, $receiver_id)
    {
        // $sender_id = auth()->id();
        $sender = User::where('id', $sender_id)->where('is_visible', 1)->first();
        $recipient = User::where('id', $receiver_id)->where('is_visible', 1)->first();

        if ($recipient && $sender) {

            $messages = User::whereHas('sender', function ($q) use ($sender_id, $receiver_id) {
                $q->where('sender_id', $sender_id)->where('receiver_id', $receiver_id);
            })->orWhereHas('receiver', function ($q) use ($sender_id, $receiver_id) {
                $q->where('sender_id', $sender_id)->where('receiver_id', $receiver_id);
            })->with('profile')->with('sender')->get();

            if ($messages->isNotEmpty())
                return $this->jsonResponse($messages, 'data', Response::HTTP_OK);

            return $this->jsonResponse(null, 'data', Response::HTTP_NOT_FOUND, 'No Messages');
        }
        return $this->jsonResponse(null, 'data', Response::HTTP_NOT_FOUND, 'Users not found');
    }

    public function sendMessage(Request $request, $receiver_id)
    {
        //get the user to be messaged
        $user = User::where('id', $receiver_id)->where('is_visible', 1)->first();

        $validator = Validator::make($request->all(), [
            'message' => 'required|min:1'
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse($validator->errors(), 'data', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($user) {
            //add it
            Auth::user()->receiver()->attach($receiver_id, ['message' => $request->message]);

            return $this->jsonResponse('Sent Successfully', 'message', Response::HTTP_OK);
        }
        return $this->jsonResponse('User not found', 'message', Response::HTTP_NOT_FOUND);
    }
}