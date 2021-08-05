<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'families';

    protected $fillable = [
        'household_id', 'family_no', 'family_name', 'have_cell_radio_tv', 'have_vehicle', 'have_bicycle', 'have_pedicab', 'have_motorcycle', 'have_tricycle', 'have_four_wheeled', 'have_truck', 'have_motor_boat', 'have_boat', 'status', 'created_by', 'updated_by', 'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public function scopeActive($query){
        $query->where('status', 1);
    }

    public function household(){
        return $this->belongsTo(Household::class);
    }

    public function individuals(){
        return $this->hasMany(Individual::class, 'family_id');
    }
}
