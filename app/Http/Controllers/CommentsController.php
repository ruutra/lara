<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    /**
     * @param int $userId
     * @param Request $request
     * @return Application|Factory|View
     */
    public function getComments(int $userId, Request $request)
    {
        $comments = (new Comment())->getComments($userId);
        if (empty($comments)) {
            $comments = (new Comment())->getComments($userId);
        }
        return view('home.comments')->with('comments', $comments);
    }

    /**
     * @param int $userId
     * @param Request $request
     * @return Application|Factory|View
     */
    public function getAllComments(int $userId, Request $request)
    {
        $comments = (new Comment())->getComments($userId, null);
        return view('home.comments')->with('comments', $comments);
    }

    /**
     * @param int $userId
     * @param Request $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function addComment(int $userId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }
        if(Auth::check()){
        /** @var User $user */
        $user = Auth::user();
        (new Comment())->addComment($request->get('text'), $userId, $user);
        return redirect(route('comment.get', ['id' => $userId]));
        }
    }

    /**
     * @param int $userId
     * @param int $parentId
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy(int $userId,Request $request)
    {
        $id = (int) $request->get('id');
        (new Comment())->deleteComment($userId, $id, Auth::user());
        return redirect(route('comment.get', ['id' => $userId]));
    }

   public function replyComments(int $userId, Request $request)
   {
       (new Comment())->replyComment($request->get('text'), $userId, $request->get('id'), Auth::user());
       return redirect(route('comment.get', ['id' => $userId]));
   }
}
