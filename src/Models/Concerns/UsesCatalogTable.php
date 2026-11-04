<?php

namespace ItmoSoft\ProductCatalog\Models\Concerns;

trait UsesCatalogTable
{
    public function getTable(): string
    {
        return config('product-catalog.table_prefix', 'catalog_').$this->catalogTable;
    }
}
