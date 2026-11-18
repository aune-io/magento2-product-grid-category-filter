# Product grid category filter for Magento 2
Add category column and filter in an efficient way to the product grid in the Magento 2 admin.

[![Tests](https://github.com/aune-io/magento2-product-grid-category-filter/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/aune-io/magento2-product-grid-category-filter/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/aune-io/magento2-product-grid-category-filter/v/stable)](https://packagist.org/packages/aune-io/magento2-product-grid-category-filter)
[![Latest Unstable Version](https://poser.pugx.org/aune-io/magento2-product-grid-category-filter/v/unstable)](https://packagist.org/packages/aune-io/magento2-product-grid-category-filter)
[![Total Downloads](https://poser.pugx.org/aune-io/magento2-product-grid-category-filter/downloads)](https://packagist.org/packages/aune-io/magento2-product-grid-category-filter)
[![License](https://poser.pugx.org/aune-io/magento2-product-grid-category-filter/license)](https://packagist.org/packages/aune-io/magento2-product-grid-category-filter)

## System requirements
This extension supports the following versions:

*	Magento Open Source / Adobe Commerce 2.4.6, 2.4.7, 2.4.8 and 2.4.9
*	PHP 8.2, 8.3, 8.4 and 8.5 (as far as each Magento release supports them)

Older Magento (2.2, 2.3, 2.4.0 - 2.4.5) and PHP (7.x, 8.0, 8.1) versions are no longer supported.
The last release supporting them is the one tagged before this change; use that if you are still on those versions.

## Installation
1. Require the module via Composer
```bash
$ composer require aune-io/magento2-product-grid-category-filter
```

2. Enable the module
```bash
$ bin/magento module:enable Aune_ProductGridCategoryFilter
$ bin/magento setup:upgrade
```

3. Login to the admin
4. Go to Catalog > Catalog > Products, the category column and filter will be available

## Anchor categories
If you wish to view and/or filter by anchor categories, you can enable them via
_Store > Configuration > Catalog > Product Grid Category Filter_.
For flexibility purposes, anchor support can be enabled separately for filter and view.

In case you enable one of those options, the _Categories Products_ index needs to be up to date.
By default the index of store 1 is used, but this configuration can be changed from
the same section in case you don't have a store with id 1 or its information are not the ones you need to see.

## Authors, contributors and maintainers

Author:
- [Renato Cason](https://github.com/renatocason)

Contributors:
- [xpedicion](https://github.com/xpedicion)

## License
Licensed under the Open Software License version 3.0
