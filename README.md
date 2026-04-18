# Logieagle-SIM-API

A RESTful backend API built with Laravel for managing products, categories, warehouses, and stock movements with a focus on **performance, concurrency safety, and clean architecture**.

---

## Tech Stack

* PHP 8.1+
* Laravel 10/11
* MySQL 8+
* Redis (for caching)

---

## Setup Info

### 1. Clone repository

```bash
git clone https://github.com/jaypatel2157/Logieagle-SIM-APIs.git
cd s
```

### 2. Install

```bash
composer install
```

### 3. Setup environment

```bash
cp .env.example .env
```

Update `.env`:

```
APP_URL=http://127.0.0.1:8000

DB_DATABASE=inventory
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### 4. Generate key

```bash
php artisan key:generate
```

### 5. Run migrations & seeders

```bash
php artisan migrate:fresh --seed
```

### 6. Start server

```bash
php artisan serve
```

---

## API Base URL

```
http://127.0.0.1:8000/api
```

---

## API Reference

### 1. Category Tree

```
GET /api/categories/tree
```

---

### 2. Product Listing

```
GET /api/products
```

Query Params:

* category_id
* warehouse_id
* available_only
* min_price
* max_price
* search
* sort_by (name, price, available_stock)
* sort_direction (asc, desc)
* per_page

---

### 3. Stock Adjustment

```
POST /api/stock/adjust
```

Example Body:

```json
{
  "product_id": 1,
  "warehouse_id": 1,
  "movement_type": "stock_in",
  "quantity": 10
}
```

Movement Types:

* stock_in
* stock_out
* reservation
* reservation_release

---

### 4. Inventory Summary

```
GET /api/inventory/summary
```

---

### 5. Movement History

```
GET /api/products/{id}/movements
```

Query Params:

* date_from
* date_to
* per_page

---

### 6. Low Stock Alert

```
GET /api/inventory/low-stock?threshold=10
```

---

## Caching Strategy

* Driver: Redis
* Naming Convention:

```
inventory:{resource}:{context}
```

Examples:

* inventory:categories:tree
* inventory:summary
* inventory:products:list:{hash}
* inventory:low-stock:threshold:{value}

### TTL

* Category Tree → 60 min
* Product Listing → 15 min
* Inventory Summary → 15 min
* Low Stock → 10 min

### Invalidation

On stock update:

* Clear inventory summary
* Clear low stock cache

---

## Query Optimization

* DB-level aggregation (SUM, GROUP BY)
* No PHP loops for reports
* Eager loading to prevent N+1
* Indexed columns:

  * category_id
  * sku (unique)
  * product_id + warehouse_id
  * moved_at

---

## Architecture

* Controllers are thin
* Business logic in Services
* Validation via Form Requests
* Responses via API Resources

---

## Concurrency Handling

Stock updates use:

```
DB::transaction + lockForUpdate()
```

This prevents race conditions and ensures correct stock values.

---

## Error Handling

* Validation → 422
* Business Rule → 422
* Server Error → 500

---

## Seeding

Includes:

* Nested categories
* 50 products
* 3 warehouses
* 200 stock movements

Edge cases:

* Zero stock products
* Multi-warehouse products
* Empty categories
* Inactive parent category

---

## Testing

Use Postman collection provided.
