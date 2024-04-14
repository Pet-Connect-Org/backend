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

    public function allergies()
    {
        return $this->hasMany(Allergy::class, 'medical_record_id', 'id');
    }

    public function pet() {
        return $this->belongsTo(Pet::class, 'pet_id', 'id');
    }
}