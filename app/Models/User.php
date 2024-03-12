<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sex',
        'birthday',
        'address',
        'account_id'
    ];

    public function account()
    {
        return $this->hasOne(Account::class, 'id', 'account_id');
    }
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likePosts()
    {
        return $this->hasMany(LikePost::class);
    }
  
}
