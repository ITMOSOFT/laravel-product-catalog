<?php

namespace ItmoSoft\ProductCatalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ItmoSoft\ProductCatalog\Models\Concerns\HasSlug;

use ItmoSoft\ProductCatalog\Models\Concerns\UsesCatalogTable;

class Category extends Model
{
    use UsesCatalogTable;

    protected string $catalogTable = 'categories';
    use HasSlug;

    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'metadata' => 'array'];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(config('product-catalog.models.category', self::class), 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(config('product-catalog.models.category', self::class), 'parent_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            config('product-catalog.models.product', Product::class),
            config('product-catalog.table_prefix', 'catalog_').'category_product'
        )->withTimestamps();
    }
}
