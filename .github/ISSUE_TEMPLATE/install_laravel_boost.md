---
name: Install Laravel Boost
about: Guide for installing and configuring Laravel Boost for AI-powered development
title: '[SETUP] Install Laravel Boost'
labels: ['enhancement', 'ai-tools', 'setup']
assignees: ''
---

## Laravel Boost Installation

Laravel Boost provides AI-powered code generation and development tools for Laravel applications.

### Prerequisites
- Laravel 11+ installed
- Composer available
- PHP 8.2 or higher

### Installation Steps

1. **Install Laravel Boost via Composer:**
   ```bash
   composer require --dev beyondcode/laravel-boost
   ```

2. **Publish Configuration:**
   ```bash
   php artisan vendor:publish --tag=boost-config
   ```

3. **Configure AI Provider:**
   Add to your `.env` file:
   ```env
   BOOST_PROVIDER=claude
   BOOST_API_KEY=your-api-key-here
   BOOST_MODEL=claude-3-5-sonnet-20241022
   ```

4. **Test Installation:**
   ```bash
   php artisan boost:test
   ```

### Features Available After Installation
- ✨ AI-powered code generation
- 🔄 Automatic migration generation
- 📝 Smart documentation generation
- 🧪 Test case generation
- 🎨 Component scaffolding

### Usage Examples

**Generate a Model with Migrations:**
```bash
php artisan boost:make:model Post --with-migration
```

**Generate a Livewire Component:**
```bash
php artisan boost:make:livewire UserProfile
```

**Generate Tests:**
```bash
php artisan boost:generate:tests app/Models/Post.php
```

### Configuration Options

Edit `config/boost.php` to customize:
- AI model preferences
- Code generation templates
- File output locations
- Integration with existing code

### Documentation
- [Laravel Boost Documentation](https://github.com/beyondcode/laravel-boost)
- [API Reference](https://laravel-boost.com/docs)

### Verification Checklist
- [ ] Laravel Boost installed via Composer
- [ ] Configuration published
- [ ] API key configured in `.env`
- [ ] Test command runs successfully
- [ ] Generated at least one component to verify

### Related Issues
- #XX - Install MCP
- #XX - Install Laravel AI SDK

---

**Note:** This is a setup guide. Mark as complete once Laravel Boost is fully configured and tested.
