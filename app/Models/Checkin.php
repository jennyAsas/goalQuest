<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkin extends Model
{
    protected $fillable = ['goal_id', 'user_id', 'date', 'amount', 'progress_delta', 'note'];

    protected $casts = ['date' => 'date'];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}
