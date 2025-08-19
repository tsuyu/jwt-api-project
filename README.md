# JWT API Project

A complete JWT (JSON Web Token) authentication system built with Laravel, featuring a robust API backend and a Bootstrap-powered frontend demo application.

## Project Structure

```
jwt-api-project/
├── app/                    # Laravel application files
├── config/                 # Configuration files
├── database/              # Database migrations and seeders
├── routes/                # API and web routes
├── frontend-demo/         # Frontend demo Laravel application
├── public/                # Public assets
├── resources/             # Views, assets, and language files
├── storage/               # Application storage
├── tests/                 # Test files
└── vendor/                # Composer dependencies
```

## Features

### JWT API Backend
- **User Registration**: Create new user accounts with validation
- **User Authentication**: Login with username/password and receive JWT tokens
- **Token Management**: Refresh and invalidate JWT tokens
- **Protected Routes**: Secure API endpoints requiring authentication
- **User Profile**: Retrieve authenticated user information
- **CORS Support**: Cross-origin resource sharing enabled
- **Comprehensive Error Handling**: Detailed error responses

### Frontend Demo Application
- **Interactive UI**: Bootstrap-powered interface for testing API endpoints
- **Real-time Token Display**: Shows current JWT token status
- **API Response Visualization**: Formatted JSON responses
- **Complete Workflow**: Registration → Login → Protected Access → Logout
- **Error Handling**: User-friendly error messages

## API Endpoints

### Public Endpoints
- `POST /api/auth/register` - Register a new user
- `POST /api/auth/login` - Authenticate user and get JWT token

### Protected Endpoints (Require JWT Token)
- `GET /api/auth/me` - Get authenticated user information
- `POST /api/auth/refresh` - Refresh JWT token
- `POST /api/auth/logout` - Logout and invalidate token
- `GET /api/user` - Get user details
- `GET /api/protected` - Example protected route

## Installation & Setup

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL or SQLite
- Node.js and npm (optional, for asset compilation)

### Backend Setup

1. **Clone and Install Dependencies**:
   ```bash
   git clone <repository-url> jwt-api-project
   cd jwt-api-project
   composer install
   ```

2. **Environment Configuration**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Setup**:
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=jwt_api
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Run Migrations**:
   ```bash
   php artisan migrate
   ```

5. **Generate JWT Secret**:
   ```bash
   php artisan jwt:secret
   ```

6. **Start the API Server**:
   ```bash
   php artisan serve
   ```
   The API will be available at `http://localhost:8000`

### Frontend Demo Setup

1. **Navigate to Frontend Directory**:
   ```bash
   cd frontend-demo
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   ```

3. **Environment Setup**:
   The `.env` file is pre-configured with file-based sessions to avoid database dependencies.

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Start the Frontend Server**:
   ```bash
   php artisan serve --port=8001
   ```
   The frontend demo will be available at `http://localhost:8001`

## Usage Guide

### Using the API Directly

#### 1. Register a New User
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### 2. Login and Get Token
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "johndoe",
    "password": "password123"
  }'
```

#### 3. Access Protected Route
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

### Using the Frontend Demo

1. **Start Both Servers**:
   - Backend API: `php artisan serve` (port 8000)
   - Frontend Demo: `cd frontend-demo && php artisan serve --port=8001`

2. **Open Browser**: Navigate to `http://localhost:8001`

3. **Test the Workflow**:
   - Register a new user
   - Login with credentials
   - View the JWT token
   - Test protected routes
   - Refresh the token
   - Logout

## Configuration

### JWT Configuration
The JWT settings can be modified in `config/jwt.php`:
- Token TTL (Time To Live)
- Refresh TTL
- Algorithm settings
- Blacklist settings

### CORS Configuration
CORS settings are in `config/cors.php` and allow cross-origin requests for API access.

### API Rate Limiting
Rate limiting is configured in `app/Http/Kernel.php` and can be adjusted as needed.

## Security Features

- **Password Hashing**: Bcrypt hashing for secure password storage
- **JWT Token Security**: Secure token generation and validation
- **Token Blacklisting**: Invalidated tokens are blacklisted
- **Input Validation**: Comprehensive request validation
- **CORS Protection**: Controlled cross-origin access
- **Rate Limiting**: API endpoint protection against abuse

## Testing

### Run Backend Tests
```bash
php artisan test
```

### Manual Testing with Frontend Demo
Use the frontend demo application to interactively test all API endpoints with a user-friendly interface.

## API Response Format

All API responses follow a consistent format:

**Success Response:**
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... },
  "token": "jwt_token_here" // when applicable
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Error description",
  "errors": { ... } // validation errors when applicable
}
```

## Technology Stack

### Backend
- **Laravel 12**: PHP framework
- **JWT-Auth**: JSON Web Token authentication
- **MySQL/SQLite**: Database options
- **Guzzle**: HTTP client library

### Frontend Demo
- **Laravel 12**: PHP framework
- **Bootstrap 5.3**: CSS framework (CDN)
- **jQuery 3.6**: JavaScript library
- **File-based Sessions**: No database dependency

## Troubleshooting

### Common Issues

1. **JWT Secret Not Set**:
   ```bash
   php artisan jwt:secret
   ```

2. **Database Connection Issues**:
   - Check `.env` database credentials
   - Ensure database server is running
   - Run `php artisan migrate`

3. **CORS Issues**:
   - Check `config/cors.php` settings
   - Ensure frontend URL is allowed

4. **Token Expiration**:
   - Use the refresh endpoint to get new tokens
   - Check JWT TTL settings in config

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues and questions:
1. Check the troubleshooting section
2. Review the API documentation
3. Test with the frontend demo application
4. Create an issue in the repository

---

**Note**: This project demonstrates a complete JWT authentication system with both backend API and frontend implementation. The frontend demo provides an interactive way to test and understand the API functionality.