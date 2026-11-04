<?php

namespace ItmoSoft\ProductCatalog\Models\Concerns;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model): void {
            if (blank($model->slug) && filled($model->name)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
