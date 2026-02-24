# 🔗 SaaS Link Management API

A robust RESTful API built with **Laravel** for generating and managing short links with rich click analytics. Users can generate short URLs, track every click with detailed metadata, and gain insights through a comprehensive analytics system.

[![Run in Postman](https://run.pstmn.io/button.svg)](https://go.postman.co/collection/26232780-bf5cf15b-c976-47a1-8643-0355a2eeeacc?source=https://documenter.getpostman.com/view/26232780/2sBXcGDeya)

---

## ✨ Features

- 🔐 **Authentication** — Register, login, and logout via Laravel Sanctum
- 🔑 **Forgot Password** — Full 3-step reset flow (request → verify → reset)
- 🔗 **Short Link Generation** — Generate unique short codes for any URL with optional custom alias and title
- 🌍 **Smart Redirection** — Every click is captured and stored with rich metadata
- 🔄 **Link Status Toggle** — Activate or deactivate links (rate limited to once every 2 days)
- 👤 **Multi-Tenant** — Each user can only access and manage their own links
- 📦 **Consistent API Responses** — Unified JSON response structure across all endpoints
- 📊 **Analytics Dashboard** — Global overview, per-link deep analytics, time trends, period comparisons, and a live clicks feed

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel (PHP) |
| Authentication | Laravel Sanctum |
| Database | MySQL |
| Geo Location | `torann/geoip` |
| Device Detection | `jenssegers/agent` |
| API Format | RESTful JSON |

---

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.1
- Composer
- MySQL

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

## 📬 Postman Collection

Import the full collection to test all endpoints immediately with real saved responses.

[![Run in Postman](https://run.pstmn.io/button.svg)](https://go.postman.co/collection/26232780-bf5cf15b-c976-47a1-8643-0355a2eeeacc?source=https://documenter.getpostman.com/view/26232780/2sBXcGDeya)

Set the `base_url` variable in Postman to `http://127.0.0.1:8000` before running requests.

---

## 📡 API Endpoints

All endpoints are prefixed with `/api`. Protected routes 🔒 require `Authorization: Bearer {token}`.

### 🔑 Auth

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| `POST` | `/api/register` | Register a new user | ❌ |
| `POST` | `/api/login` | Login and receive token | ❌ |
| `POST` | `/api/logout` | Revoke current token | 🔒 |

### 🔐 Forgot Password

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| `POST` | `/api/forgot-password` | Send 6-digit reset code to email | ❌ |
| `POST` | `/api/forgot-password/verify` | Verify code and receive `verify_token` | ❌ |
| `POST` | `/api/reset-password` | Set new password using `verify_token` | ❌ |

### 🔗 Links

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| `GET` | `/api/links` | List all user's links with visits | 🔒 |
| `POST` | `/api/generate` | Generate a new short link | 🔒 |
| `POST` | `/api/toggle-link/{id}` | Toggle link active/inactive | 🔒 |
| `DELETE` | `/api/delete-link/{id}` | Delete a link | 🔒 |

### 🌍 Redirect

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| `GET` | `/{code}` | Redirect to original URL and record click | ❌ |

### 📊 Analytics

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| `GET` | `/api/overview` | Global summary across all links | 🔒 |
| `GET` | `/api/clicks-over-time` | Clicks per day + period comparison | 🔒 |
| `GET` | `/api/links/{id}` | Deep analytics for a single link | 🔒 |
| `GET` | `/api/recent-clicks` | Live feed of latest clicks | 🔒 |

---

## 📋 Key Examples

### Generate a Short Link
```http
POST /api/generate
Authorization: Bearer {token}
Content-Type: application/json

{
  "original_url": "https://google.com",
  "title": "Google",
  "custom_alias": "my-google"
}
```
```json
{
  "message": "Short link created successfully",
  "status": "success",
  "data": {
    "id": 1,
    "original_link": "https://google.com",
    "short_code": "6nBf9w",
    "title": "Google",
    "custom_alias": "my-google",
    "clicks_count": 0,
    "visits": []
  }
}
```

---

### Clicks Over Time — with Comparison
```http
GET /api/clicks-over-time?period=week
GET /api/clicks-over-time?from=2026-01-01&to=2026-01-31
```
```json
{
  "data": {
    "period": "week",
    "clicks_over_time": [
      { "date": "2026-02-23", "clicks": 1 },
      { "date": "2026-02-24", "clicks": 5 }
    ],
    "comparison": {
      "current_total": 6,
      "previous_total": 3,
      "growth_percentage": "100%"
    }
  }
}
```

---

### Analytics Overview
```http
GET /api/overview
Authorization: Bearer {token}
```
```json
{
  "data": {
    "total_links": 5,
    "active_link": 4,
    "inactive_link": 1,
    "total_clicks": 120,
    "best_performing_link": { "title": "Google", "clicks": 80 },
    "top_five_links": [...],
    "peak_hours": [{ "hour": 14, "total": 45 }],
    "top_referrers": [{ "referrer": "google.com", "total": 30 }]
  }
}
```

---

## 🗂️ Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── ForgetPasswordController.php
│   │   ├── RedirectController.php
│   │   ├── ShortLinkController.php
│   │   └── AnalyticsController.php
│   ├── Requests/
│   │   ├── Analytics/
│   │   │   ├── ClicksOverTimeRequest.php
│   │   │   └── RecentClicksRequest.php
│   │   └── ShortLikeGenerateRequest.php
│   └── Resources/
│       └── ShortLinkResource.php
├── Models/
│   ├── User.php
│   ├── Link.php
│   └── Click.php
├── Helpers/
│   └── ApiResponseHelper.php
database/
└── migrations/
    ├── create_users_table.php
    ├── create_links_table.php
    └── create_clicks_table.php
routes/
└── api.php
```

---

## 🔒 Security & Rate Limiting

- All link and analytics operations are **scoped to the authenticated user** — users cannot access each other's data.
- Link status toggle is **rate limited to once every 2 days** per link.
- Analytics query inputs (`period`, `limit`, `from`, `to`) are validated via dedicated Form Requests to prevent invalid or abusive values.

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
