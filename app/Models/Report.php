<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $primaryKey = 'report_id';

    protected $fillable = [
        'request_id',
        'counselor_id',
        'summary',
        'recommendation',
    ];

    /**
     * Relationship: A report belongs to a CounselingRequest.
     */
    public function counselingRequest()
    {
        return $this->belongsTo(CounselingRequest::class, 'request_id', 'request_id');
    }

    /**
     * Relationship: A report is written by a Counselor (User).
     */
    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id', 'user_id');
    }
}
