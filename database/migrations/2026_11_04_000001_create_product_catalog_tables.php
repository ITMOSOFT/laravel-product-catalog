<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('product-catalog.table_prefix', 'catalog_');

        Schema::create($prefix.'brands', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create($prefix.'categories', function (Blueprint $table) use ($prefix): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained($prefix.'categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['parent_id', 'position']);
        });

        Schema::create($prefix.'products', function (Blueprint $table) use ($prefix): void {
            $table->id();
            $table->foreignId('brand_id')->nullable()->constrained($prefix.'brands')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->string('product_type')->default('simple');
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('is_active')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['status', 'is_active']);
        });

        Schema::create($prefix.'category_product', function (Blueprint $table) use ($prefix): void {
            $table->foreignId('category_id')->constrained($prefix.'categories')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained($prefix.'products')->cascadeOnDelete();
            $table->primary(['category_id', 'product_id']);
            $table->timestamps();
        });

        Schema::create($prefix.'attributes', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('select');
            $table->boolean('is_variant')->default(false);
            $table->timestamps();
        });

        Schema::create($prefix.'attribute_values', function (Blueprint $table) use ($prefix): void {
            $table->id();
            $table->foreignId('attribute_id')->constrained($prefix.'attributes')->cascadeOnDelete();
            $table->string('value');
            $table->string('slug');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
            $table->unique(['attribute_id', 'slug']);
        });

        Schema::create($prefix.'product_variants', function (Blueprint $table) use ($prefix): void {
            $table->id();
            $table->foreignId('product_id')->constrained($prefix.'products')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('compare_at_price', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('options')->nullable();
            $table->timestamps();
            $table->index(['product_id', 'is_active']);
        });

        Schema::create($prefix.'product_attribute_value', function (Blueprint $table) use ($prefix): void {
            $table->id();
            $table->foreignId('product_id')->constrained($prefix.'products')->cascadeOnDelete();
            $table->foreignId('attribute_value_id')->constrained($prefix.'attribute_values')->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained($prefix.'product_variants')->cascadeOnDelete();
            $table->timestamps();
            $table->index(
                ['product_id', 'attribute_value_id'],
                'cat_pav_product_attribute_idx'
            );

            $table->index(
                ['product_variant_id'],
                'cat_pav_variant_idx'
            );
        });
    }

    public function down(): void
    {
        $prefix = config('product-catalog.table_prefix', 'catalog_');

        Schema::dropIfExists($prefix.'product_attribute_value');
        Schema::dropIfExists($prefix.'product_variants');
        Schema::dropIfExists($prefix.'attribute_values');
        Schema::dropIfExists($prefix.'attributes');
        Schema::dropIfExists($prefix.'category_product');
        Schema::dropIfExists($prefix.'products');
        Schema::dropIfExists($prefix.'categories');
        Schema::dropIfExists($prefix.'brands');
    }
};
