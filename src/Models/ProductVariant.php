<?php

namespace ItmoSoft\ProductCatalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use ItmoSoft\ProductCatalog\Models\Concerns\UsesCatalogTable;

class ProductVariant extends Model
{
    use UsesCatalogTable;

    protected string $catalogTable = 'product_variants';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'is_active' => 'boolean',
            'options' => 'array',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(config('product-catalog.models.product', Product::class));
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            config('product-catalog.models.attribute_value', AttributeValue::class),
            config('product-catalog.table_prefix', 'catalog_').'product_attribute_value'
        )->withPivot('product_id')->withTimestamps();
    }
}
