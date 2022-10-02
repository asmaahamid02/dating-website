<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use App\Models\User;
use App\Models\UserProfile;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{
    use ResponseJson;
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getUsers()
    {
        $id = auth()->id();
        $user = User::find($id)->first();

        $interested_in = [$user->interested_in];
        if ($user->interested_in == 'both') {
            $interested_in = ['female', 'male'];
        }

        //get the users
        $all_users =
            User::whereIn('gender',  $interested_in)
            ->where('is_visible', 1)
            ->whereNotIn('id', $this->getMatchedIDs($id))
            ->orderBy('country')
            ->orderBy('city')
            ->get();

        if ($all_users->isNotEmpty())
            return $this->jsonResponse($all_users, 'data', Response::HTTP_OK);

        return $this->jsonResponse('Users not found', 'message', Response::HTTP_NOT_FOUND);
    }

    public function show($id)
    {
        $user = User::where('id', $id)
            ->where('is_visible', 1)
            ->with('profile')
            ->first();

        if ($user)
            return $this->jsonResponse($user, 'data', Response::HTTP_OK);
        return $this->jsonResponse('User not found', 'message', Response::HTTP_NOT_FOUND);
    }

    public function update(Request $request)
    {
        $id = Auth::id();
        $validator = Validator::make($request->all(), [
            'nickname' => 'min:5',
            'age' => 'numeric',
            'bio' => 'max:150',
            'birthdate' => 'date',
            'is_visible' => 'required',
            'gender' => 'required',
            'interested_in' => 'required',
            'country' => 'required',
            'city' => 'required',
            'is_visible' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse($validator->errors(), 'message', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::find($id);
        if ($user) {
            $user_data_to_update = [];
            $user_profile_data = [];

            //collect the user data
            $user_data_to_update['gender'] = $request->gender;
            $user_data_to_update['interested_in'] = $request->interested_in;
            $user_data_to_update['country'] = $request->country;
            $user_data_to_update['city'] = $request->city;
            $user_data_to_update['is_visible'] = $request->is_visible;

            $user->update($user_data_to_update);

            //collect the user profile data            
            if ($request->has('nickname')) {
                $user_profile_data['nickname'] = $request->nickname;
            }
            if ($request->has('bio')) {
                $user_profile_data['bio'] = $request->bio;
            }
            if ($request->has('birthdate')) {
                $user_profile_data['birthdate'] = $request->birthdate;
            }
            if ($request->has('age')) {
                $user_profile_data['age'] = $request->age;
            }
            if ($request->has('profile_picture')) {
                $base64_image = $request->profile_picture;

                //split the base64URL from the image data
                @list($type, $file_data) = explode(';', $base64_image);
                //get the file extension
                @list(, $extension) = explode('/', $type);
                //get the file data
                @list(, $file_data) = explode(',', $file_data);
                //specify the full image name
                $imageName = rand(100000, 999999) . time() . '.' . $extension;
                //add it to the array
                $user_profile_data['profile_picture'] =  $imageName;
            }
            if (count($user_profile_data) > 0) {
                $user_profile = UserProfile::where('user_id', $id)->first();
                if ($user_profile) {
                    $user_profile->update($user_profile_data);
                } else {
                    $user_profile_data['user_id'] = $id;
                    $user_profile = UserProfile::create($user_profile_data);
                }

                if ($request->has('profile_picture'))
                    //move the image to the directory
                    Storage::disk('public')->put('users/profiles/' . $imageName, base64_decode($file_data));
            }

            return $this->jsonResponse('Profile Updated Successfully', 'data', Response::HTTP_OK);
        }
        return $this->jsonResponse('User not found', 'message', Response::HTTP_NOT_FOUND);
    }

    public function collectionToArray($collection, $attribute)
    {
        $array = [];

        foreach ($collection as $row) {
            $array[] = $row->$attribute;
        }

        return $array;
    }

    public function getMatchedIDs($user_id)
    {
        //get the users who are blocked by the logged in user
        $blockedUsersData = BlockedUser::where('user_id', $user_id)->get('blocked_user_id');
        $blockedUsersIDs = $this->collectionToArray($blockedUsersData, 'blocked_user_id');

        //get the users who blocked the logged in user
        $userBlockedByByData = BlockedUser::where('blocked_user_id', $user_id)->get('user_id');
        $blockedUsersByIDs = $this->collectionToArray($userBlockedByByData, 'user_id');

        //merge the blocked users by auth, the users who blocked auth, and the auth ids into one array
        return array_merge($blockedUsersIDs, $blockedUsersByIDs, [$user_id]);
    }
}