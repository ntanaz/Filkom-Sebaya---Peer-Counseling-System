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

    // Accessor for Status to keep old controllers/views working
    public function getStatusAttribute($value)
    {
        switch ($value) {
            case 'pending': return 'menunggu';
            case 'accepted': return $this->schedule ? 'dijadwalkan' : 'diproses';
            case 'rescheduled': return 'dijadwalkan';
            case 'completed': return 'selesai';
            case 'cancelled': return 'dibatalkan';
            default: return $value;
        }
    }

    // Mutator for Status to store SRS database enums
    public function setStatusAttribute($value)
    {
        switch ($value) {
            case 'menunggu': $this->attributes['status'] = 'pending'; break;
            case 'diproses': $this->attributes['status'] = 'accepted'; break;
            case 'dijadwalkan': $this->attributes['status'] = 'accepted'; break;
            case 'selesai': $this->attributes['status'] = 'completed'; break;
            case 'dibatalkan': $this->attributes['status'] = 'cancelled'; break;
            default: $this->attributes['status'] = $value; break;
        }
    }

    // Custom Eloquent Builder to map statuses in database queries transparently
    public function newEloquentBuilder($query)
    {
        return new class($query) extends \Illuminate\Database\Eloquent\Builder {
            public function where($column, $operator = null, $value = null, $boolean = 'and')
            {
                if ($column === 'status' || $column === 'counseling_requests.status') {
                    $val = $value ?? $operator;
                    if (is_string($val)) {
                        if ($val === 'dijadwalkan') {
                            return $this->whereIn($column, ['accepted', 'rescheduled'], $boolean);
                        }
                        $val = $this->mapStatus($val);
                        if ($value === null) {
                            $operator = $val;
                        } else {
                            $value = $val;
                        }
                    }
                }
                return parent::where($column, $operator, $value, $boolean);
            }

            public function whereIn($column, $values, $boolean = 'and', $not = false)
            {
                if ($column === 'status' || $column === 'counseling_requests.status') {
                    $newValues = [];
                    foreach ($values as $val) {
                        if ($val === 'dijadwalkan') {
                            $newValues[] = 'accepted';
                            $newValues[] = 'rescheduled';
                        } else {
                            $newValues[] = $this->mapStatus($val);
                        }
                    }
                    $values = array_unique($newValues);
                }
                return parent::whereIn($column, $values, $boolean, $not);
            }

            private function mapStatus($status)
            {
                switch ($status) {
                    case 'menunggu': return 'pending';
                    case 'diproses': return 'accepted';
                    case 'dijadwalkan': return 'accepted';
                    case 'selesai': return 'completed';
                    case 'dibatalkan': return 'cancelled';
                    default: return $status;
                }
            }
        };
    }
}
