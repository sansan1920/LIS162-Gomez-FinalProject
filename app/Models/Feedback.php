<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'feedbacks';    
    protected $primaryKey = 'feedback_id';
    protected $fillable = ['user_feedback',];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'feedback_id');
    }
}
