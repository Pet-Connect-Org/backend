<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'name',
        'birthday',
        'sex',
        'description',
        'image',
        'pet_type_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function petType()
    {
        return $this->belongsTo(PetType::class, 'pet_type_id', 'id');
    }
}
