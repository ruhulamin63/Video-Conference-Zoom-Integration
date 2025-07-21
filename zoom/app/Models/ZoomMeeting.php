<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZoomMeeting extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 
        'meeting_id', 
        'client_id',
        'project_id',
        'start_date',
        'duration',
        'start_url',
        'password',
        'join_url',
        'status',
        'created_by',
    ];

    public function checkDateTime(){
        $m = $this;
        
        if (\Carbon\Carbon::parse($m->start_date)->addMinutes($m->duration)->gt(\Carbon\Carbon::now())) {
            return 1;
        }else{
            return 0;
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users($users)
    {
        $userArr = explode(',', $users);
        $users  = [];
        
        foreach($userArr as $user) {
            $users[] = User::find($user);
        }
        return $users;
    }
}
