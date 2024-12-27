<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $table = 'periods';

    public function user()
    {
        return $this->belongsToMany(User::class, 'period_participants', 'periods_id', 'users_id')
            ->withPivot('status', 'date', 'file_bank_statement')
            ->withTimestamps();
    }
}
