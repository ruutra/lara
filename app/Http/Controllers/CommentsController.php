<?php

namespace App\Http\Controllers;

use App\Models\Comments;
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
     * @return Application|Factory|View
     */
    public function getAllComments(int $userId, Request $request)
    {
        $comments = (new Comments())->getComments($userId, null);
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

        /** @var User $user */
        $user = Auth::user();
        (new Comments())->addComment($request->get('text'), $userId, $user);
        return redirect('/' . $userId);
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
        (new Comments())->deleteComment($userId, $id, Auth::user());
        return redirect('/' . $userId);
    }

   public function replyComments(int $userId, Request $request)
   {
       (new Comments())->replyComment($request->get('text'), $userId, $request->get('id'), Auth::user());
       return redirect('/' . $userId);
   }
}
