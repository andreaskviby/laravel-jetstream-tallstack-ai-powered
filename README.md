# Laravel Jetstream TALL Stack AI-Powered Starter Kit

A comprehensive starter kit for quickly bootstrapping Laravel projects with Jetstream, TALL stack (Tailwind, Alpine.js, Livewire, Laravel), OTP authentication, team management, and AI-powered features.

## Features

✨ **Modern Tech Stack**
- Laravel 11+ (latest stable version)
- Laravel Jetstream with Livewire
- TALL Stack (Tailwind CSS, Alpine.js, Livewire, Laravel)
- Team management with enhanced invitations
- OTP (One-Time Password) authentication instead of traditional passwords

🔐 **Security & Authentication**
- OTP-based authentication system
- Prefilled OTP codes for local development (123456)
- Secure team invitation system
- Built-in security best practices

🤖 **AI Integration**
- Claude AI API integration ready
- OAuth login support for Claude
- Pre-configured for AI-powered features

⚡ **Developer Experience**
- Interactive installation wizard
- Automatic database setup (MySQL or SQLite)
- Laravel Herd mail configuration support
- Quick curl or composer installation
- Built-in terminal aliases for Git, Artisan, Composer, and NPM commands

## Installation

### Method 1: Curl Install (Recommended)

Install directly from the command line:

```bash
curl -s https://raw.githubusercontent.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/main/install.sh | bash -s -- my-project
```

> **Note**: The installation script downloads and executes code. Always review scripts before running them. You can inspect the script at: https://github.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/blob/main/install.sh

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

# Start the development server
php artisan serve
```

Visit `http://localhost:8000` to see your application.

## Terminal Aliases

The starter kit includes convenient terminal aliases for common Git, PHP Artisan, Composer, and NPM commands.

### Loading Aliases

**For the current session only:**
```bash
source aliases.sh
```

**To load automatically (add to your shell configuration):**
```bash
# For Zsh (macOS default)
echo "source $(pwd)/aliases.sh" >> ~/.zshrc
source ~/.zshrc

# For Bash
echo "source $(pwd)/aliases.sh" >> ~/.bashrc
source ~/.bashrc
```

### Viewing Available Aliases

List all available aliases with descriptions:
```bash
php artisan aliases:list
# or use the shorthand (after loading aliases)
palist-aliases
```

### Example Aliases

**Git shortcuts:**
- `gs` → `git status`
- `ga` → `git add`
- `gcm` → `git commit -m`
- `gp` → `git push`

**PHP Artisan shortcuts:**
- `pa` → `php artisan`
- `pam` → `php artisan migrate`
- `pams` → `php artisan migrate:fresh --seed`
- `pas` → `php artisan serve`
- `pat` → `php artisan test`

**Composer shortcuts:**
- `c` → `composer`
- `ci` → `composer install`
- `cu` → `composer update`
- `cr` → `composer require`

See the full list by running `php artisan aliases:list`.

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

**SQLite** (default for quick start):
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

**MySQL**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Project Structure

```
├── setup/
│   ├── installer.php           # Main installation script
│   └── stubs/                  # Template files
│       ├── SendOTPCode.stub    # OTP sending action
│       ├── VerifyOTPCode.stub  # OTP verification action
│       ├── auth.config.stub    # Auth configuration
│       ├── otp-email.stub      # OTP email template
│       └── login.blade.stub    # OTP login view
├── install.sh                  # Curl installer script
├── composer.json              # Composer configuration
└── README.md                  # This file
```

## Features in Detail

### Teams Management

Built on Laravel Jetstream's team features with enhancements:
- Create and manage multiple teams
- Invite team members with enhanced invitation system
- Role-based permissions
- Team switching

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

**SQLite**: Ensure the database file path is absolute and writable.

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

Made with ❤️ for the Laravel community