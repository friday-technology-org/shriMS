<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostViewStat extends Model
{
    protected $fillable = [
        'post_id',
        'date',
        'views',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
