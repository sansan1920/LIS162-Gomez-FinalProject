<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'reservation_id';
    protected $fillable = ['reservation_date', 'reservation_status', 'approval_date', 'user_id', 'schedule_id', 'feedback_id',];
    
    protected $attributes = [
        'reservation_status' => 'Approved',];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
    
    public function feedback(): BelongsTo
    {
        return $this->belongsTo(Feedback::class, 'feedback_id');
    }
}
