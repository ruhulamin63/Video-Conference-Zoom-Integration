## Video Conference Zoom Integration

This Laravel application provides seamless integration with Zoom Video Conferencing API to create, manage, and join Zoom meetings directly from your application.

### Features

- Create and schedule Zoom meetings
- Manage meeting participants
- Generate meeting join URLs
- Meeting status tracking
- Meeting duration management
- Password-protected meetings

## Setup Instructions

### Prerequisites

- PHP 8.2 or higher
- Composer
- Laravel 12.x
- Zoom Pro account or higher
- Active Zoom App with required permissions

### 1. Zoom App Configuration

1. Go to [Zoom App Marketplace](https://marketplace.zoom.us/)
2. Sign in with your Zoom account
3. Click "Develop" → "Build App"
4. Choose "Server-to-Server OAuth" app type
5. Fill in the required app information:
   - App Name: Your application name
   - Company Name: Your company
   - Developer Email: Your email address

### 2. Get Zoom API Credentials

After creating your Zoom app:

1. Navigate to the "App Credentials" tab
2. Copy the following credentials:
   - **Account ID**
   - **Client ID** 
   - **Client Secret**

### 3. Environment Configuration

1. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```

2. Add your Zoom credentials to the `.env` file:
   ```env
   ZOOM_ACCOUNT_ID=your_account_id_here
   ZOOM_CLIENT_ID=your_client_id_here
   ZOOM_CLIENT_SECRET=your_client_secret_here
   ```

### 4. Installation Steps

1. Clone the repository:
   ```bash
   git clone https://github.com/ruhulamin63/Video-Conference-Zoom-Integration.git
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Generate application key:
   ```bash
   php artisan key:generate
   ```

4. Run database migrations:
   ```bash
   php artisan migrate
   ```

5. (Optional) Seed the database:
   ```bash
   php artisan db:seed
   ```

### 5. Zoom API Permissions

Ensure your Zoom app has the following scopes enabled:

- `meeting:write:admin` - Create meetings
- `meeting:read:admin` - Read meeting details
- `meeting:update:admin` - Update meetings
- `meeting:delete:admin` - Delete meetings
- `user:read:admin` - Read user information

### 6. Testing the Integration

1. Start the development server:
   ```bash
   php artisan serve
   ```

2. Test the Zoom integration by creating a meeting through your application

### Available Models and Traits

- **ZoomMeeting Model**: Handles meeting data and relationships
- **ZoomMeetingTrait**: Provides Zoom API integration methods
- **Migration**: Creates `zoom_meetings` table with required fields

### Configuration Files

- `config/zoom.php` - Zoom API configuration
- `app/Models/ZoomMeeting.php` - Meeting model
- `app/Traits/ZoomMeetingTrait.php` - Zoom API methods

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
