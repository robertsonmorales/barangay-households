<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Barangay extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "barangays";

    protected $fillable = [
        'barangay_code', 'barangay_name', 'status', 'created_by', 'updated_by', 'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public function scopeActive($query){
        $query->where('status', 1);
    }

    public function houses(){
        return $this->hasMany(House::class, 'barangay_id');
    }
}
