<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Database table prefix
    |--------------------------------------------------------------------------
    |
    | Change this before running the package migrations. The prefix is used
    | consistently by migrations and package model relationships.
    |
    */
    'table_prefix' => env('PRODUCT_CATALOG_TABLE_PREFIX', 'catalog_'),

    'models' => [
        'product' => \ItmoSoft\ProductCatalog\Models\Product::class,
        'category' => \ItmoSoft\ProductCatalog\Models\Category::class,
        'brand' => \ItmoSoft\ProductCatalog\Models\Brand::class,
        'attribute' => \ItmoSoft\ProductCatalog\Models\Attribute::class,
        'attribute_value' => \ItmoSoft\ProductCatalog\Models\AttributeValue::class,
        'variant' => \ItmoSoft\ProductCatalog\Models\ProductVariant::class,
    ],
];
