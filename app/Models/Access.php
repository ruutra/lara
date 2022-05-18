<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id
 * @property int $library_id
 */
class Access extends Model
{
    protected $table = 'access';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'library_id',
    ];

    /**
     * @param int $accessUserId
     * @param int $userLibraryId
     * @return bool
     */
    public function enable(int $accessUserId, int $userLibraryId): bool
    {
        $library = new self();
        $library->user_id = $accessUserId;
        $library->library_id = $userLibraryId;
        return $library->save();
    }

    /**
     * @param int $accessUserId
     * @param int $userLibraryId
     * @return void
     */
    public function disable(int $accessUserId, int $userLibraryId): void
    {
        self::query()
            ->where(['user_id' => $accessUserId])
            ->where(['library_id' => $userLibraryId])
            ->delete();
    }

    /**
     * @param int $currentUserId
     * @param int $userLibraryId
     * @return bool
     */
    public function isAccess(int $currentUserId, int $userLibraryId): bool
    {
        return (bool) self::query()
            ->where('user_id', $currentUserId)
            ->where('library_id', $userLibraryId)
            ->first();
    }
}
