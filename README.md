# KILT PHP SDK

This is a first draft of a partial wrapper around the [KILT JavaScript / TypeScript SDK](https://github.com/KILTprotocol/sdk-js/)

## Documentation

Check the [official KILT website](https://kilt.io) or the KILT [documentation hub](https://docs.kilt.io) to explore what KILT can offer to new and existing projects.

## How to install the SDK

Install the KILT-SDK by running the following commands:

```bash
composer config repositories.dwellir-public/kilt-sdk vcs https://github.com/dwellir-public/kilt-sdk
composer require dwellir-public/kilt-sdk @dev
cd vendor/dwellir-public/kilt-sdk
composer install
cd ../../..
```

## Execute your first code

```bash
cp vendor/dwellir-public/kilt-sdk/samples/cli.php .
sed -i 's/\/\.\.//' cli.php
php cli.php
```
