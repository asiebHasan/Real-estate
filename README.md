🏡 Real Estate API (Laravel)

A RESTful API built with Laravel for a real estate platform. It supports user authentication, property management, favorites, and bookings.

🚀 Features

🔐 Authentication: Register, Login, Logout using Laravel Sanctum

🏘 Properties: CRUD operations, image uploads, filtering

⭐ Favorites: Add/remove favorite properties

📅 Bookings: Schedule and manage property visits

👤 User dashboard: View your properties, favorites, and bookings

📦 Installation

Clone and install dependencies:

git clone https://github.com/your-username/real-estate-api.git
cd real-estate-api
composer install
dotenv setup:
cp .env.example .env
php artisan key:generate

🔧 Database Setup

Configure your database in .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=realestate_db
DB_USERNAME=root
DB_PASSWORD=secret

Run migrations and seeders:

php artisan migrate
php artisan db:seed  # (optional) create default admin

Create storage symlink:

php artisan storage:link

Start local server:

php artisan serve
# Visit http://127.0.0.1:8000

🔐 Authentication

Sanctum provides token-based API auth.

Register

POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}

Login

POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password"
}

Response: { "token": "<plainTextToken>" }

Use Authorization: Bearer <token> for protected routes.

Logout

POST /api/logout
Authorization: Bearer <token>

🛠 API Endpoints

📑 Auth Routes

Method

Endpoint

Description

POST

/api/register

User registration

POST

/api/login

User login

POST

/api/logout

User logout (auth)

GET

/api/user

Get authenticated user

🏠 Property Routes

Method

Endpoint

Auth

Description

GET

/api/properties

No

List all properties

GET

/api/properties/{id}

No

Get property by ID

POST

/api/properties

Yes

Create property + upload images

POST

/api/properties/{id}

Yes

Update property (multipart)

DELETE

/api/properties/{id}

Yes

Delete property

GET

/api/user/properties

Yes

List properties by user

Property JSON Schema

{
  "title": "Cozy Apartment",
  "description": "Downtown with park view",
  "price": 150000,
  "address": "123 Main St",
  "city": "Metropolis",
  "state": "NY",
  "zipcode": "10001",
  "property_type": "apartment",
  "rent_or_sale": "sale",
  "bedrooms": 2,
  "bathrooms": 1,
  "square_feet": 850,
  "images": [file1, file2]
}

⭐ Favorites

Method

Endpoint

Auth

Description

POST

/api/user/favorite/{propertyId}

Yes

Toggle favorite status

GET

/api/user/favorites

Yes

List user favorites

📅 Booking Routes

Method

Endpoint

Auth

Description

GET

/api/bookings

Yes

List all bookings

POST

/api/bookings

Yes

Create a new booking

POST

/api/bookings/{id}

Yes

Update existing booking

DELETE

/api/bookings/{id}

Yes

Cancel a booking

GET

/api/bookings/{userId}

Yes

List bookings for specific user

GET

/api/bookings/property/{propertyId}

Yes

List bookings for specific property

Booking JSON Schema

{
  "property_id": 1,
  "meeting_time": "2025-06-01 10:00:00",
  "notes": "Looking forward to the tour",
  "contact_number": "5551234567"
}

🔧 Configuration

config/auth.php

'guards' => [
  'api' => [
    'driver' => 'sanctum',
    'provider' => 'users',
  ],
],

config/sanctum.php

'stateful' => [
  Sanctum::currentApplicationUrlWithPort(),
  Sanctum::currentRequestHost(),
  'localhost:3000',
],

📝 License

This project is open-source under the MIT license.

🚀 Contributing

Contributions welcome! Please fork, create feature branches, and submit pull requests.

