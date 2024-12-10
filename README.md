
# Laravel API v1

This is a Laravel-based API project with modular architecture, media management, and token-based authentication.

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL or other supported database

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/ilgarhuseynli/laravel_api_v1.git
   cd laravel_api_v1
   ```

2. **Install dependencies**
   ```bash
   composer install 
   ```

3. **Environment setup**  
   Copy `.env.example` to `.env` and configure the environment variables:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database migration and seeding**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Storage linking**
   ```bash
   php artisan storage:link
   ```

6. **Run the application**  
   Start the development server:
   ```bash
   php artisan serve
   ```

## Installed Packages

- **[Laravel Framework](https://laravel.com/docs/10.x)**: Core PHP framework.
- **[Laravel Sanctum](https://laravel.com/docs/10.x/sanctum)**: API token authentication.
- **[Laravel UI](https://github.com/laravel/ui)**: Provides UI scaffolding with authentication.
- **[Nwidart Laravel Modules](https://nwidart.com/laravel-modules/v10/introduction)**: Modular architecture for scalable projects.
- **[Spatie Laravel Medialibrary](https://spatie.be/docs/laravel-medialibrary/v10/introduction)**: Media management for file uploads.

## Features

1. **Modular Architecture**  
   Organized using `nwidart/laravel-modules` for maintainable code structure.

2. **API Authentication**  
   Secure API token authentication implemented with `Laravel Sanctum`.

3. **Media Management**  
   Manage file uploads and associations using `Spatie Laravel Medialibrary`.

4. **Frontend Scaffolding**  
   Prebuilt authentication UI provided by `Laravel UI`.

## Usage

1. **Creating a Module**
   ```bash
   php artisan module:make <ModuleName>
   ```

2. **Managing Media**
   ```php
   use Spatie\MediaLibrary\HasMedia;
   use Spatie\MediaLibrary\InteractsWithMedia;

   class YourModel extends Model implements HasMedia
   {
       use InteractsWithMedia;
   }
   ```

3. **API Tokens**
   ```php
   $token = $user->createToken('token-name')->plainTextToken;
   ```

## Testing

Run PHPUnit tests:
```bash
php artisan test
```

Run specific tests:
```bash
php artisan test --filter=<TestClassName>
```

## Deployment

1. **Set up environment variables**  
   Ensure `.env` is configured for production.

2. **Optimize the application**
   ```bash
   php artisan optimize
   ```

3. **Run database migrations**
   ```bash
   php artisan migrate --force
   ```

## License

This project is open-source and available under the [MIT license](https://opensource.org/licenses/MIT).
