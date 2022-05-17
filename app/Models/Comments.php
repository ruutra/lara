<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @property string $text
 * @property integer $user_id
 * @property integer $parent_id
 * @property integer $author_id
 * @property int $id
 * @method static find($id)
 */

class Comments extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'text',
        'user_id',
        'parent_id',
        'author_id',
    ];

    /**
     * @param string $text
     * @param int $userId
     * @param User $user
     * @return void
     */
    public function addComment(string $text, int $userId, User $user): void
    {
        $comment = new self();
        $comment->text = $text;
        $comment->user_id = $userId;
        $comment->author_id = $user->id;
        $comment->save();
    }

    /**
     * @param int $userId
     * @param int|null $limit
     * @return array
     */
    public function getComments(int $userId, ?int $limit = 5): array
    {
        return DB::table(self::getTable(), 'main')
            ->select('main.*','users.username')
            ->where(['main.user_id' => $userId])
            ->join('users','main.author_id','=','users.id')
            ->leftJoin('comments', 'main.parent_id','=','comments.id')
            ->orderByRaw('coalesce(comments.created_at, main.created_at) desc')
            ->orderByRaw('coalesce(comments.id, main.id)')
            ->orderByRaw('(comments.id is null) desc')
            ->orderByRaw('main.created_at asc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * @param int $userId
     * @param int $commentId
     * @param User $currentUser
     * @return bool
     */
   public function deleteComment(int $userId, int $commentId, User $currentUser): bool
   {
       return self::query()
           ->where([
               'id' => $commentId,
               'user_id' => $userId,
               'author_id' => $currentUser->id,
           ])
           ->delete();
   }

   public function replyComment(string $text, int $userId, int $parentId, User $currentUser)
   {
       $comment = new self();
       $comment->text = $text;
       $comment->user_id = $userId;
       $comment->author_id = $currentUser->id;
       $comment->parent_id = $parentId;
       $comment->save();
   }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): BelongsTo
    {
        return $this->belongsTo(Comments::class);
    }
}
