<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use App\Models\User;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BlockedUserController extends Controller
{
    use ResponseJson;

    public function getBlockedUsers()
    {
        $blocked_users = Auth::user()->blocking;

        if ($blocked_users->isNotEmpty())
            return $this->jsonResponse($blocked_users, 'data', Response::HTTP_OK);

        return $this->jsonResponse('No Blocked Users', 'message', Response::HTTP_NOT_FOUND);
    }

    public function blockUser($blocked_user_id)
    {
        //get the user who will added to blocked list
        $user = User::where('id', $blocked_user_id)->where('is_visible', 1)->first();

        if ($user) {
            //check if there is a relation previousely
            if (Auth::user()->blocking->contains($blocked_user_id))
                //remove it
                Auth::user()->blocking()->detach($blocked_user_id);
            else
                //add it
                Auth::user()->blocking()->attach($blocked_user_id);

            return $this->jsonResponse('Updated Successfully', 'message', Response::HTTP_OK);
        }
        return $this->jsonResponse('User not found', 'message', Response::HTTP_NOT_FOUND);
    }
}