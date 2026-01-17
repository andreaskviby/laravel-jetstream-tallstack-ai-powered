# Laravel Jetstream TALL Stack AI-Powered

## Quick Start Guide

This guide will help you get up and running quickly with the starter kit.

### 1. Installation

Choose your preferred installation method:

#### Option A: Quick Install (curl)
```bash
curl -s https://raw.githubusercontent.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/main/install.sh | bash -s -- my-awesome-app
```

#### Option B: Composer Install
```bash
composer create-project andreaskviby/laravel-jetstream-tallstack-ai-powered my-awesome-app
cd my-awesome-app
```

### 2. Interactive Setup

The installer will ask you a series of questions:

#### Database Selection
```
Which database would you like to use?
  1) MySQL
  2) SQLite
Enter your choice (1-2):
```

- **Choose 1 for MySQL** if you have a MySQL server running
- **Choose 2 for SQLite** for the quickest setup (no database server required)

#### MySQL Configuration (if chosen)
```
Do you want to create a new database or connect to existing? (create/connect):
Database host (default: 127.0.0.1):
Database port (default: 3306):
Database username (default: root):
Database password:
Database name:
```

#### Claude AI Integration
```
Would you like to configure Claude AI integration? (yes/no):
Enter your Claude AI API key:
```

Get your API key from: https://console.anthropic.com/

#### Laravel Herd (Local Development)
```
Are you using Laravel Herd? (yes/no):
```

Choose "yes" if you're using Laravel Herd for local development.

### 3. Complete Setup

After the installer finishes:

```bash
cd my-awesome-app

# Run database migrations
php artisan migrate

# Start the development server
php artisan serve
```

### 4. Access Your Application

Open your browser and go to:
```
http://localhost:8000
```

### 5. Register Your First User

1. Click "Register" 
2. Fill in your details
3. For local development, use OTP code: **123456**

### 6. Explore Features

Once logged in, you can:
- ✅ Create and manage teams
- ✅ Invite team members
- ✅ Manage your profile
- ✅ Use AI-powered features (if configured)
- ✅ Switch between teams

## OTP Authentication

### Local Development
In local environment, the system uses a prefilled OTP code for easy testing:
- **Default Code**: `123456`
- No email is sent
- Code is automatically accepted

### Production
In production:
- OTP codes are generated randomly
- Sent via email to the user
- Expire after 10 minutes
- Must be used once

### Testing OTP

**Local Environment:**
```
Email: test@example.com
OTP Code: 123456
```

**Production:**
Check your email for the 6-digit code.

## Database Configuration

### SQLite (Recommended for Quick Start)
Automatically configured during setup. Database file is created at:
```
database/database.sqlite
```

### MySQL
Requires a running MySQL server. During setup, you can:
1. Create a new database automatically
2. Connect to an existing database

**Manual Database Creation:**
```sql
CREATE DATABASE my_database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Frontend Assets

### Development
```bash
npm install
npm run dev
```

This starts the Vite development server with hot module replacement.

### Production Build
```bash
npm run build
```

## Common Tasks

### Create a New Team
1. Click your profile picture (top right)
2. Select "Team Settings"
3. Click "Create New Team"

### Invite Team Members
1. Go to "Team Settings"
2. Click "Team Members"
3. Enter email and select role
4. Click "Invite"

### Switch Teams
1. Click your profile picture (top right)
2. Select the team you want to switch to

## Directory Structure

```
my-awesome-app/
├── app/
│   ├── Actions/         # Jetstream and custom actions
│   ├── Models/          # Eloquent models
│   └── ...
├── database/
│   ├── migrations/      # Database migrations
│   └── seeders/         # Database seeders
├── resources/
│   ├── css/            # Tailwind CSS
│   ├── js/             # JavaScript and Alpine.js
│   └── views/          # Blade templates and Livewire components
└── routes/
    ├── web.php         # Web routes
    └── api.php         # API routes
```

## Next Steps

### Customize Your Application

1. **Update Branding**
   - Edit `config/app.php` to change app name
   - Update logo in `resources/views/components/`

2. **Add Custom Features**
   - Create new Livewire components: `php artisan make:livewire ComponentName`
   - Add new models: `php artisan make:model ModelName -m`

3. **Configure AI Features**
   - Add Claude AI API key in `.env`
   - Implement AI features in your components

4. **Deploy to Production**
   - Choose a hosting provider (Laravel Forge, Vapor, etc.)
   - Configure environment variables
   - Set up database
   - Run migrations

## Troubleshooting

### Port 8000 Already in Use
```bash
php artisan serve --port=8080
```

### Permission Errors
```bash
chmod -R 775 storage bootstrap/cache
```

### Database Connection Failed
Check your `.env` file and ensure:
- Database credentials are correct
- Database server is running (for MySQL)
- Database file exists (for SQLite)

### Frontend Not Loading
```bash
npm install
npm run build
php artisan optimize:clear
```

## Getting Help

- 📚 [Laravel Documentation](https://laravel.com/docs)
- 📚 [Jetstream Documentation](https://jetstream.laravel.com)
- 📚 [Livewire Documentation](https://livewire.laravel.com)
- 🐛 [Report Issues](https://github.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/issues)

## Tips for Success

1. **Start Simple**: Get the basic app running before customizing
2. **Use SQLite**: For quick prototyping and testing
3. **Leverage Teams**: Build multi-tenant features easily
4. **OTP in Local**: Use the prefilled code (123456) for faster development
5. **Keep Dependencies Updated**: Run `composer update` regularly

---

Happy coding! 🚀
