<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetType extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'image',
        'type'
    ];

    public function pets()
    {
        return $this->hasMany(Pet::class, 'pet_type_id', 'id');
    }
}
