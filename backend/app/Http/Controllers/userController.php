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
    }

    public function show($id)
    {
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