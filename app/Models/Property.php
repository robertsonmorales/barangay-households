<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'properties';

    protected $fillable = [
        'owner_code', 
        'name', 
        'code', 
        'property_type_code', 
        'price', 
        'amenities', 
        'rules_and_regulations', 
        'status', 
        'created_by', 
        'updated_by', 
        'deleted_by', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];
}
