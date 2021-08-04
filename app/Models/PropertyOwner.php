<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyOwner extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'property_owners';
    protected $fillable = [
        'name', 
        'code', 
        'profile_image', 
        'contact_number', 
        'email', 
        'address', 
        'status', 
        'created_by', 
        'updated_by', 
        'deleted_by', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];
}
