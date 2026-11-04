<?php

namespace ItmoSoft\ProductCatalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ItmoSoft\ProductCatalog\Models\Concerns\HasSlug;

use ItmoSoft\ProductCatalog\Models\Concerns\UsesCatalogTable;

class Product extends Model
{
    use UsesCatalogTable;

    protected string $catalogTable = 'products';
    use HasSlug;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'published_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(config('product-catalog.models.brand', Brand::class));
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            config('product-catalog.models.category', Category::class),
            config('product-catalog.table_prefix', 'catalog_').'category_product'
        )->withTimestamps();
    }

    public function variants(): HasMany
    {
        return $this->hasMany(config('product-catalog.models.variant', ProductVariant::class));
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            config('product-catalog.models.attribute_value', AttributeValue::class),
            config('product-catalog.table_prefix', 'catalog_').'product_attribute_value'
        )->withPivot('product_variant_id')->withTimestamps();
    }
}
