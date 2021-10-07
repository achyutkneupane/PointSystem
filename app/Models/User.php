<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $extends = [
        'active_order',
        'reward',
        'active_points'
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function points()
    {
        return $this->hasMany(Point::class);
    }
    public function getActiveOrderAttribute()
    {
        $orders = $this->orders->filter(function($order) {
            return !$order->completed;
        });
        return $orders->count() ? $orders->first() : NULL;
    }
    public function getActivePointsAttribute()
    {
        $points = $this->points->where('available','!=',0)->where('expires_at','>',now());
        return $points;
    }
    public function getRewardAttribute()
    {
        return $this->active_points->count() ? $this->active_points->sum('available') : 0;
    }
}
