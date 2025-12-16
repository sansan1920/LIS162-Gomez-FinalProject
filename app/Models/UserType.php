<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'user_types';
    protected $primaryKey = 'user_types_id';
    protected $fillable = ['user_type',];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'user_types_id');
    }
}
