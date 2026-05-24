<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'user_id',
        'request_id',
        'message',
        'type',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Relationship: A notification belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relationship: A notification may refer to a CounselingRequest.
     */
    public function counselingRequest()
    {
        return $this->belongsTo(CounselingRequest::class, 'request_id', 'request_id');
    }

    // Mutator for Type to store database-aligned ENUM values
    public function setTypeAttribute($value)
    {
        switch ($value) {
            case 'success':
            case 'request':
            case 'status':
                $this->attributes['type'] = 'status';
                break;
            case 'info':
            case 'schedule':
                $this->attributes['type'] = 'schedule';
                break;
            case 'reminder':
                $this->attributes['type'] = 'reminder';
                break;
            default:
                $this->attributes['type'] = $value;
                break;
        }
    }
}
