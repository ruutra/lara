<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user instanceof User) {
            return redirect(route('comment.get', ['id' => $user->id]));
        }

        return view('home.index');
    }
}
