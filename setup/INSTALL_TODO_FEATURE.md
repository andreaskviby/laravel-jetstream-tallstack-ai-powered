# AI Landing Page Generator - Installation Guide

This guide explains how to set up the AI Landing Page Generator feature in your Laravel Jetstream TALL Stack application.

## Prerequisites

- Laravel Jetstream TALL Stack application already installed
- Claude AI API key (get one from https://console.anthropic.com/)
- PHP 8.2+
- Composer

## Installation Steps

### 1. Copy the Model

Copy the Todo model to your application:

```bash
cp setup/stubs/Todo.stub app/Models/Todo.php
```

### 2. Run the Migration

Copy and run the migration:

```bash
cp setup/stubs/create_todos_table.stub database/migrations/$(date +%Y_%m_%d_%H%M%S)_create_todos_table.php
php artisan migrate
```

### 3. Install the Service

Create the services directory and copy the researcher agent service:

```bash
mkdir -p app/Services
cp setup/stubs/ResearcherAgentService.stub app/Services/ResearcherAgentService.php
```

### 4. Install the Livewire Component

Copy the Livewire component:

```bash
mkdir -p app/Http/Livewire
cp setup/stubs/TodoManager.stub app/Http/Livewire/TodoManager.php
```

### 5. Create the Views

Create the views directory and copy the blade files:

```bash
mkdir -p resources/views/livewire
cp setup/stubs/todo-manager.blade.stub resources/views/livewire/todo-manager.blade.php

mkdir -p resources/views/todos
cp setup/stubs/todos-index.blade.stub resources/views/todos/index.blade.php
```

### 6. Update Configuration

Update the services configuration to include Claude AI settings:

```bash
cp setup/stubs/services.config.stub config/services.php
```

Or manually add to `config/services.php`:

```php
'claude' => [
    'api_key' => env('CLAUDE_API_KEY'),
    'model' => env('CLAUDE_MODEL', 'claude-3-5-sonnet-20241022'),
],
```

### 7. Add Routes

Add the routes to `routes/web.php`:

```php
use App\Http\Livewire\TodoManager;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // ... existing routes ...
    
    // AI Landing Page Generator Routes
    Route::get('/todos', TodoManager::class)->name('todos.index');
});
```

### 8. Update Navigation

Edit `resources/views/navigation-menu.blade.php` to add the menu item.

Find the section with navigation links (around line 15-20) and add:

```blade
<x-nav-link href="{{ route('todos.index') }}" :active="request()->routeIs('todos.*')">
    {{ __('AI Landing Pages') }}
</x-nav-link>
```

Also add to the responsive navigation section (around line 50-60):

```blade
<x-responsive-nav-link href="{{ route('todos.index') }}" :active="request()->routeIs('todos.*')">
    {{ __('AI Landing Pages') }}
</x-responsive-nav-link>
```

### 9. Configure Environment

Add your Claude AI API key to `.env`:

```env
CLAUDE_API_KEY=sk-ant-xxxxxxxxxxxxxxxxxxxxx
CLAUDE_MODEL=claude-3-5-sonnet-20241022
```

### 10. Clear Cache

Clear the application cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Verification

1. Start your development server:
   ```bash
   php artisan serve
   ```

2. Log in to your application

3. Click "AI Landing Pages" in the navigation menu

4. You should see the todo management interface

5. Try creating a new task with a SaaS idea description

## Troubleshooting

### "Class 'App\Http\Livewire\TodoManager' not found"

Run:
```bash
composer dump-autoload
```

### "Table 'todos' doesn't exist"

Run the migration:
```bash
php artisan migrate
```

### "Claude API key not configured"

Ensure you've added `CLAUDE_API_KEY` to your `.env` file and run:
```bash
php artisan config:clear
```

### Menu item doesn't appear

- Check that you've added the navigation links to both desktop and responsive sections
- Clear the view cache: `php artisan view:clear`
- Ensure you're logged in

## Automatic Installation Script

For convenience, you can create a bash script to automate the installation:

```bash
#!/bin/bash

# Install AI Landing Page Generator Feature

echo "Installing AI Landing Page Generator..."

# Copy files
cp setup/stubs/Todo.stub app/Models/Todo.php
cp setup/stubs/ResearcherAgentService.stub app/Services/ResearcherAgentService.php
cp setup/stubs/TodoManager.stub app/Http/Livewire/TodoManager.php
cp setup/stubs/todo-manager.blade.stub resources/views/livewire/todo-manager.blade.php
cp setup/stubs/todos-index.blade.stub resources/views/todos/index.blade.php

# Create migration
TIMESTAMP=$(date +%Y_%m_%d_%H%M%S)
cp setup/stubs/create_todos_table.stub "database/migrations/${TIMESTAMP}_create_todos_table.php"

# Update services config if it doesn't exist
if [ ! -f config/services.php ]; then
    cp setup/stubs/services.config.stub config/services.php
fi

echo ""
echo "Files installed successfully!"
echo ""
echo "Next steps:"
echo "1. Run: php artisan migrate"
echo "2. Add CLAUDE_API_KEY to your .env file"
echo "3. Update routes/web.php with the routes from setup/stubs/web-routes-snippet.stub"
echo "4. Update resources/views/navigation-menu.blade.php with navigation links"
echo "5. Run: php artisan config:clear"
echo ""
echo "See setup/stubs/TODO_FEATURE_README.stub for complete documentation."
```

Save this as `install-ai-landing-page.sh` and run:

```bash
chmod +x install-ai-landing-page.sh
./install-ai-landing-page.sh
```

## Next Steps

- Read the full feature documentation: `setup/stubs/TODO_FEATURE_README.stub`
- Try creating your first landing page
- Customize the AI prompt in `ResearcherAgentService.php`
- Add queue support for background processing
- Implement email notifications

## Support

For issues or questions:
- Check the detailed documentation in `setup/stubs/TODO_FEATURE_README.stub`
- Open an issue on GitHub
- Review the example in QUICKSTART.md

## License

This feature is part of the Laravel Jetstream TALL Stack AI-Powered starter kit and is licensed under the MIT License.
