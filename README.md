# 🔗 SaaS Link Management API

A robust, multi-tenant RESTful API built with **Laravel** for managing and sharing links in a SaaS environment. Each user has full control over their own links, with features like status toggling, rate limiting, and secure authentication.

---

## ✨ Features

- 🔐 **Authentication** — Secure user registration and login with token-based auth (Laravel Sanctum)
- 🔗 **Link Management** — Full CRUD operations for user-owned links
- 🔄 **Status Toggling** — Activate or deactivate links with a built-in rate limit (once every 2 days)
- 👤 **Multi-Tenant** — Each user can only access and manage their own links
- 🛡️ **Authorization** — Protected routes ensure data isolation between users
- 📦 **Consistent API Responses** — Unified JSON response structure across all endpoints

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel (PHP) |
| Authentication | Laravel Sanctum |
| Database | MySQL |
| API Format | RESTful JSON |

---

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.1
- Composer
- MySQL
- Laravel CLI

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/Abdelrahman-Abdullah/SaaS-Link-Management-API.git
cd SaaS-Link-Management-API

# 2. Install dependencies
composer install

# 3. Set up environment
cp .env.example .env
php artisan key:generate

# 4. Configure your database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# 5. Run migrations
php artisan migrate

# 6. Start the server
php artisan serve
```

---

## 📡 API Endpoints

All endpoints are prefixed with `/api`. Protected routes require a `Bearer` token in the `Authorization` header.

### Auth

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| `POST` | `/api/register` | Register a new user | ❌ |
| `POST` | `/api/login` | Login and receive token | ❌ |
| `POST` | `/api/logout` | Revoke current token | ✅ |

### Links

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| `GET` | `/api/links` | List all user's links | ✅ |
| `POST` | `/api/links` | Create a new link | ✅ |
| `GET` | `/api/links/{id}` | Get a specific link | ✅ |
| `PUT` | `/api/links/{id}` | Update a link | ✅ |
| `DELETE` | `/api/links/{id}` | Delete a link | ✅ |
| `PATCH` | `/api/links/{id}/toggle-status` | Toggle link active/inactive status | ✅ |

---

## 📋 Example Requests

### Register
```http
POST /api/register
Content-Type: application/json

{
  "name": "Abdelrahman",
  "email": "abdelrahman@example.com",
  "password": "secret123",
  "password_confirmation": "secret123"
}
```

### Create a Link
```http
POST /api/links
Authorization: Bearer {your_token}
Content-Type: application/json

{
  "title": "My Portfolio",
  "url": "https://myportfolio.com"
}
```

### Toggle Link Status
```http
PATCH /api/links/1/toggle-status
Authorization: Bearer {your_token}
```

**Response:**
```json
{
  "message": "Link status updated successfully",
  "is_active": "active"
}
```

> ⚠️ **Rate Limit:** Link status can only be toggled **once every 2 days**.

---

## 🗂️ Project Structure

```
app/
├── Http/
│   ├── Controllers/        # API controllers
│   ├── Requests/           # Form request validation
│   └── Middleware/         # Custom middleware
├── Models/                 # Eloquent models (User, Link)
├── Traits/                 # Reusable traits (e.g. ApiResponse)
database/
├── migrations/             # Database schema
routes/
└── api.php                 # All API routes
```

---

## 🔒 Authorization & Rate Limiting

- All link operations are scoped to the **authenticated user** — users cannot access each other's links.
- The **toggle status** endpoint is rate-limited to **once every 2 days** per link to prevent abuse.

---

## 🤝 Contributing

Contributions are welcome! Please fork the repository and open a pull request with a clear description of your changes.

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Author

**Abdelrahman Abdullah**  
[GitHub](https://github.com/Abdelrahman-Abdullah)
