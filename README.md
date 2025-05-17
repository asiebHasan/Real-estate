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
