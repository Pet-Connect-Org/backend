<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    use HasFactory;
/**
 * @OA\Schema(
 *     schema="Post",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="content",
 *         type="string",
 *         example="This is the content of the post."
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="latitude",
 *         type="float",
 *         example=20
 *     ),
 *     @OA\Property(
 *         property="longitude",
 *         type="float",
 *         example=120
 *     )
 * )
 */
    protected $primaryKey = 'id';

    protected $fillable = [
        'content',
        'user_id',
        'latitude',
        'longitude'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->with('user');
    }

    public function likes()
    {
        return $this->hasMany(LikePost::class);
    }
}
