<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'user_types_id',];
    protected $hidden = ['password', 'remember_token',];
    protected function casts(): array
    { 
        return ['email_verified_at' => 'datetime', 'password' => 'hashed',];
    }

    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class, 'user_types_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name,
        );
    }
}
