<?php

namespace ItmoSoft\ProductCatalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use ItmoSoft\ProductCatalog\Models\Concerns\UsesCatalogTable;

class AttributeValue extends Model
{
    use UsesCatalogTable;

    protected string $catalogTable = 'attribute_values';

    protected $guarded = [];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(config('product-catalog.models.attribute', Attribute::class));
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            config('product-catalog.models.product', Product::class),
            config('product-catalog.table_prefix', 'catalog_').'product_attribute_value'
        )->withPivot('product_variant_id')->withTimestamps();
    }
}
