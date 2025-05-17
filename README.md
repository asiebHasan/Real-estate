# 🏡 Real Estate API (Laravel)

A RESTful API built with Laravel for a real estate platform. Supports user authentication, property management, favorites, and bookings.

---

## 🚀 Features
- **🔐 Authentication**: Register, Login, Logout using Laravel Sanctum
- **🏘 Properties**: CRUD operations, image uploads, filtering
- **⭐ Favorites**: Add/remove favorite properties
- **📅 Bookings**: Schedule and manage property visits
- **👤 User Dashboard**: View properties, favorites, and bookings

---

## 📦 Installation

1. Clone and install dependencies:
```bash
git clone https://github.com/your-username/real-estate-api.git
cd real-estate-api
composer install
cp .env.example .env
php artisan key:generate
```
## 🔧 Database Setup

1. Configure your database credentials in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=realestate_db  # Create this database first
DB_USERNAME=root           # Your MySQL username
DB_PASSWORD=               # Your MySQL password
```
2. Run database migrations and seeders:
php artisan migrate         # Create database tables
php artisan db:seed         # Optional: Seed default data (admin/user)
php artisan storage:link    # Link storage for image uploads

3. Start the development server:
php artisan serve

Access API at: http://localhost:8000


## 🔐 Authentication  
Laravel Sanctum handles token-based API authentication.  

### 📑 Auth Endpoints  
| Method | Endpoint        | Description          | Auth Required |
|--------|-----------------|----------------------|---------------|
| `POST` | `/api/register` | User registration    | No            |
| `POST` | `/api/login`    | User login           | No            |
| `POST` | `/api/logout`   | User logout          | Yes           |
| `GET`  | `/api/user`     | Get authenticated user | Yes       |

---

### Register New User  
```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```
Success Response (201 Created):
```json
{
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "1|abcdefgh123456..."
  }
}
```
User Login

```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password"
}
```
Success Response (200 OK):

```json
{
  "token": "1|abcdefgh123456...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

## ⭐ Favorites

### 📌 Favorite Routes
| Method | Endpoint                          | Auth | Description               |
|--------|-----------------------------------|------|---------------------------|
| `POST` | `/api/user/favorite/{propertyId}` | Yes  | Toggle favorite status    |
| `GET`  | `/api/user/favorites`             | Yes  | List user favorites       |

---

### Toggle Favorite
```http
POST /api/user/favorite/5  # Replace 5 with property ID
Authorization: Bearer <token>
```
Success Response (200 OK):

```json
{
  "message": "Property added to favorites",
  "favorites_count": 8
}
```
Error Response (404 Not Found):

```json
{
  "error": "Property not found"
}
```
List Favorites
```http
GET /api/user/favorites
Authorization: Bearer <token>
```
Success Response (200 OK):

```json
{
  "data": [
    {
      "id": 5,
      "title": "Beachfront Villa",
      "price": 450000,
      "city": "Miami",
      "is_favorited": true
    }
  ]
}
```
## 📅 Bookings

### 📌 Booking Endpoints
| Method | Endpoint                          | Auth | Description                          | Parameters                   |
|--------|-----------------------------------|------|--------------------------------------|------------------------------|
| `GET`  | `/api/bookings`                   | Yes  | List all user's bookings            | `?status=pending`            |
| `POST` | `/api/bookings`                   | Yes  | Create new booking                   | -                            |
| `GET`  | `/api/bookings/{id}`              | Yes  | Get specific booking details         | -                            |
| `PUT`  | `/api/bookings/{id}`              | Yes  | Update booking                       | -                            |
| `DELETE` | `/api/bookings/{id}`            | Yes  | Cancel booking                       | -                            |
| `GET`  | `/api/properties/{id}/bookings`   | Yes  | Get bookings for specific property   | `?start_date=2025-06-01`     |

---

### Create Booking
```http
POST /api/bookings
Authorization: Bearer <token>
Content-Type: application/json

{
  "property_id": 5,
  "meeting_time": "2025-06-01 14:00:00",
  "notes": "Will bring 2 guests",
  "contact_number": "+1234567890"
}
```
Success Response (201 Created):

```json
{
  "id": 15,
  "user_id": 3,
  "property_id": 5,
  "meeting_time": "2025-06-01T14:00:00.000000Z",
  "status": "pending",
  "notes": "Will bring 2 guests",
  "created_at": "2024-03-15T09:30:00.000000Z"
}

```
Validation Errors (422 Unprocessable Content):

```json
{
  "message": "Validation failed",
  "errors": {
    "property_id": ["The selected property is not available for booking"],
    "meeting_time": ["Meeting time must be at least 24 hours in advance"]
  }
}
```
List Bookings with Filters
```http
GET /api/bookings?status=confirmed&start_date=2025-06-01
Authorization: Bearer <token>
```
Success Response (200 OK):

```json
{
  "data": [
    {
      "id": 15,
      "property": {
        "id": 5,
        "title": "Luxury Penthouse",
        "main_image": "/storage/penthouse.jpg"
      },
      "meeting_time": "2025-06-01T14:00:00Z",
      "status": "confirmed",
      "owner_notes": "Please ring bell #3"
    }
  ],
  "meta": {
    "total": 1,
    "current_page": 1
  }
}
```

Update Booking (Partial Update)
```http
PUT /api/bookings/15
Authorization: Bearer <token>
Content-Type: application/json

{
  "meeting_time": "2025-06-01 15:30:00",
  "notes": "Delayed by 1.5 hours"
}
```

Success Response (200 OK):

```json
{
  "id": 15,
  "meeting_time": "2025-06-01T15:30:00.000000Z",
  "status": "modified",
  "updated_at": "2024-03-15T10:15:00.000000Z"
}
```

403 Forbidden Response (Non-owner):

```json
{
  "error": "You can only modify your own bookings"
}
```
Property-specific Bookings
```http
GET /api/properties/5/bookings?start_date=2025-06-01&end_date=2025-06-30
Authorization: Bearer <token>
```
Success Response (200 OK):

```json
{
  "data": [
    {
      "id": 15,
      "user": {
        "name": "Sarah Wilson",
        "contact": "+1234567890"
      },
      "meeting_time": "2025-06-01T15:30:00Z",
      "status": "confirmed"
    }
  ]
}
```

👩‍💻 Contributing
1. Fork it

2. Create your feature branch (git checkout -b feature/YourFeature)

3. Commit your changes (git commit -m 'Add new feature')

4. Push to the branch (git push origin feature/YourFeature)

5. Open a Pull Request

📝 License
Distributed under the MIT License. See LICENSE for more information.

#Made with ❤️ by Asieb Hasan






