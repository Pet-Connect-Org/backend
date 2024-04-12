<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $fillable = [
        'favoriteFood', 'isFriendlyWithDog', 'isFriendlyWithCat', 'isCleanProperly', 'isHyperactive', 'isShy', 'isFriendlyWithKid', 'pet_id'
    ];
}
