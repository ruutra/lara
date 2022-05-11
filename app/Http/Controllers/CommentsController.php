<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    public function getComments(Request $request)
    {
        $comments = (new Comments())->getComments(Auth::user());
        return view('home.comments')->with('comments', $comments);
    }

    public function addComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        /** @var User $user */
        $user = Auth::user();
        (new Comments())->addComment($request->get('text'), $user);

        $profile = $request->get('profile') ?? $user->id;
        return redirect('/' . $profile);
    }
    public function deleteUserComment(Request $request)
    {
        $id = $request->input('id');
        $deleted = DB::delete('delete from `laravel`.`comments` where `id` = ? and `user_id` = ?');
        redirect('/');
    }
}
