# oxid-esales/graphql-catalogue

[![Build Status](https://flat.badgen.net/travis/OXID-eSales/graphql-catalogue-module/?icon=travis&label=build&cache=300&scale=1.1)](https://travis-ci.com/OXID-eSales/graphql-catalogue-module)
[![PHP Version](https://flat.badgen.net/packagist/php/OXID-eSales/graphql-catalogue/?cache=300&scale=1.1)](https://github.com/oxid-esales/graphql-catalogue-module)
[![Stable Version](https://flat.badgen.net/packagist/v/OXID-eSales/graphql-catalogue/latest/?label=latest&cache=300&scale=1.1)](https://packagist.org/packages/oxid-esales/graphql-catalogue)

This module provides [GraphQL](https://www.graphql.org) queries and mutations for the [OXID eShop](https://www.oxid-esales.com/) allowing access the catalogue view.

This module is not maintained anymore. Hava a look at new module that have all the functionality of this one and more: https://github.com/OXID-eSales/graphql-storefront-module

## Usage

This assumes you have OXID eShop (at least `oxid-esales/oxideshop_ce: v6.5.0` component, which is part of the `v6.2.0` compilation) up and running.

### Install

```bash
$ composer require oxid-esales/graphql-catalogue --no-update
$ composer update
```

If you didn't have the `oxid-esales/graphql-base` module installed, composer will do that for you.

After requiring the module, you need to activate it, either via OXID eShop admin or CLI.

```bash
$ ./bin/oe-console oe:module:activate oe_graphql_base
$ ./bin/oe-console oe:module:activate oe_graphql_catalogue
```

### How to use

A good starting point is to check the [How to use section in the GraphQL Base Module](https://github.com/OXID-eSales/graphql-base-module/#how-to-use)

## Testing

### Linting, syntax check, static analysis and unit tests

```bash
$ composer test
```

### Integration/Acceptance tests

- install this module into a running OXID eShop
- change the `test_config.yml`
  - add `oe/graphql-catalogue` to the `partial_module_paths`
  - set `activate_all_modules` to `true`

```bash
$ ./vendor/bin/runtests
```

## Contributing

You like to contribute? 🙌 AWESOME 🙌\
Go and check the [contribution guidelines](CONTRIBUTING.md)

## Build with

- [GraphQLite](https://graphqlite.thecodingmachine.io/)

## License

GPLv3, see [LICENSE file](LICENSE).
