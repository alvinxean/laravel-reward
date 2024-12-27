<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodParticipant extends Model
{
    use HasFactory;
    protected $table = 'period_participants';
    
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'periods_id');
    }
    protected $fillable = [
        'users_id',
        'periods_id',
    ];
}
