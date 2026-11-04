<?php

namespace ItmoSoft\ProductCatalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ItmoSoft\ProductCatalog\Models\Concerns\HasSlug;

use ItmoSoft\ProductCatalog\Models\Concerns\UsesCatalogTable;

class Attribute extends Model
{
    use UsesCatalogTable;

    protected string $catalogTable = 'attributes';
    use HasSlug;


    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_variant' => 'boolean'];
    }

    public function values(): HasMany
    {
        return $this->hasMany(
            config('product-catalog.models.attribute_value', AttributeValue::class),
            'attribute_id'
        );
    }
}
