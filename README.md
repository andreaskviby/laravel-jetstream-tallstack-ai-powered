# Laravel Jetstream TALL Stack AI-Powered Starter Kit

A comprehensive starter kit for quickly bootstrapping Laravel projects with Jetstream, TALL stack (Tailwind, Alpine.js, Livewire, Laravel), OTP authentication, team management with branding, and AI-powered features.

## Features

### Modern Tech Stack
- Laravel 11+ (latest stable version)
- Laravel Jetstream with Livewire
- TALL Stack (Tailwind CSS, Alpine.js, Livewire, Laravel)
- Team management with enhanced invitations
- OTP (One-Time Password) authentication instead of traditional passwords

### Security & Authentication
- OTP-based authentication system
- Prefilled OTP codes for local development (123456)
- Secure team invitation system
- Built-in security best practices

### AI Integration
- Claude AI API integration ready
- OAuth login support for Claude
- Pre-configured for AI-powered features

### Team Branding
Custom branding features for each team:

- **Custom Team Logo**: Upload and manage custom logos for each team
  - Support for image uploads (JPG, PNG, GIF, SVG)
  - Maximum file size: 1MB
  - Automatic storage management with easy logo deletion

- **Brand Colors**: Set primary and secondary brand colors for your team
  - Primary Color: Main brand color for your team
  - Secondary Color: Complementary brand color
  - Hex color code validation (e.g., #FF5733)
  - Color picker UI for easy selection

### Developer Experience
- Interactive installation wizard
- Automatic database setup (MySQL or SQLite)
- Laravel Herd mail configuration support
- Quick curl or composer installation

## Installation

### Method 1: Curl Install (Recommended)

Install directly from the command line:

```bash
curl -s https://raw.githubusercontent.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/main/install.sh | bash -s -- my-project
```

> **Note**: The installation script downloads and executes code. Always review scripts before running them.

Or download and run:

```bash
curl -O https://raw.githubusercontent.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/main/install.sh
chmod +x install.sh
./install.sh my-project
```

### Method 2: Composer Install

```bash
composer create-project andreaskviby/laravel-jetstream-tallstack-ai-powered my-project
cd my-project
```

## Interactive Setup

The installer will guide you through:

1. **Database Selection**: Choose between MySQL or SQLite
2. **Database Configuration**:
   - For MySQL: Create new or connect to existing database
   - Enter credentials (host, port, username, password)
   - For SQLite: Automatic file creation
3. **Claude AI Setup**: Optionally configure Claude AI API integration
4. **Mail Configuration**: Configure Laravel Herd or other mail settings
5. **OTP Authentication**: Automatic setup with local environment detection

## Post-Installation

After installation, follow these steps:

```bash
cd my-project

# Run database migrations
php artisan migrate

# Link storage
php artisan storage:link

# Build assets
npm install
npm run build

# Start the development server
php artisan serve
```

Visit `http://localhost:8000` to see your application.

## Configuration

### OTP Authentication

The starter kit uses OTP authentication instead of passwords. Configuration is in `.env`:

```env
OTP_ENABLED=true
OTP_LENGTH=6
OTP_EXPIRES_IN=10
OTP_PREFILL_LOCAL=true
OTP_DEFAULT_CODE=123456
```

**Local Development**: When `OTP_PREFILL_LOCAL=true` and `APP_ENV=local`, the system uses the prefilled code `123456` for easy testing.

**Production**: OTP codes are sent via email and expire after 10 minutes (configurable).

### Claude AI Integration

Configure Claude AI in your `.env` file:

```env
CLAUDE_API_KEY=your-api-key-here
CLAUDE_MODEL=claude-3-5-sonnet-20241022
```

### Database Configuration

**MySQL** (recommended for production):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Usage

### Team Settings

Navigate to your team settings page to access the branding options. You can:

1. **Upload a Team Logo**:
   - Click "Select A New Logo"
   - Choose an image file (max 1MB)
   - Preview the logo before saving
   - Click "Save" to apply changes

2. **Set Brand Colors**:
   - Use the color picker or enter hex codes directly
   - Primary color: Main brand identity color
   - Secondary color: Complementary color
   - Click "Save" to apply changes

3. **Remove Logo**:
   - Click "Remove Logo" to delete the current team logo
   - Changes are saved immediately

### Teams Management

Built on Laravel Jetstream's team features with enhancements:
- Create and manage multiple teams
- Invite team members with enhanced invitation system
- Role-based permissions
- Team switching
- Custom branding per team

### OTP Authentication

Replaces traditional password authentication:
- Email-based OTP codes
- 6-digit codes (configurable)
- 10-minute expiration (configurable)
- Prefilled codes for local development
- Secure code storage and verification

### TALL Stack

Full integration of the TALL stack:
- **Tailwind CSS**: Utility-first CSS framework
- **Alpine.js**: Minimal JavaScript framework
- **Livewire**: Full-stack framework for Laravel
- **Laravel**: The PHP framework for web artisans

## Technical Implementation

### Database Schema

The team branding feature adds three columns to the `teams` table:
- `logo_path` (string, nullable): Stores the path to the uploaded team logo
- `primary_color` (string, nullable): Stores the team's primary brand color (hex code)
- `secondary_color` (string, nullable): Stores the team's secondary brand color (hex code)

### Components

- **UpdateTeamBrandingForm**: Livewire component for managing team branding
  - Logo upload with file validation
  - Color selection with hex code validation
  - Real-time preview of logo changes
  - Logo deletion functionality

### Models

- **Team Model**: Extended with branding attributes
  - `logo_path`, `primary_color`, `secondary_color` are mass assignable
  - `logo_url` accessor provides the full URL to the team logo

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and npm (for frontend assets)
- MySQL 5.7+ or SQLite 3.8+
- Git

## Development

### Building Assets

```bash
npm install
npm run dev
```

### Running Tests

```bash
php artisan test
```

Or run specific feature tests:
```bash
php artisan test --filter UpdateTeamBrandingTest
```

### Code Style

```bash
./vendor/bin/pint
```

## Customization

### Modifying OTP Behavior

Edit the OTP action classes in `app/Actions/Fortify/`:
- `SendOTPCode.php` - Customize OTP generation and sending
- `VerifyOTPCode.php` - Customize OTP verification logic

### Customizing Team Invitations

Team invitation views and logic can be found in:
- `resources/views/teams/` - Team management views
- `app/Actions/Jetstream/` - Team action classes

### Styling

All views use Tailwind CSS. Customize the theme in:
- `tailwind.config.js` - Tailwind configuration
- `resources/css/app.css` - Custom styles

## Troubleshooting

### Database Connection Issues

**MySQL**: Ensure your database server is running and credentials are correct.
```bash
mysql -u your_username -p
```

### OTP Not Working

- Check mail configuration in `.env`
- For local development, ensure `OTP_PREFILL_LOCAL=true`
- Verify cache driver is configured correctly

### Frontend Assets Not Building

```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues, questions, or contributions, please visit:
- GitHub: [andreaskviby/laravel-jetstream-tallstack-ai-powered](https://github.com/andreaskviby/laravel-jetstream-tallstack-ai-powered)

## Credits

Built with:
- [Laravel](https://laravel.com)
- [Laravel Jetstream](https://jetstream.laravel.com)
- [Livewire](https://livewire.laravel.com)
- [Tailwind CSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)

---

Made with love for the Laravel community
