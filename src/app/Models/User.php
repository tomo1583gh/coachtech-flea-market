<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'zip',
        'address',
        'building',
        'image_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function boughtProducts()
    {
        return $this->hasMany(Product::class, 'buyer_id');
    }

    public function soldProducts()
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorite_product', 'user_id', 'product_id')->withTimestamps();
    }

    public function isProfileComplete(): bool
    {
        return !empty($this->name)
            && !empty($this->zip)
            && !empty($this->address)
            && !empty($this->building);
    }
}
