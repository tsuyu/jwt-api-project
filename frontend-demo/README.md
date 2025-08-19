# JWT API Frontend Demo

This Laravel application demonstrates how to interact with the JWT API backend using Bootstrap UI. It provides a user-friendly interface to test all the JWT authentication endpoints.

## Features

- **User Registration**: Register new users via the API
- **User Login**: Authenticate users and receive JWT tokens
- **Token Management**: Display current JWT token and clear it when needed
- **Protected Routes**: Access protected API endpoints using JWT tokens
- **User Information**: Retrieve authenticated user details
- **Token Refresh**: Refresh JWT tokens to extend session
- **User Logout**: Invalidate JWT tokens

## Setup Instructions

1. **Install Dependencies**:
   ```bash
   composer install
   ```

2. **Environment Configuration**:
   The `.env` file is already configured to use SQLite database and the app name is set to "JWT API Demo".

3. **Generate Application Key** (if not already done):
   ```bash
   php artisan key:generate
   ```

4. **Run Database Migrations**:
   ```bash
   php artisan migrate
   ```

5. **Start the Development Server**:
   ```bash
   php artisan serve --port=8001
   ```
   
   Note: We use port 8001 to avoid conflicts with the JWT API backend running on port 8000.

## Usage

1. **Start the JWT API Backend**:
   Make sure the JWT API backend is running on `http://localhost:8000`. From the main project directory:
   ```bash
   php artisan serve
   ```

2. **Access the Frontend Demo**:
   Open your browser and navigate to `http://localhost:8001`

3. **Test the API Endpoints**:
   - **Register**: Create a new user account
   - **Login**: Authenticate and receive a JWT token
   - **Get User Info**: Retrieve authenticated user details
   - **Access Protected Route**: Test protected API endpoints
   - **Refresh Token**: Renew your JWT token
   - **Logout**: Invalidate your JWT token

## API Endpoints Demonstrated

- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User authentication
- `GET /api/auth/me` - Get authenticated user info
- `GET /api/protected` - Access protected route
- `POST /api/auth/refresh` - Refresh JWT token
- `POST /api/auth/logout` - User logout

## Technology Stack

- **Laravel 12**: PHP framework
- **Bootstrap 5.3**: CSS framework (via CDN)
- **jQuery 3.6**: JavaScript library for AJAX requests
- **Guzzle HTTP**: For making API requests to the backend

## Key Features

- **External Bootstrap**: Uses Bootstrap via CDN instead of compiled assets
- **Real-time Token Display**: Shows the current JWT token and updates automatically
- **Response Visualization**: Displays API responses in formatted JSON
- **Error Handling**: Proper error handling for failed API requests
- **User-friendly Interface**: Clean, responsive design with clear sections for each API endpoint

## Notes

- The frontend communicates with the JWT API backend running on `http://localhost:8000`
- All API requests are proxied through Laravel controllers to handle CORS and provide a clean interface
- JWT tokens are automatically extracted from successful login/register responses
- The interface provides immediate feedback for all API operations