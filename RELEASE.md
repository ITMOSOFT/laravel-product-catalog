# Release checklist

## Repository setup

Suggested GitHub repository: `ITMOSOFT/laravel-product-catalog`

Repository description:

> A reusable Laravel product catalog package with products, categories, brands, attributes, and variants. Supports Laravel 12 and 13.

## Local verification

```bash
composer install
composer validate --no-check-publish
composer test
```

Do not publish a release until local tests pass and both Laravel 12 and 13 GitHub Actions jobs pass.

## Commit and push

```bash
git add .
git status --short
git commit -m "feat: add Laravel product catalog package"
git push -u origin main
```

Check that `vendor/` is not staged or tracked:

```bash
git ls-files vendor/
```

This command should return no files.

## Tag and push

Run only after the main branch is up to date and CI is green:

```bash
git checkout main
git pull --ff-only origin main
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0
```

Verify:

```bash
git show v1.0.0
```

## GitHub Release

Title:

`v1.0.0 — Initial Stable Release`

Description:

```markdown
## ITMOSOFT Laravel Product Catalog v1.0.0

Initial stable release of a reusable Laravel product catalog package.

### Features
- Product model with SKU, type, descriptions, status, publication timestamp, and JSON metadata
- Hierarchical categories and product-category assignments
- Brands and product-brand relationship
- Product attributes and attribute values
- Product variants with SKU, price, compare-at price, and JSON options
- Configurable package table prefix and model class map
- Laravel 12 and Laravel 13 compatibility

### Requirements
- PHP 8.3+
- Laravel 12 or 13

### Installation
```bash
composer require itmosoft/laravel-product-catalog
php artisan vendor:publish --tag=product-catalog-config
php artisan migrate
```

### Quality
Includes PHPUnit feature tests and a GitHub Actions Laravel 12/13 test matrix.
```

## Packagist

1. Submit `https://github.com/ITMOSOFT/laravel-product-catalog` at https://packagist.org/packages/submit.
2. Configure the Packagist GitHub service hook/webhook if automatic updates are not already enabled.
3. Verify the package page and install it into a clean Laravel application.

Package name: `itmosoft/laravel-product-catalog`
Version tag: `v1.0.0`
