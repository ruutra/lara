<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param string $username
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getUserName(string $users,Request $request)
    {
       (new User())->getUserName($request->get('username'));
        return view('layouts.partials.navbar')->with($users);
    }

    public function getUsers(Request $request)
    {
        $users = (new User())->getUsers();
        return view('home.users')->with('users', $users);
    }
}

