<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    use HasFactory;

    protected $table = 'property_types';
    protected $fillable = ['name', 'code', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'];
}
