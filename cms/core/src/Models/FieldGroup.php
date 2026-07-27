<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldGroup extends Model
{
    protected $fillable = [
        'title',
        'location_rules',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'location_rules' => 'array',
        'is_active' => 'boolean',
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class)->orderBy('sort_order');
    }
}
