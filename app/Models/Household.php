<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Household extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'households';

    protected $fillable = [
        'house_id', 'household_no', 'land_ownership', 'cr', 'shared_to', 'electricity_connection', 'disaster_kit', 'praticing_waste_segregation', 'status', 'created_by', 'updated_by', 'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public function scopeActive($query){
        $query->where('status', 1);
    }

    public function house(){
        return $this->belongsTo(House::class);
    }

    public function families(){
        return $this->hasMany(Family::class, 'household_id');
    }
}
