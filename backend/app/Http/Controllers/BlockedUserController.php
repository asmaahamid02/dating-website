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

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getBlockedUsers()
    {
        $blocked_users = BlockedUser::where('user_id', Auth::id())
            ->with('BlockedUsers')
            ->get();

        if ($blocked_users->isNotEmpty())
            return $this->jsonResponse($blocked_users, 'data', Response::HTTP_OK);

        return $this->jsonResponse('No Blocked Users', 'message', Response::HTTP_NOT_FOUND);
    }

    public function updateBlockStatus($blocked_user_id)
    {
        //get the user who will added to blocked list
        $user = User::where('id', $blocked_user_id)->where('id', '!=', Auth::id())->first();

        if ($user) {
            //check if the user is in the block list
            $blocked = BlockedUser::where('user_id', Auth::id())
                ->where('blocked_user_id', $blocked_user_id)
                ->first();

            //if found delete it
            if ($blocked)
                //delete
                $blocked->delete();
            //else create it    
            else
                //create
                BlockedUser::create([
                    'user_id' => Auth::id(),
                    'blocked_user_id' => $blocked_user_id
                ]);


            return $this->jsonResponse('Updated Successfully', 'message', Response::HTTP_OK);
        }
        return $this->jsonResponse('User not found', 'message', Response::HTTP_NOT_FOUND);
    }
}