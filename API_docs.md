## API

### Authorization

Some endpoints need authorization with a token to access. Most notably the endpoints to create, update and delete
records. The User endpoint is also protected to keep user data private. API token keys can only be added by an administrator.

### product endpoints

#### see all products

```http
GET /api/v1/product
```

#### response

An array of all products.

```javascript
{
    "product_id": int, 
    "product_name": string,
    "product_type": string,
    "product_price": int|null,
    "product_price_bulk": int|null,
    "product_notes": int|null,
    "product_link": string,
    "supplier_id": int
}
```

#### Get single product

```http
GET /api/v1/product/{id}
```

#### Response

An array of one product.

```javascript
{
    "product_id": int, 
    "product_name": string,
    "product_type": string,
    "product_price": int|null,
    "product_price_bulk": int|null,
    "product_notes": int|null,
    "product_link": string,
    "supplier_id": int
}
```
