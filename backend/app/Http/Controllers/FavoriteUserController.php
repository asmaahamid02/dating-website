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
        $favorite_users = FavoriteUser::where('user_id', Auth::id())
            ->with('favoriteUsers')
            ->get();

        if ($favorite_users->isNotEmpty())
            return $this->jsonResponse($favorite_users, 'data', Response::HTTP_OK);

        return $this->jsonResponse('No Favorite Users', 'message', Response::HTTP_NOT_FOUND);
    }

    public function updateFavoriteStatus($favorite_user_id)
    {
        //get the user who will added to favorites
        $user = User::where('id', $favorite_user_id)->where('id', '!=', Auth::id())->first();

        if ($user) {
            //check if the user is in the favorites list
            $favorited = FavoriteUser::where('user_id', Auth::id())
                ->where('favorite_user_id', $favorite_user_id)
                ->first();

            //if found delete it
            if ($favorited)
                //delete
                $favorited->delete();
            //else create it    
            else
                //create
                FavoriteUser::create([
                    'user_id' => Auth::id(),
                    'favorite_user_id' => $favorite_user_id
                ]);


            return $this->jsonResponse('Updated Successfully', 'message', Response::HTTP_OK);
        }
        return $this->jsonResponse('User not found', 'message', Response::HTTP_NOT_FOUND);
    }
}