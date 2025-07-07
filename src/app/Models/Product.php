<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Comment;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'description',
        'price',
        'state',
        'image_path',

    ];

    protected $casts = [
        'is_sold' => 'boolean',
        'state' => 'integer',
    ];


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorite_product')->withTimestamps();
    }

    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id')->withTimestamps();
    }
}
