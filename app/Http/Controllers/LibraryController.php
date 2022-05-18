<?php

namespace App\Http\Controllers;

use App\Models\Access;
use App\Models\Book;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    /**
     * @param int $userId
     * @param Request $request
     * @return Application|ResponseFactory|RedirectResponse|Response|Redirector
     */
    public function addBook(int $userId, Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($userId !== $user->id){
            return response([], 403);
        }

        $name = $request->get('name');
        $text = $request->get('text');

        (new Book())->addBook($user->id, $name, $text);
        return redirect(route('library.get', ['id' => $userId]));
    }

    /**
     * @param int $userId
     * @return Application|ResponseFactory|Factory|View|Response
     */
    public function getLibrary(int $userId)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return response([], 403);
        }

        $access = new Access();
        $isAccess = $access->isAccess($userId, $user->id);
        if (!$access->isAccess($user->id, $userId)) {
            return view('layouts.partials.library')
                ->with('access', $isAccess);
        }

        $books = (new Book())->getLibrary($userId);
        return view('layouts.partials.library')
            ->with('books', $books)
            ->with('access', $isAccess);
    }

    /**
     * @param int $bookId
     * @return bool
     * @throws Exception
     */
    public function togglePublic(int $bookId): bool
    {
        /** @var User $user */
        $user = Auth::user();
        return (new Book())->togglePublic($user->id, $bookId);
    }

    /**
     * @param int $bookId
     * @return Application|Factory|View
     */
    public function readBook(int $bookId)
    {
        $book = (new Book())->getBook($bookId);
        if ($book->public) {
            return view('layouts.partials.book')
                ->with('book', $book);
        }

        $user = Auth::user();
        if (!$user) {
            return response([], 403);
        }
        if (!(new Book())->isAccess($user->id, $bookId)) {
            return response([], 403);
        }

        return view('layouts.partials.book')
            ->with('book', $book);
    }

    /**
     * @param int $userId
     * @return Application|RedirectResponse|Redirector
     */
    public function enableAccess(int $userId)
    {
        /** @var User $user */
        $user = Auth::user();
        (new Access())->enable($userId, $user->id);
        return redirect(route('library.get', ['id' => $userId]));
    }

    /**
     * @param int $userId
     * @return Application|RedirectResponse|Redirector
     */
    public function disableAccess(int $userId)
    {
        /** @var User $user */
        $user = Auth::user();
        (new Access())->disable($userId, $user->id);
        return redirect(route('library.get', ['id' => $userId]));
    }

    /**
     * @param int $bookId
     * @return Application|RedirectResponse|Redirector
     */
    public function deleteBook(int $bookId)
    {
        /** @var User $user */
        $user = Auth::user();
        (new Book())->deleteBook($bookId, $user->id);
        return redirect(route('library.get', ['id' => $user->id]));
    }

    /**
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function editBook(Request $request)
    {
        $user = Auth::user();
        $id = $request->get('id');
        $text = $request->get('text');

        (new Book())->editBook($id, $user->id, $text);
        return redirect(route('book.read', ['id' => $id]));
    }
}
