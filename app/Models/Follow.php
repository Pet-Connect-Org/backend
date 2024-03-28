<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = [
        'following_user_id',
        'user_id'
    ];

    public function user() // người theo dõi
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function following() // người được theo dõi
    {
        return $this->belongTo(User::class, 'following_user_id', 'id');
    }
}
