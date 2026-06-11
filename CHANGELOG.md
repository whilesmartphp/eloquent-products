# Changelog

All notable changes to `whilesmart/eloquent-products` are documented here.

## [1.0.0] - 2026-06-11

- Initial release
- `Product` model with polymorphic `owner`, scoped per owner via owner-access
- `ProductType` enum: product, service
- Catalog fields: sku, name, description, default unit, default price/cost cents, default tax rate, currency, category, active flag, metadata
- `HasProducts` trait exposing `products()` for owner-side models
- Implements the invoices `Invoiceable` contract so a product can be added to an invoice as a line item
- Auto-registered API routes: `apiResource products`
- Factory for testing
