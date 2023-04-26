<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_publish'];

    // Optional: Define table name if not using default
    protected $table = 'categories';

    // Optional: Define timestamps if not using default
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
