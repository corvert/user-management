<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TimeLogAudit extends Model
{
    use HasFactory;

    protected $fillable = ['time_log_id', 'user_id', 'action'];
    
    public function timeLog()
    {
        return $this->belongsTo(TimeLog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
