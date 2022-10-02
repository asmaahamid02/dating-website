<?php

namespace App\Http\Controllers;

use App\Models\FavoriteUser;
use App\Models\User;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FavoriteUserController extends Controller
{
    use ResponseJson;
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getFavoriteUsers()
    {
        $favorite_users = Auth::user()->favoriting;

        if ($favorite_users->isNotEmpty())
            return $this->jsonResponse($favorite_users, 'data', Response::HTTP_OK);

        return $this->jsonResponse('No Favorite Users', 'message', Response::HTTP_NOT_FOUND);
    }

    public function favoriteUser($favorite_user_id)
    {
        //check the user if it is existed
        $user = User::where('id', $favorite_user_id)->where('is_visible', 1)->first();

        if ($user) {
            //check if there is a relation previousely
            if (Auth::user()->favoriting->contains($favorite_user_id))
                //remove it
                Auth::user()->favoriting()->detach($favorite_user_id);
            else
                //add it
                Auth::user()->favoriting()->attach($favorite_user_id);

            return $this->jsonResponse('Updated Successfully', 'message', Response::HTTP_OK);
        }
        return $this->jsonResponse('User not found', 'message', Response::HTTP_NOT_FOUND);
    }
}