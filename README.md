# Transaction / Expense Tracker API

A RESTful API built with **Laravel 12.x** for managing income and expense transactions.
Users can categorize transactions, apply filters by date and type, and view summary statistics.
Authentication is handled via **Laravel Sanctum** with role-based authorization.

---

## Authentication (Sanctum)

All protected endpoints require:

Authorization: Bearer {token}

### Register
POST /api/v1/auth/register

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
}
```

### Login
POST /api/v1/auth/login

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```


### Logout
POST /api/v1/auth/logout

---

## User API

GET /api/v1/user/me  
PUT /api/v1/user/me

GET /api/v1/user/1 (admin)

PUT /api/v1/user/1 (admin)

DELETE /api/v1/user/1 (admin)

---

## Category API

GET /api/v1/categories  
POST /api/v1/categories  
GET /api/v1/categories/{id}  
PUT /api/v1/categories/{id}  
DELETE /api/v1/categories/{id}

---

## Transaction API

GET /api/v1/transactions

Filters:
?type=income|expense  
?category_id=1  
?from=2024-01-01  
?to=2024-12-31

POST /api/v1/transactions  
GET /api/v1/transactions/{id}  
PUT /api/v1/transactions/{id}  
DELETE /api/v1/transactions/{id}

---

## Statistics API

GET /api/v1/stats/summary

Returns:
- total_income
- total_expense
- balance

Admin can pass user_id to view stats for a specific user.

---

## Success response

```json
{
  "success": true,
  "data": {
      "token": "SANCTUM_TOKEN", 
      "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "role": "user"
      }
  },
  "error": null
}
```

---

## Error response

```json
{
  "success": false,
  "data": null,
  "error": {
    "errorId": 403,
    "isFriendly": true,
    "errorMsg": "Unauthorized", 
    "details": []
  }
}
```

---
