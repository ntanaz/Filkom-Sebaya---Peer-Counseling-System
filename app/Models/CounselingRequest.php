<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingRequest extends Model
{
    use HasFactory;

    protected $table = 'counseling_requests';
    
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'konseli_id',
        'konselor_id',
        'topic',
        'description',
        'status',
        'category',
        'case_level',
        'problem_description',
        'accepted_at',
        'completed_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Relationship: A request belongs to a Konseli (User).
     */
    public function konseli()
    {
        return $this->belongsTo(User::class, 'konseli_id', 'user_id');
    }

    /**
     * Relationship: A request belongs to a Counselor (User).
     */
    public function counselor()
    {
        return $this->belongsTo(User::class, 'konselor_id', 'user_id');
    }

    /**
     * Relationship: A request has one Schedule.
     */
    public function schedule()
    {
        return $this->hasOne(Schedule::class, 'request_id', 'request_id');
    }

    /**
     * Relationship: A request has many Reports.
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'request_id', 'request_id');
    }
}
