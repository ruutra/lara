<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
     * @param User $user
     * @return void
     */
    public function addComment(string $text, User $user): void
    {
        $comment = new self();
        $comment->text = $text;
        $comment->user_id = $user->id;
        $comment->author_id = $user->id;
        $comment->save();
    }

    /**
     * @param User $user
     * @return array
     */
    public function getComments(User $user): array
    {
        return self::query()
            ->where(['user_id' => $user->id])
            ->get()
            ->toArray();
    }

   //public function deleteComment($id)
   //{
   //    $data = Comments::find($id);
   //    $data->delete();
   //   // return self::query()
   //   //    ->where(['id'=>$comments->id,'user_id'=>$user->id])->delete();
   //    //DB::delete('delete from `laravel`.`comments` where `id` = ? and `user_id` = ?');
   //}

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): BelongsTo
    {
        return $this->belongsTo(Comments::class);
    }
}
