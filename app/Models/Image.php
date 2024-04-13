<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'post_id',
        'link'
    ];

    public function post()
    {
        return $this->belongsTo(User::class, 'post_id', 'id');
    }
}
