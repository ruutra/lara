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
     * @return array
     */
    public function getComments(int $userId): array
    {
        return DB::table(self::getTable())
            ->select('comments.*','users.username')
            ->where(['comments.user_id' => $userId])
            ->join('users','comments.author_id','=','users.id')
            ->orderBy('comments.id')
            ->get()
            ->toArray();
    }

    /**
     * @param int $userId
     * @param int $commentId
     * @param User $currentUser
     * @return bool
     */
   public function deleteComment(int $userId, int $commentId, User $currentUser)
   {
       return self::query()
           ->where([
               'id' => $commentId,
               'user_id' => $userId,
               'author_id' => $currentUser->id
           ])
           ->delete();
   }

   public function replyComment(int $parentId)
   {
       return DB::table(self::getTable())
           ->select('comments.*','users.username')
           ->where(['comments.parent_id'=>$parentId])
           ->join('comments','comments.id','=','comments.parent_id')
           ->orderBy('parent_id');
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
