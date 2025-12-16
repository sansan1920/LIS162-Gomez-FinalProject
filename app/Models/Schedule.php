<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'schedules';
    protected $primaryKey = 'schedule_id';
    protected $fillable = ['day_of_week', 'start_time', 'end_time', 'facility_id',];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }
    
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'schedule_id');
    }
}
