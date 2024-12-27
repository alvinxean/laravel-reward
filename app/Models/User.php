<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';

    public function period()
    {
        return $this->belongsToMany(Period::class, 'period_participants', 'users_id', 'periods_id')
            ->withPivot('status', 'date', 'file_bank_statement')
            ->withTimestamps();
    }

    public function role()
    {
        return $this->belongsTo(User::class, 'roles_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'banks_id', 'id');
    }
}
