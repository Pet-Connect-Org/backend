<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = [
        'content',
        'user_id',
        'latitude',
        'longitude'
    ];

    public function images() {
        return $this->hasMany(Image::class);
    }

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
