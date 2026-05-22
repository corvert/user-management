<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class TimeLog extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'user_id',
        'log_date',
        'arrival_time',
        'departure_time',
        'status',
        'approved_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function timeLogAudits()
    {
        return $this->hasMany(TimeLogAudit::class);
    }

    public function transformAudit(array $data): array
    {
        $data['reason'] = request()->input('reason');

        return $data;
    }
}
