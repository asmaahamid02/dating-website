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

    public function getMessages($receiver_id)
    {
        // $sender_id = auth()->id();
        $sender_id = Auth::id();
        $recipient = User::where('id', $receiver_id)->where('is_visible', 1)->first();

        if ($recipient) {
            //get the users that match with sender and receiver
            //then get those who $this user sends them messages (sender relation)
            //then get the messages from the other user sent to $this user (receiver relation)
            $messages = User::where('id', $receiver_id)->orWhere('id', $sender_id)->with('profile')->with('sender', function ($q) use ($sender_id) {
                $q->where('sender_id', $sender_id);
            })->with('receiver', function ($q) use ($receiver_id) {
                $q->where('receiver_id', $receiver_id);
            })->get();

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