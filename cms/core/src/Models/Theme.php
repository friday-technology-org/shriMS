<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'version',
        'author',
        'description',
        'screenshot',
        'is_active',
        'is_child_of',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Absolute filesystem path to this theme's directory.
     */
    public function path(): string
    {
        return base_path('cms-content/themes/' . $this->slug);
    }

    /**
     * Public URL for the theme's screenshot, if set.
     */
    public function screenshotUrl(): ?string
    {
        if (!$this->screenshot) {
            return null;
        }

        return url('themes/' . $this->slug . '/' . $this->screenshot);
    }

    /**
     * The parent theme, if this is a child theme.
     */
    public function parentTheme(): ?self
    {
        if (!$this->is_child_of) {
            return null;
        }

        return static::where('slug', $this->is_child_of)->first();
    }
}
