<?php

namespace ItmoSoft\ProductCatalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ItmoSoft\ProductCatalog\Models\Concerns\HasSlug;

use ItmoSoft\ProductCatalog\Models\Concerns\UsesCatalogTable;

class Brand extends Model
{
    use UsesCatalogTable;

    protected string $catalogTable = 'brands';
    use HasSlug;

    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'metadata' => 'array'];
    }

    public function products(): HasMany
    {
        return $this->hasMany(config('product-catalog.models.product', Product::class));
    }
}
