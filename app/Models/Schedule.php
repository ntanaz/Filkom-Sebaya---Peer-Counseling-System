<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'request_id',
        'date',
        'time',
        'status',
    ];

    /**
     * Relationship: A schedule belongs to a CounselingRequest.
     */
    public function counselingRequest()
    {
        return $this->belongsTo(CounselingRequest::class, 'request_id', 'request_id');
    }
}
