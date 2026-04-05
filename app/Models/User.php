<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * الحقول المسموح بتعبئتها (Mass Assignment)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * الحقول التي يتم إخفاؤها عند تحويل الموديل إلى Array أو JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * تحويل الحقول لتنسيقات معينة
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * العلاقات (Relationships)
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}