# 📖 SaaS Link Management API — Full Documentation

**Base URL:** `http://127.0.0.1:8000`  
**Format:** All requests and responses are JSON  
**Auth:** Protected routes require `Authorization: Bearer {token}` header  
**Collection:** [![Run in Postman](https://run.pstmn.io/button.svg)](https://go.postman.co/collection/26232780-bf5cf15b-c976-47a1-8643-0355a2eeeacc?source=https://documenter.getpostman.com/view/26232780/2sBXcGDeya)

---

## 📌 Response Structure

All responses follow this unified structure:

```json
{
  "message": "Human readable message",
  "status": "success | error | 200 | 422 ...",
  "data": { }
}
```

---

## 🔑 Authentication

### Register a New User

**POST** `/api/register`

Creates a new user account.

**Headers**
| Key | Value |
|-----|-------|
| Accept | application/json |

**Body**
```json
{
  "name": "abdelrahman",
  "email": "abdelrahman@example.com",
  "password": "123456789",
  "password_confirmation": "123456789"
}
```

**Responses**

✅ `200 OK` — Successfully registered
```json
{
  "message": "User registered successfully",
  "status": 201,
  "data": []
}
```

❌ `422` — Email already taken
```json
{
  "message": "Validation Failed",
  "status": 422,
  "data": {
    "email": ["The email has already been taken."]
  }
}
```

❌ `422` — All fields required
```json
{
  "message": "Validation Failed",
  "status": 422,
  "data": {
    "name": ["The name field is required."],
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

❌ `422` — Password too short or not confirmed
```json
{
  "message": "Validation Failed",
  "status": 422,
  "data": {
    "password": [
      "The password field must be at least 8 characters.",
      "The password field confirmation does not match."
    ]
  }
}
```

---

### Login

**POST** `/api/login`

Authenticates the user and returns a Bearer token.

**Headers**
| Key | Value |
|-----|-------|
| Accept | application/json |

**Body**
```json
{
  "email": "abdelrahman@example.com",
  "password": "123456789"
}
```

**Responses**

✅ `200 OK` — Successfully logged in
```json
{
  "message": "Login successful",
  "status": "success",
  "data": {
    "user": {
      "name": "abdelrahman",
      "email": "abdelrahman@example.com"
    },
    "access_token": "5|kHzkxGU6AF2fp5ZHbmlyvCTy...",
    "token_type": "Bearer"
  }
}
```

❌ `422` — Missing fields
```json
{
  "message": "Validation Failed",
  "status": 422,
  "data": {
    "email": ["The email field is required."]
  }
}
```

❌ `401` — Wrong credentials
```json
{
  "message": "Invalid email or password",
  "status": "error",
  "data": []
}
```

---

### Logout

**POST** `/api/logout` 🔒

Revokes the current user's token.

**Headers**
| Key | Value |
|-----|-------|
| Accept | application/json |
| Authorization | Bearer {token} |

**Responses**

✅ `200 OK`
```json
{
  "message": "Logout successful",
  "status": "success",
  "data": []
}
```

❌ `401` — No token provided
```json
{
  "message": "Unauthenticated."
}
```

---

## 🔒 Forgot Password

A 3-step flow: **Request code → Verify code → Reset password**

---

### Step 1 — Request Reset Code

**POST** `/api/forgot-password`

Sends a 6-digit reset code to the user's email.

**Body**
```json
{
  "email": "abdelrahman@example.com"
}
```

**Responses**

✅ `200 OK` — Code sent
```json
{
  "message": "Password reset code sent to your email.",
  "status": 200,
  "data": []
}
```

⚠️ `200` — Code already sent and not expired yet
```json
{
  "message": "A password reset code was already sent. Please check your inbox.",
  "status": 200,
  "data": []
}
```

---

### Step 2 — Verify Reset Code

**POST** `/api/forgot-password/verify`

Verifies the 6-digit code. Returns a `verify_token` to use in Step 3.

**Body** (form-data)
| Key | Value |
|-----|-------|
| email | abdelrahman@example.com |
| code | 095580 |

**Responses**

✅ `200 OK` — Code verified
```json
{
  "message": "Code verified successfully. You can now reset your password.",
  "status": 200,
  "data": {
    "verify_token": "468cf3b71195ede0367333305837ebcad83ae1a4..."
  }
}
```

❌ `422` — Code field required
```json
{
  "message": "The code field is required.",
  "errors": {
    "code": ["The code field is required."]
  }
}
```

❌ `422` — Code must be exactly 6 digits
```json
{
  "message": "The code field must not be greater than 6 characters.",
  "errors": {
    "code": ["The code field must not be greater than 6 characters."]
  }
}
```

❌ `404` — No reset request found for this email
```json
{
  "message": "No password reset request found for this email.",
  "status": 404,
  "data": []
}
```

❌ `422` — Wrong code
```json
{
  "message": "Invalid reset code. Please check the code and try again.",
  "status": 422,
  "data": []
}
```

---

### Step 3 — Set New Password

**POST** `/api/reset-password`

Sets a new password using the `verify_token` from Step 2.

**Body** (form-data)
| Key | Value |
|-----|-------|
| verify_token | 468cf3b71195ede... |
| password | newpassword123 |
| password_confirmation | newpassword123 |

**Responses**

✅ `200 OK`
```json
{
  "message": "Password reset successfully. You can now log in with your new password.",
  "status": 200,
  "data": []
}
```

❌ `422` — Missing required fields
```json
{
  "message": {
    "verify_token": ["The verify token field is required."],
    "password": ["The password field is required."]
  },
  "status": 422,
  "data": []
}
```

❌ `422` — Password too short
```json
{
  "message": {
    "password": ["The password field must be at least 8 characters."]
  },
  "status": 422,
  "data": []
}
```

❌ `422` — Passwords don't match
```json
{
  "message": {
    "password": ["The password field confirmation does not match."],
    "password_confirmation": ["The password confirmation field must match password."]
  },
  "status": 422,
  "data": []
}
```

❌ `422` — Token wrong or expired
```json
{
  "message": "Invalid or expired verification token.",
  "status": 422,
  "data": []
}
```

---

## 🔗 Links

### Generate a Short Link

**POST** `/api/generate` 🔒

Creates a new short link for the authenticated user.

**Headers**
| Key | Value |
|-----|-------|
| Accept | application/json |
| Authorization | Bearer {token} |

**Body**
```json
{
  "original_url": "https://facebook.com",
  "custom_alias": "my-facebook",
  "title": "Facebook"
}
```

| Field | Required | Description |
|-------|----------|-------------|
| `original_url` | ✅ Yes | The URL to shorten |
| `custom_alias` | ❌ Optional | A unique custom alias |
| `title` | ❌ Optional | A display name for the link |

**Responses**

✅ `201 Created`
```json
{
  "message": "Short link created successfully",
  "status": "success",
  "data": {
    "id": 4,
    "original_link": "https://facebook.com",
    "short_code": "u6L1fn",
    "title": "Facebook",
    "custom_alias": "my-facebook",
    "clicks_count": 0,
    "visits": []
  }
}
```

❌ `422` — Custom alias already taken
```json
{
  "message": "Validation failed",
  "status": "error",
  "data": {
    "custom_alias": ["The custom alias has already been taken."]
  }
}
```

❌ `422` — Original URL required
```json
{
  "message": "Validation failed",
  "status": "error",
  "data": {
    "original_url": ["The original url field is required."]
  }
}
```

---

### Get All Links

**GET** `/api/links` 🔒

Returns all links belonging to the authenticated user, including their click history.

**Headers**
| Key | Value |
|-----|-------|
| Accept | application/json |
| Authorization | Bearer {token} |

**Responses**

✅ `200 OK`
```json
{
  "message": "Links retrieved successfully",
  "status": "success",
  "data": [
    {
      "id": 1,
      "original_link": "https://google.com",
      "short_code": "6nBf9w",
      "title": "Google",
      "custom_alias": "my-google",
      "clicks_count": 2,
      "visits": [
        {
          "id": 1,
          "user_agent": "Mozilla/5.0...",
          "city": "Cairo",
          "country": "Egypt",
          "region": "Cairo Governorate",
          "device_type": "WebKit",
          "browser": "Chrome",
          "platform": "Windows",
          "clicked_at": "22 hours ago"
        }
      ]
    }
  ]
}
```

❌ `404` — No links found
```json
{
  "message": "No links found for the user",
  "status": "success",
  "data": []
}
```

---

### Toggle Link Status

**POST** `/api/toggle-link/{id}` 🔒

Toggles a link between active and inactive. Rate limited to **once every 2 days**.

**Headers**
| Key | Value |
|-----|-------|
| Accept | application/json |
| Authorization | Bearer {token} |

**Responses**

✅ `200 OK`
```json
{
  "message": "Link status updated successfully",
  "status": "success",
  "data": {
    "is_active": "inactive"
  }
}
```

❌ `403` — Too soon to toggle again
```json
{
  "message": "You can only toggle the link status once every 2 days",
  "status": "success",
  "data": []
}
```

❌ `404` — Link not found
```json
{
  "message": "Link not found",
  "status": "success",
  "data": []
}
```

---

### Delete a Link

**DELETE** `/api/delete-link/{id}` 🔒

Permanently deletes a link and all its click data.

**Headers**
| Key | Value |
|-----|-------|
| Accept | application/json |
| Authorization | Bearer {token} |

**Responses**

✅ `200 OK`
```json
{
  "message": "Link deleted successfully",
  "status": "success",
  "data": []
}
```

❌ `404` — Link not found
```json
{
  "message": "Link not found",
  "status": "success",
  "data": []
}
```

---

## 🌍 Redirect

### Redirect to Original URL

**GET** `/{short_code}`

Resolves the short code, records click metadata, and returns the original URL.

> No authentication required. This endpoint is public.

**Responses**

✅ `200 OK`
```json
{
  "message": "Redirecting to original URL",
  "status": "success",
  "data": "https://google.com"
}
```

❌ `404` — Invalid short code
```json
{
  "message": "Link not found",
  "status": "error",
  "data": []
}
```

---

## 📊 Analytics

### Global Overview

**GET** `/api/overview` 🔒

Returns a summary across all the user's links including top performers, peak activity hours, and referrers.

**Headers**
| Key | Value |
|-----|-------|
| Authorization | Bearer {token} |

**Responses**

✅ `200 OK`
```json
{
  "message": "Analytics overview retrieved successfully",
  "status": "success",
  "data": {
    "total_links": 1,
    "active_link": 0,
    "inactive_link": 1,
    "total_clicks": 10,
    "best_performing_link": {
      "id": 1,
      "title": "Google",
      "short_code": "6nBf9w",
      "original_url": "https://google.com",
      "clicks": 10
    },
    "top_five_links": [
      {
        "id": 1,
        "title": "Google",
        "short_code": "6nBf9w",
        "original_url": "https://google.com",
        "clicks": 10
      }
    ],
    "peak_hours": [
      { "hour": 14, "total": 6 },
      { "hour": 15, "total": 4 }
    ],
    "top_referrers": [
      { "referrer": "http://localhost:5000/", "total": 4 }
    ]
  }
}
```

❌ `404` — No links found
```json
{
  "message": "No links found",
  "status": "success",
  "data": {
    "total_links": 0,
    "active_links": 0,
    "inactive_links": 0,
    "total_clicks": 0,
    "unique_clicks": 0,
    "best_performing_link": null
  }
}
```

---

### Clicks Over Time

**GET** `/api/clicks-over-time` 🔒

Returns click data grouped by day with a comparison against the previous equivalent period.

**Query Parameters**

| Param | Type | Required | Description |
|-------|------|----------|-------------|
| `period` | string | ❌ | `week` (default), `month`, or `year` |
| `from` | date | ❌ | Start date for custom range (e.g. `2026-01-01`) |
| `to` | date | ❌ | End date for custom range (e.g. `2026-01-31`) |

> Use either `period` OR `from`+`to`. If both are provided, `from`+`to` takes priority.

**Responses**

✅ `200 OK` — Weekly (default)
```http
GET /api/clicks-over-time
GET /api/clicks-over-time?period=week
```
```json
{
  "message": "Clicks over time retrieved successfully",
  "status": "success",
  "data": {
    "period": "week",
    "clicks_over_time": [
      { "date": "2026-02-23", "clicks": 1 },
      { "date": "2026-02-24", "clicks": 2 }
    ],
    "comparison": {
      "current_total": 3,
      "previous_total": 3,
      "growth_percentage": "0%"
    }
  }
}
```

✅ `200 OK` — Custom date range
```http
GET /api/clicks-over-time?from=2026-02-22&to=2026-02-24
```
```json
{
  "message": "Clicks over time retrieved successfully",
  "status": "success",
  "data": {
    "period": "custom",
    "clicks_over_time": [
      { "date": "2026-02-23", "clicks": 1 },
      { "date": "2026-02-24", "clicks": 5 }
    ],
    "comparison": {
      "current_total": 6,
      "previous_total": 0,
      "growth_percentage": "100%"
    }
  }
}
```

❌ `422` — Invalid period value
```json
{
  "message": "Validation failed",
  "status": "success",
  "data": {
    "period": ["The period must be one of the following: week, month, year."]
  }
}
```

❌ `404` — No links found
```json
{
  "message": "No links found",
  "status": "success",
  "data": {
    "period": "week",
    "clicks_over_time": []
  }
}
```

---

### Per-Link Deep Analytics

**GET** `/api/links/{id}` 🔒

Returns detailed analytics for a single link including countries, cities, browsers, platforms, devices, peak hours, referrers, and clicks over time.

**Responses**

✅ `200 OK` — With analytics data
```json
{
  "message": "Link analytics retrieved successfully",
  "status": "success",
  "data": {
    "link": {
      "id": 1,
      "title": "Google",
      "short_code": "6nBf9w",
      "original_url": "https://google.com",
      "is_active": 0,
      "total_clicks": 10
    },
    "analytics": {
      "top_countries": [
        { "country": "United States", "total": 10 }
      ],
      "top_cities": [
        { "city": "New Haven", "total": 9 },
        { "city": "Cairo", "total": 1 }
      ],
      "browsers": [
        { "browser": "Chrome", "total": 8 },
        { "browser": "Safari", "total": 2 }
      ],
      "platforms": [
        { "platform": "Windows", "total": 8 },
        { "platform": "iOS", "total": 2 }
      ],
      "peak_hours": [
        { "hour": 14, "total": 6 },
        { "hour": 15, "total": 4 }
      ],
      "top_referrers": [
        { "referrer": "http://localhost:5000/", "total": 4 }
      ],
      "clicks_over_time": [
        { "date": "2026-02-15", "total": 3 },
        { "date": "2026-02-23", "total": 1 },
        { "date": "2026-02-24", "total": 6 }
      ]
    }
  }
}
```

✅ `200 OK` — Link exists but has no clicks yet
```json
{
  "message": "No clicks recorded for this link yet",
  "status": "success",
  "data": {
    "link": {
      "id": 1,
      "title": "Google",
      "short_code": "6nBf9w",
      "original_url": "https://google.com",
      "is_active": 0,
      "total_clicks": 0,
      "unique_clicks": 0
    },
    "analytics": null
  }
}
```

❌ `404` — Link not found
```json
{
  "message": "Link not found",
  "status": "success",
  "data": []
}
```

---

### Recent Clicks Feed

**GET** `/api/recent-clicks` 🔒

Returns the most recent clicks across all user links, with link details attached.

**Query Parameters**

| Param | Type | Required | Default | Constraints |
|-------|------|----------|---------|-------------|
| `limit` | integer | ❌ | 10 | Min: 1, Max: 50 |

**Responses**

✅ `200 OK`
```json
{
  "message": "Recent clicks retrieved successfully",
  "status": "success",
  "data": [
    {
      "id": 1,
      "link_id": 1,
      "country": "United States",
      "city": "New Haven",
      "device_type": "WebKit",
      "browser": "Chrome",
      "platform": "Windows",
      "created_at": "2026-02-23T15:21:20.000000Z",
      "link": {
        "id": 1,
        "title": "Google",
        "short_code": "6nBf9w"
      }
    }
  ]
}
```

✅ `200 OK` — No clicks yet
```json
{
  "message": "Recent clicks retrieved successfully",
  "status": "success",
  "data": []
}
```

❌ `422` — Limit exceeds 50
```json
{
  "message": "Validation failed",
  "status": "success",
  "data": {
    "limit": ["Limit cannot exceed 50."]
  }
}
```

❌ `422` — Limit below 1
```json
{
  "message": "Validation failed",
  "status": "success",
  "data": {
    "limit": ["Limit must be at least 1."]
  }
}
```

❌ `422` — Limit must be a number
```json
{
  "message": "Validation failed",
  "status": "success",
  "data": {
    "limit": ["Limit must be a number."]
  }
}
```

---

## ⚡ Quick Reference

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/api/register` | ❌ | Register |
| POST | `/api/login` | ❌ | Login |
| POST | `/api/logout` | ✅ | Logout |
| POST | `/api/forgot-password` | ❌ | Request reset code |
| POST | `/api/forgot-password/verify` | ❌ | Verify reset code |
| POST | `/api/reset-password` | ❌ | Set new password |
| POST | `/api/generate` | ✅ | Generate short link |
| GET | `/api/links` | ✅ | Get all links |
| POST | `/api/toggle-link/{id}` | ✅ | Toggle link status |
| DELETE | `/api/delete-link/{id}` | ✅ | Delete link |
| GET | `/{code}` | ❌ | Redirect + record click |
| GET | `/api/overview` | ✅ | Analytics overview |
| GET | `/api/clicks-over-time` | ✅ | Clicks trend + comparison |
| GET | `/api/links/{id}` | ✅ | Per-link deep analytics |
| GET | `/api/recent-clicks` | ✅ | Recent clicks feed |
