<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $text
 * @property boolean $public
 * @property int $user_id
 */
class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'text',
        'public',
        'user_id',
    ];

    /**
     * @param int $userLibraryId
     * @param $name
     * @param $text
     * @return int
     */
    public static function addBook(int $userLibraryId, $name, $text): int
    {
        $book = new self();
        $book->name = $name;
        $book->text = $text;
        $book->public = false;
        $book->user_id = $userLibraryId;
        return $book->save();
    }

    /**
     * @param int $bookId
     * @return Book
     */
    public function getBook(int $bookId): Book
    {
        return self::query()
            ->where('id', $bookId)
            ->first();
    }

    /**
     * @param int $currentUserId
     * @param int $bookId
     * @return bool
     */
    public function isAccess(int $currentUserId, int $bookId): bool
    {
        return (bool) self::query()
            ->join('access', 'books.user_id', 'access.library_id')
            ->where('books.id', $bookId)
            ->where('access.user_id', $currentUserId)
            ->first();
    }

    /**
     * @param $userId
     * @return Collection
     */
    public function getLibrary($userId): Collection
    {
        return self::query()
            ->where('user_id', $userId)
            ->get();
    }

    /**
     * @param int $bookId
     * @param int $userId
     * @return bool
     */
    public function deleteBook(int $bookId,int $userId):bool
    {
        return self::query()
            ->where([
                'id' => $bookId,
                'user_id' => $userId,
            ])
            ->delete();
    }

    /**
     * @param int $userId
     * @param int $bookId
     * @return boolean
     * @throws Exception
     */
    public function togglePublic(int $userId, int $bookId): bool
    {
        $book = self::query()
            ->where('id', $bookId)
            ->where('user_id', $userId)
            ->first();

        if (!$book instanceof Book) {
            throw new Exception('Не удалось найти запись!');
        }

        $isPublic = !$book->public;
        self::query()
            ->where('id', $bookId)
            ->where('user_id', $userId)
            ->update([
                'public' => $isPublic,
            ]);

        return $isPublic;
    }

    /**
     * @param int $bookId
     * @param int $userId
     * @param string $text
     * @return void
     */
    public function editBook(int $bookId, int $userId, string $text): void
    {
        self::query()
            ->where('id', $bookId)
            ->where('user_id', $userId)
            ->update([
                'text' => $text,
            ]);
    }
}
