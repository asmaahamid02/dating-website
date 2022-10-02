<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use ResponseJson;
    public function index()
    {
        $users = User::where('is_visible', 1)->with('profile')->get();

        if ($users->isNotEmpty())
            return $this->jsonResponse($users, 'data', Response::HTTP_OK);

        return $this->jsonResponse('Request not found', 'error', Response::HTTP_NOT_FOUND);
    }

    public function createProfile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nickname' => 'min:5',
            'age' => 'numeric',
            'bio' => 'max:150',
            'birthdate' => 'date',
            'is_visible' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse($validator->errors(), 'data', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::find($id);
        if ($user) {
            $data_to_update = [];
            $data_to_update['is_visible'] = $request->is_visible;
            if ($request->has('nickname')) {
                $data_to_update['nickname'] = $request->nickname;
            }
            if ($request->has('bio')) {
                $data_to_update['bio'] = $request->bio;
            }
            if ($request->has('birthdate')) {
                $data_to_update['birthdate'] = $request->birthdate;
            }
            if ($request->has('age')) {
                $data_to_update['age'] = $request->age;
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
                $data_to_update['profile_picture'] =  $imageName;
            }

            if (count($data_to_update) > 0) {
                $data_to_update['user_id'] = $id;
                $user_profile = UserProfile::create($data_to_update);
                if ($user_profile) {
                    if ($request->has('profile_picture'))
                        //move the image to the directory
                        Storage::disk('public')->put('users/profiles/' . $imageName, base64_decode($file_data));

                    return $this->jsonResponse($user_profile, 'data', Response::HTTP_OK, 'Profile Created Successfully');
                }
            }
            return $this->jsonResponse('', 'data', Response::HTTP_UNPROCESSABLE_ENTITY, 'Nothing to update');
        }
        return $this->jsonResponse('Request not found', 'error', Response::HTTP_NOT_FOUND, 'User not found');
    }

    public function show($id)
    {
        $user = User::find($id);

        if ($user)
            return $this->jsonResponse($user, 'data', Response::HTTP_OK);

        return $this->jsonResponse('Request not found', 'error', Response::HTTP_NOT_FOUND);
    }
}