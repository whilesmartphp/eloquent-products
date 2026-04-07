# whilesmart/eloquent-products

Polymorphic product / service catalog for Laravel applications. Implements `Whilesmart\Invoices\Contracts\Invoiceable` so every product can be referenced as a line item by `whilesmart/eloquent-invoices`.

## Install

```bash
composer require whilesmart/eloquent-products
php artisan migrate
```

`whilesmart/eloquent-invoices` is a hard dependency — composer pulls it in automatically because every Product implements its `Invoiceable` contract.

## Use

Add `HasProducts` to any model that should own a catalog (Workspace, Organization, User, …):

```php
use Whilesmart\Products\Traits\HasProducts;

class Workspace extends Model
{
    use HasProducts;
}
```

Create products and services:

```php
$workspace->products()->create([
    'type'                => 'product',
    'sku'                 => 'SHN-30YR',
    'name'                => '30-year asphalt shingles',
    'default_unit'        => 'bundle',
    'default_price_cents' => 4200,
    'currency'            => 'USD',
]);

$workspace->products()->create([
    'type'                => 'service',
    'sku'                 => 'LBR-ROOF',
    'name'                => 'Roofing labour',
    'default_unit'        => 'hour',
    'default_price_cents' => 7500,
]);
```

Then drop them onto an invoice without retyping anything:

```php
$invoice->lineItems()->create([
    'invoiceable_type' => Product::class,
    'invoiceable_id'   => $shingles->id,
    'quantity'         => 12,
]);
```

The line item gets `description`, `default_unit`, and `default_price_cents` snapshotted from the product at create time.

## Endpoints

| Verb | Path | Notes |
|---|---|---|
| `GET` | `/api/products` | List, filter by `owner_type`+`owner_id`, `type`, `category`, `is_active`, search via `?q=` |
| `POST` | `/api/products` | Create |
| `GET` | `/api/products/{id}` | Show |
| `PUT` | `/api/products/{id}` | Update |
| `DELETE` | `/api/products/{id}` | Soft delete |

## Type enum

`Whilesmart\Products\Enums\ProductType`: `product` (physical / unit-priced) or `service` (time / rate-priced). Same model, single table.

## Schema

`products` table:

| column | type |
|---|---|
| id | bigint |
| owner_type / owner_id | morphs |
| type | string (enum) |
| sku | nullable string, unique per owner |
| name | string |
| description | text |
| default_unit | nullable string |
| default_price_cents / default_cost_cents | bigint |
| default_tax_rate | decimal(6,4) nullable |
| currency | char(3) |
| category | nullable string |
| is_active | boolean |
| metadata | json |
| timestamps + soft deletes | |

## Scope

This package does **one thing**: store the reusable definition of a product or service. Adjacent concerns live in their own packages:

- Inventory levels / stock counts → `whilesmart/eloquent-inventory` (later)
- Multi-tier / multi-currency price lists → `whilesmart/eloquent-pricing` (later)
- Bundles / kits → on top of the same products table, later

## Config

Publish with `php artisan vendor:publish --tag=products-config`. Override `register_routes`, `route_prefix`, `route_middleware`, and `table` via env or the published config file.
