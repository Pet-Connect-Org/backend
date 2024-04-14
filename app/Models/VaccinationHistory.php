<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccinationHistory extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = [
        'medical_record_id',
        'description',
        'name',
        'time'
    ];

    public function meds()
    {
        return $this->belongsTo(MedicalRecord::class, 'medical_record_id', 'id');
    }
}
