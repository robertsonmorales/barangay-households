<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Individual extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'individuals';

    protected $fillable = [
        'family_id', 'individual_no', 'last_name', 'first_name', 'middle_name', 'suffix', 'gender', 'birthdate', 'ethnicity', 'relationship', 'marital_status', 'status', 'created_by', 'updated_by', 'deleted_by' 
    ];

    protected $dates = ['deleted_at'];

    public function scopeActive($query){
        $query->where('status', 1);
    }

    public function families(){
        return $this->belongsTo(Family::class);
    }
}
