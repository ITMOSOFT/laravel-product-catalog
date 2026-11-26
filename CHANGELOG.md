# Changelog

All notable changes to this project are documented here.

## [1.0.2] - Unreleased

- Fixed MySQL migration failure caused by automatically generated index names exceeding the 64-character identifier limit.
- Added explicit, MySQL-safe index names for product attribute value relationships.
- Added a regression test verifying the product attribute value index names and their lengths.

## [1.0.1] - Unreleased

- Fixed variant-to-attribute-value attachment so the required `product_id` pivot column is populated automatically.
- Added a regression test for attaching attribute values through a product variant.
- Removed the `test:unit` Composer script because the package has no Unit test suite configured; retained the feature-test script.

## [1.0.0] - 2026-11-04

- Initial stable product catalog package.
- Added products, hierarchical categories, brands, attributes, attribute values, and product variants.
- Added configurable table prefix and model map.
- Added Laravel 12/13 CI matrix and feature tests.
