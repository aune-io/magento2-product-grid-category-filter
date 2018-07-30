# Product grid category filter for Magento 2
Add category column and filter in an efficient way to the product grid in the Magento 2 admin.

[![Build Status](https://travis-ci.org/aune-io/magento2-product-grid-category-filter.svg?branch=master)](https://travis-ci.org/aune-io/magento2-product-grid-category-filter)
[![Coverage Status](https://coveralls.io/repos/github/aune-io/magento2-product-grid-category-filter/badge.svg?branch=master)](https://coveralls.io/github/aune-io/magento2-product-grid-category-filter?branch=master)
[![Latest Stable Version](https://poser.pugx.org/aune-io/magento2-product-grid-category-filter/v/stable)](https://packagist.org/packages/aune-io/magento2-product-grid-category-filter)
[![Latest Unstable Version](https://poser.pugx.org/aune-io/magento2-product-grid-category-filter/v/unstable)](https://packagist.org/packages/aune-io/magento2-product-grid-category-filter)
[![Total Downloads](https://poser.pugx.org/aune-io/magento2-product-grid-category-filter/downloads)](https://packagist.org/packages/aune-io/magento2-product-grid-category-filter)
[![License](https://poser.pugx.org/aune-io/magento2-product-grid-category-filter/license)](https://packagist.org/packages/aune-io/magento2-product-grid-category-filter)

## System requirements
This extension supports the following versions of Magento:

*	Community Edition (CE) version 2.2.x

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

## License
Licensed under the Open Software License version 3.0
