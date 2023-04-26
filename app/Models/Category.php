<?php

namespace App\Models;

use App\Notifications\CategoryNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = ['name', 'is_publish'];

    // Optional: Define table name if not using default
    protected $table = 'categories';

    // Optional: Define timestamps if not using default
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public static function boot()
    {
        parent::boot();

        static::created(function ($category) {
            $category->notify(new CategoryNotification($category, 'created'));
        });

        static::deleted(function ($category) {
            $category->notify(new CategoryNotification($category, 'deleted'));
        });
    }
}
