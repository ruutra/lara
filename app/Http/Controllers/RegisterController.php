<?php

namespace App\Http\Controllers;

use App\Models\Access;
use App\Models\User;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    public function show(){
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        auth()->login($user);
        (new Access())->enable($user->id, $user->id);

        return redirect('/')
            ->with('success', 'Успешная регистрация!');
    }
}
