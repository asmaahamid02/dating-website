<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class userController extends Controller
{
    use ResponseJson;
    public function index()
    {
        $users = User::all();

        if ($users->isNotEmpty())
            return $this->jsonResponse($users, 'data', Response::HTTP_OK);

        return $this->jsonResponse('Request not found', 'error', Response::HTTP_NOT_FOUND);
    }

    public function show($id)
    {
        $user = User::find($id);

        if ($user)
            return $this->jsonResponse($user, 'data', Response::HTTP_OK);

        return $this->jsonResponse('Request not found', 'error', Response::HTTP_NOT_FOUND);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function delete($id)
    {
        //
    }
}