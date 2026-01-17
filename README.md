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
- Laravel Prism / Laravel AI SDK support
- MCP (Model Context Protocol) compatible
- AI-powered development agents
- Pre-configured Claude Code agents and skills
- OAuth login support for Claude
- Pre-configured for AI-powered features

⚡ **Developer Experience**
- Interactive installation wizard
- Automatic database setup (MySQL or SQLite)
- Laravel Herd mail configuration support
- Quick curl or composer installation
- Claude Code agents for accelerated development
- Comprehensive skill modules for common tasks

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

### AI Integration

Configure AI services in your `.env` file:

```env
# Claude AI (via Anthropic)
ANTHROPIC_API_KEY=your-api-key-here
ANTHROPIC_MODEL=claude-3-5-sonnet-20241022

# Laravel Prism (recommended)
PRISM_PROVIDER=anthropic
PRISM_CACHE_ENABLED=true

# Alternative: OpenAI
OPENAI_API_KEY=your-openai-key
OPENAI_MODEL=gpt-4
```

**Available AI Tools:**
- **Laravel Prism**: Multi-provider AI integration (recommended)
- **Laravel AI SDK**: Official Laravel AI package
- **MCP (Model Context Protocol)**: Context-aware AI development
- **Laravel Boost**: AI-powered code generation

See the AI Tools Setup Guides for installation instructions:
- [Install Laravel Prism/AI SDK](.github/ISSUE_TEMPLATE/install_laravel_ai_sdk.md)
- [Install MCP (Model Context Protocol)](.github/ISSUE_TEMPLATE/install_mcp.md)
- [Install Laravel Boost](.github/ISSUE_TEMPLATE/install_laravel_boost.md)

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
├── .github/
│   ├── agents/                  # Claude Code agents & skills
│   │   ├── README.md           # Agents documentation
│   │   ├── laravel-developer.md
│   │   ├── database-migration-expert.md
│   │   ├── testing-expert.md
│   │   ├── frontend-tall-stack-expert.md
│   │   ├── api-developer.md
│   │   └── skills/             # Project-specific skills
│   │       ├── otp-authentication-skills.md
│   │       ├── team-management-skills.md
│   │       ├── ai-integration-skills.md
│   │       └── security-best-practices.md
│   └── ISSUE_TEMPLATE/         # Setup guides for AI tools
│       ├── install_laravel_boost.md
│       ├── install_mcp.md
│       └── install_laravel_ai_sdk.md
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

### AI-Powered Development

This project includes comprehensive AI development tools:

#### Claude Code Agents
Pre-built agents for accelerated development:
- **Laravel Developer**: Full-stack Laravel development assistance
- **Database Expert**: Migration and query optimization
- **Testing Expert**: PHPUnit and Pest testing guidance
- **Frontend Expert**: TALL stack UI development
- **API Developer**: RESTful API design and implementation

#### Project Skills
Specialized skills for this platform:
- **OTP Authentication**: Complete OTP implementation patterns
- **Team Management**: Multi-tenant team features
- **AI Integration**: Laravel Prism, MCP, and AI SDK usage
- **Security**: Best practices for secure Laravel apps

#### Getting Started with AI Tools
1. **View Agents**: See [.github/agents/README.md](.github/agents/README.md)
2. **Install AI Tools**: Follow [setup guides](.github/ISSUE_TEMPLATE/)
3. **Use with Claude Desktop**: Configure MCP integration
4. **Reference in Development**: Use agents as coding companions

See the [AI Agents Documentation](.github/agents/README.md) for detailed usage instructions.

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