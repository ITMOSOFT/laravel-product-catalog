# ITMOSOFT Laravel Product Catalog

A reusable Laravel package providing a foundational product catalog: products, hierarchical categories, brands, product attributes, attribute values, and product variants.

## Requirements

- PHP 8.3+
- Laravel 12 or 13

## Installation

```bash
composer require itmosoft/laravel-product-catalog
php artisan vendor:publish --tag=product-catalog-config
php artisan migrate
```

Laravel package auto-discovery registers the service provider and loads the package migrations.

## Included models

| Model | Purpose |
|---|---|
| `Product` | Catalog item with name, slug, SKU, type, descriptions, status, publication state and metadata |
| `Category` | Hierarchical product categories |
| `Brand` | Product brand |
| `Attribute` | Product attribute definition, such as color or size |
| `AttributeValue` | A value belonging to an attribute |
| `ProductVariant` | Sellable product option with SKU and price fields |

## Basic usage

```php
use ItmoSoft\ProductCatalog\Models\Product;
use ItmoSoft\ProductCatalog\Models\Category;
use ItmoSoft\ProductCatalog\Models\Brand;

$brand = Brand::create(['name' => 'Acme']);

$category = Category::create(['name' => 'Accessories']);

$product = Product::create([
    'name' => 'Canvas Backpack',
    'brand_id' => $brand->id,
    'sku' => 'BAG-001',
    'product_type' => 'simple',
    'status' => 'published',
    'is_active' => true,
]);

$product->categories()->attach($category);
```

Slugs are generated from `name` when a model is created and no slug is supplied. Slug uniqueness is enforced by the database; this package does not silently append suffixes to resolve collisions.

## Variants and attributes

```php
$color = Attribute::create([
    'name' => 'Color',
    'type' => 'select',
    'is_variant' => true,
]);

$red = $color->values()->create([
    'value' => 'Red',
    'slug' => 'red',
]);

$variant = $product->variants()->create([
    'name' => 'Red / Medium',
    'sku' => 'BAG-001-RED-M',
    'price' => 49.99,
    'compare_at_price' => 59.99,
]);

$product->attributeValues()->attach($red->id, [
    'product_variant_id' => $variant->id,
]);
```

## Configuration

Publish `config/product-catalog.php`:

```bash
php artisan vendor:publish --tag=product-catalog-config
```

- `table_prefix`: prefix applied to all package tables. Set it before running migrations.
- `models`: model class map used by package relationships. Custom model classes should extend the corresponding package model or preserve its relationship/table contract.

The default table prefix is `catalog_`.

## Database tables

- `catalog_products`
- `catalog_categories`
- `catalog_brands`
- `catalog_attributes`
- `catalog_attribute_values`
- `catalog_product_variants`
- `catalog_category_product`
- `catalog_product_attribute_value`

## Design boundaries

This package is the catalog foundation only. It does not implement inventory quantities, stock reservations, pricing rules, promotions, carts, checkout, orders, payment processing, product image storage, or storefront APIs. Those concerns should remain in dedicated packages or the consuming application.

## Testing

```bash
composer install
composer validate --no-check-publish
composer test
```

GitHub Actions tests Laravel 12 and Laravel 13 separately using the matching Orchestra Testbench major version.

## License

MIT. See [LICENSE](LICENSE).
