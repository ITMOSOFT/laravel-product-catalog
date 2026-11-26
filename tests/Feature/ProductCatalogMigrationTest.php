<?php

namespace ItmoSoft\ProductCatalog\Tests\Feature;

use Illuminate\Support\Facades\Schema;
use ItmoSoft\ProductCatalog\Tests\TestCase;

class ProductCatalogMigrationTest extends TestCase
{
    public function test_product_attribute_value_indexes_use_mysql_safe_names(): void
    {
        $indexes = Schema::getIndexes('catalog_product_attribute_value');

        $indexNames = array_column($indexes, 'name');

        $this->assertContains('cat_pav_product_attribute_idx', $indexNames);
        $this->assertContains('cat_pav_variant_idx', $indexNames);

        foreach ($indexNames as $indexName) {
            $this->assertLessThanOrEqual(
                64,
                strlen($indexName),
                "Index name [{$indexName}] exceeds MySQL's 64-character limit."
            );
        }
    }
}
