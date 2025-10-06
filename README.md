# Laravel Uptime Monitor

A comprehensive uptime monitoring application built with Laravel 12 and the TALL stack (Tailwind CSS, Alpine.js, Livewire, Laravel). This application provides multi-tenant website monitoring with support for HTTP pings, heartbeats, SSL certificate monitoring, and more.

## Features

### 🎯 Core Monitoring Features
- **HTTP Ping Monitoring** - Monitor website availability, response time, and status codes
- **Heartbeat Monitoring** - Track application health via webhook callbacks
- **SSL Certificate Monitoring** - Get alerts before certificates expire
- **Keyword Monitoring** - Check for presence or absence of specific content
- **Custom Check Intervals** - Configure monitoring frequency per monitor
- **Response Time Tracking** - Monitor average response times
- **Uptime Percentage** - Track 30-day uptime statistics

### 📊 Dashboard & UI
- Beautiful, responsive dashboard with dark mode support
- Real-time monitor status overview
- Recent incidents timeline
- Monitor management interface (create, edit, pause, delete)
- Public status pages for each tenant
- Visual uptime history with sparklines

### 🏢 Multi-Tenancy
- Full tenant isolation using Stancl Tenancy
- Separate databases per tenant
- Custom domains support
- Tenant-specific configurations

### 💳 Stripe Integration (Laravel Cashier)
- Multiple subscription tiers (Free, Pro, Enterprise)
- Usage-based billing
- Secure payment processing
- Subscription management

### 🔒 Authentication & Security
- Laravel Breeze authentication
- Email verification
- Password reset functionality
- Session management

### ✅ Testing
- Comprehensive feature tests
- Unit tests for monitoring services
- Authentication test suite
- 36+ passing tests

## Tech Stack

- **Laravel 12** - Latest Laravel framework
- **Livewire 3** - Dynamic reactive components
- **Alpine.js** - Lightweight JavaScript framework
- **Tailwind CSS v4** - Utility-first CSS framework
- **Stancl Tenancy** - Multi-tenant architecture
- **Laravel Cashier** - Stripe subscription management
- **Guzzle HTTP** - HTTP client for monitoring
- **SQLite/MySQL** - Database support

## Installation

### Requirements
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite or MySQL database

### Setup Steps

1. **Clone the repository**
```bash
git clone https://github.com/PeteBishwhip/laravel-uptime.git
cd laravel-uptime
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install JavaScript dependencies**
```bash
npm install
```

4. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure database**
Edit `.env` and set your database credentials:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

Or for MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_uptime
DB_USERNAME=root
DB_PASSWORD=
```

6. **Run migrations**
```bash
php artisan migrate
```

7. **Build assets**
```bash
npm run build
```

8. **Start the development server**
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## Usage

### Creating Monitors

1. **Register an account** at `/register`
2. **Navigate to Monitors** from the dashboard
3. **Click "Create Monitor"**
4. **Choose monitor type:**
   - **HTTP Ping** - Enter URL, method, expected status codes
   - **Heartbeat** - Configure expected interval and grace period
   - **SSL Certificate** - Enter HTTPS URL to monitor

### Setting Up Heartbeat Monitoring

For heartbeat monitors, send a POST request to:
```
POST https://your-domain.com/heartbeat/{monitor-id}
```

Example using curl:
```bash
curl -X POST https://your-domain.com/heartbeat/1
```

### Creating Status Pages

1. Navigate to **Status Pages** in the dashboard
2. Click **Create Status Page**
3. Configure your page settings
4. Add monitors to display
5. Share the public URL: `https://your-domain.com/status/{slug}`

### Running the Scheduler

The monitoring checks run via Laravel's scheduler. Add this to your crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Or run the scheduler in development:
```bash
php artisan schedule:work
```

### Queue Workers

For production, run queue workers to process monitoring jobs:
```bash
php artisan queue:work
```

## Configuration

### Monitor Types

**HTTP Ping**
- URL to monitor
- HTTP method (GET, POST, etc.)
- Expected status codes (200, 201, etc.)
- Timeout duration
- Optional keyword checking

**Heartbeat**
- Expected heartbeat interval
- Grace period before marking as down
- Webhook URL provided after creation

**SSL Certificate**
- HTTPS URL to monitor
- Alert threshold (default: 30 days before expiry)

### Check Intervals

- Minimum: 30 seconds
- Default: 60 seconds
- Configurable per monitor

## Testing

Run the test suite:
```bash
php artisan test
```

Run specific test suites:
```bash
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

## API Endpoints

### Heartbeat Endpoint
```
POST /heartbeat/{monitor}
```

**Response:**
```json
{
  "success": true,
  "message": "Heartbeat recorded successfully",
  "timestamp": "2025-10-06T10:00:00+00:00"
}
```

### Public Status Page
```
GET /status/{slug}
```

Returns a public HTML page showing monitor statuses and incidents.

## Multi-Tenancy

This application uses domain-based tenancy. Each tenant can have:
- Custom domain or subdomain
- Isolated database
- Independent monitors and configurations

### Creating Tenants

```php
use App\Models\Tenant;

$tenant = Tenant::create([
    'name' => 'Acme Corp',
    'email' => 'admin@acme.com',
]);

$tenant->domains()->create([
    'domain' => 'acme.example.com',
]);
```

## Stripe Integration

### Setting Up Subscriptions

1. Add Stripe keys to `.env`:
```env
STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=your_webhook_secret
```

2. Create products and prices in Stripe Dashboard

3. Configure plans in your application

## Development

### Building Assets for Development
```bash
npm run dev
```

### Running in Watch Mode
```bash
php artisan serve &
npm run dev &
php artisan queue:work &
php artisan schedule:work
```

Or use the built-in dev command:
```bash
composer run dev
```

## Deployment

1. Set `APP_ENV=production` in `.env`
2. Run `composer install --optimize-autoloader --no-dev`
3. Run `npm run build`
4. Run `php artisan optimize`
5. Set up queue workers and cron jobs
6. Configure your web server (Nginx/Apache)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues, questions, or contributions, please open an issue on GitHub.
