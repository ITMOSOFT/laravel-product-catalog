<?php

namespace ItmoSoft\ProductCatalog\Tests\Feature;

use ItmoSoft\ProductCatalog\Models\Attribute;
use ItmoSoft\ProductCatalog\Models\Brand;
use ItmoSoft\ProductCatalog\Models\Category;
use ItmoSoft\ProductCatalog\Models\Product;
use ItmoSoft\ProductCatalog\Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    public function test_product_slug_is_generated_from_name(): void
    {
        $product = Product::create(['name' => 'Premium Cotton Shirt']);

        $this->assertSame('premium-cotton-shirt', $product->slug);
    }

    public function test_brand_slug_and_product_relationship(): void
    {
        $brand = Brand::create(['name' => 'Acme']);
        $product = Product::create(['name' => 'Item', 'brand_id' => $brand->id]);

        $this->assertSame($brand->id, $product->brand->id);
        $this->assertCount(1, $brand->products);
    }

    public function test_category_hierarchy_can_be_created(): void
    {
        $parent = Category::create(['name' => 'Clothing']);
        $child = Category::create(['name' => 'Shirts', 'parent_id' => $parent->id]);

        $this->assertSame($parent->id, $child->parent->id);
        $this->assertSame($child->id, $parent->children->first()->id);
    }

    public function test_product_can_belong_to_multiple_categories(): void
    {
        $product = Product::create(['name' => 'Everyday Tee']);
        $first = Category::create(['name' => 'Clothing']);
        $second = Category::create(['name' => 'Summer']);

        $product->categories()->attach([$first->id, $second->id]);

        $this->assertCount(2, $product->categories);
    }

    public function test_product_variant_belongs_to_product(): void
    {
        $product = Product::create(['name' => 'Sneaker']);
        $variant = $product->variants()->create([
            'name' => 'Size 42',
            'sku' => 'SNK-42',
            'price' => 89.50,
        ]);

        $this->assertSame($product->id, $variant->product->id);
        $this->assertSame('89.50', $variant->price);
    }

    public function test_attribute_values_belong_to_attribute(): void
    {
        $attribute = Attribute::create(['name' => 'Color', 'type' => 'select', 'is_variant' => true]);
        $value = $attribute->values()->create(['value' => 'Blue', 'slug' => 'blue']);

        $this->assertSame($attribute->id, $value->attribute->id);
        $this->assertCount(1, $attribute->values);
    }

    public function test_product_can_attach_attribute_values(): void
    {
        $product = Product::create(['name' => 'T-Shirt']);
        $attribute = Attribute::create(['name' => 'Color']);
        $value = $attribute->values()->create(['value' => 'Red', 'slug' => 'red']);

        $product->attributeValues()->attach($value->id);

        $this->assertCount(1, $product->attributeValues);
    }

    public function test_variant_can_attach_attribute_values_and_populates_product_id(): void
    {
        $product = Product::create(['name' => 'T-Shirt']);
        $variant = $product->variants()->create(['sku' => 'TS-RED-M', 'price' => 15]);
        $attribute = Attribute::create(['name' => 'Color']);
        $value = $attribute->values()->create(['value' => 'Red', 'slug' => 'red']);

        $variant->attributeValues()->attach($value->id);

        $pivot = $variant->attributeValues()->firstOrFail()->pivot;

        $this->assertSame($value->id, $pivot->attribute_value_id);
        $this->assertSame($product->id, $pivot->product_id);
        $this->assertSame($variant->id, $pivot->product_variant_id);
    }

    public function test_product_metadata_is_cast_to_array(): void
    {
        $product = Product::create(['name' => 'Desk', 'metadata' => ['material' => 'oak']]);

        $this->assertSame(['material' => 'oak'], $product->fresh()->metadata);
    }

    public function test_product_active_flag_is_cast_to_boolean(): void
    {
        $product = Product::create(['name' => 'Lamp', 'is_active' => 1]);

        $this->assertTrue($product->is_active);
    }

    public function test_brand_can_be_deleted_without_deleting_product(): void
    {
        $brand = Brand::create(['name' => 'Brand']);
        $product = Product::create(['name' => 'Product', 'brand_id' => $brand->id]);

        $brand->delete();

        $this->assertDatabaseHas('catalog_products', ['id' => $product->id, 'brand_id' => null]);
    }

    public function test_deleting_product_cascades_to_variants(): void
    {
        $product = Product::create(['name' => 'Product']);
        $variant = $product->variants()->create(['sku' => 'P-001']);

        $product->delete();

        $this->assertDatabaseMissing('catalog_product_variants', ['id' => $variant->id]);
    }
}
