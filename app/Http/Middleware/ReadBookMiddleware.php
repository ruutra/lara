<?php

namespace App\Http\Middleware;

use App\Models\Book;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ReadBookMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request,Closure $next)
    {
        $bookId = $request->route('id');
        $book = (new Book())->getBook($bookId);
        if ($book->public) {
            return $next($request);
        }

        $user = Auth::user();
        if (!$user) {
            return response([], 403);
        }

        if (!(new Book())->isAccess($user->id,$bookId)) {
            return response([], 403);
        }

        return $next($request);
    }
}
