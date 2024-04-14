<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightHistory extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = [
        'medical_record_id',
        'weight',
        'description',
        'time'
    ];

    public function meds()
    {
        return $this->belongsTo(MedicalRecord::class, 'medical_record_id', 'id');
    }
}
