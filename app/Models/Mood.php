<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mood extends Model
{
    protected $fillable = ['goal_id', 'user_id', 'mood', 'note', 'logged_at'];

    protected $casts = ['logged_at' => 'date'];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    /** Classical quotes matched to mood, mirroring the prototype. */
    public static function quotesFor(string $mood): array
    {
        return match ($mood) {
            'content' => [
                ['t' => 'A contented mind is the greatest blessing a person can enjoy.', 'a' => 'Joseph Addison'],
                ['t' => 'Happiness depends upon ourselves.', 'a' => 'Aristotle'],
            ],
            'driven' => [
                ['t' => 'The impediment to action advances action. What stands in the way becomes the way.', 'a' => 'Marcus Aurelius'],
                ['t' => 'Nothing is particularly hard if you divide it into small jobs.', 'a' => 'Henry Ford'],
            ],
            'calm' => [
                ['t' => 'He who is not calm cannot think clearly.', 'a' => 'Confucius'],
                ['t' => 'Very little is needed to make a happy life.', 'a' => 'Marcus Aurelius'],
            ],
            'weary' => [
                ['t' => 'Rest, but never quit.', 'a' => 'Traditional proverb'],
                ['t' => 'Even a small star shines in the darkness.', 'a' => 'Finnish proverb'],
            ],
            'weighed' => [
                ['t' => 'The obstacle is the way.', 'a' => 'Marcus Aurelius'],
                ['t' => 'Difficulties strengthen the mind, as labor does the body.', 'a' => 'Seneca'],
            ],
            default => [['t' => 'Well begun is half done.', 'a' => 'Aristotle']],
        };
    }
}
