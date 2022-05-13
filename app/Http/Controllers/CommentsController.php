<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    /**
     * @param int $userId
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getComments(int $userId, Request $request)
    {
        $comments = (new Comments())->getComments($userId);
        if (empty($comments)) {
            $comments = (new Comments())->getComments($userId);
            return view('home.comments')->with('comments', $comments);
        }
        return view('home.comments')->with('comments', $comments);
    }

    /**
     * @param int $userId
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addComment(int $userId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        /** @var User $user */
        $user = Auth::user();
        (new Comments())->addComment($request->get('text'), $userId, $user);
        return redirect('/' . $userId);
    }

    /**
     * @param int $userId
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(int $userId, Request $request)
    {
        $id = (int) $request->get('id');
        (new Comments())->deleteComment($userId, $id, Auth::user());
        return redirect('/' . $userId);
    }

   public function replyComments(int $userId, Request $request)
   {
       (new Comments())->replyComment($request->get('text'), $userId, $request->get('id'), Auth::user());
       return redirect('/' . $userId);
   }
}
