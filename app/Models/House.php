<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class House extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'houses';

    protected $fillable = [
        'barangay_id', 'house_no', 'house_roof', 'house_wall', 'building_permit', 'occupancy_permit', 'date_constructed', 'status', 'created_by', 'updated_by', 'deleted_by'
    ];
    
    protected $dates = ['deleted_at'];

    public function scopeActive($query){
        $query->where('status', 1);
    }

    public function barangay(){
        return $this->belongsTo(Barangay::class);
    }

    public function households(){
        return $this->hasMany(household::class, 'house_id');
    }
}
