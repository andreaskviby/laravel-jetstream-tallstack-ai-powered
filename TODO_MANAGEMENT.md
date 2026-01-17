# Todo Management System

This Laravel application includes a built-in todo management system that allows you to manage todos directly from the command line using artisan commands and Laravel Tinker.

## Overview

The todo management system is designed to help developers and AI assistants (like Claude) track tasks, progress, and context directly in the database. It's perfect for managing development tasks, tracking work items, and maintaining project context.

## Features

- ✅ Command-line interface for todo management
- ✅ Priority levels (low, medium, high)
- ✅ Status tracking (pending, in_progress, completed)
- ✅ Context storage for additional notes
- ✅ Timestamps for creation and completion
- ✅ Colorized output for better readability
- ✅ Integration with Laravel Tinker for advanced operations

## Database Schema

The `todos` table includes the following fields:

- `id` - Primary key
- `title` - The todo title (required)
- `description` - Detailed description (optional)
- `status` - pending, in_progress, or completed
- `priority` - low, medium, or high
- `context` - Additional notes or context (optional)
- `completed_at` - Timestamp when marked as completed
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

## Available Commands

### 1. Add a Todo

```bash
php artisan todo:add "Title of the todo"
```

With options:

```bash
php artisan todo:add "Create Ticket CRUD for admin" \
  --description="Build complete CRUD functionality for Ticket model, admin-only access" \
  --priority=high \
  --status=pending \
  --context="User requested this feature in issue #123"
```

**Options:**
- `--description` - Detailed description
- `--priority` - Priority level: low, medium (default), high
- `--status` - Status: pending (default), in_progress, completed
- `--context` - Additional context or notes

### 2. List Todos

```bash
# List active todos (pending and in_progress)
php artisan todo:list

# List all todos including completed
php artisan todo:list --all

# Filter by status
php artisan todo:list --status=pending
php artisan todo:list --status=in_progress
php artisan todo:list --status=completed

# Filter by priority
php artisan todo:list --priority=high

# Combine filters
php artisan todo:list --status=pending --priority=high
```

### 3. Show Todo Details

```bash
php artisan todo:show 1
```

Displays full details of a specific todo including all fields and timestamps.

### 4. Update a Todo

```bash
# Update title
php artisan todo:update 1 --title="New title"

# Update status
php artisan todo:update 1 --status=in_progress

# Update priority
php artisan todo:update 1 --priority=high

# Update multiple fields
php artisan todo:update 1 \
  --title="Updated title" \
  --status=in_progress \
  --context="Additional notes added"
```

### 5. Mark as Completed

```bash
php artisan todo:complete 1
```

Marks a todo as completed and sets the `completed_at` timestamp.

### 6. Delete a Todo

```bash
# With confirmation
php artisan todo:delete 1

# Skip confirmation
php artisan todo:delete 1 --force
```

## Using with Laravel Tinker

Laravel Tinker provides a REPL (Read-Eval-Print Loop) for interacting with your Laravel application. You can use it for advanced todo operations:

```bash
php artisan tinker
```

### Tinker Examples

```php
// Create a todo
$todo = App\Models\Todo::create([
    'title' => 'Review pull requests',
    'description' => 'Review and merge pending PRs',
    'priority' => 'high',
    'status' => 'pending'
]);

// Find a todo
$todo = App\Models\Todo::find(1);

// Update a todo
$todo->update(['status' => 'in_progress']);

// Mark as completed
$todo->markAsCompleted();

// Get all high priority todos
$todos = App\Models\Todo::where('priority', 'high')->get();

// Get pending todos
$todos = App\Models\Todo::pending()->get();

// Get completed todos
$todos = App\Models\Todo::completed()->get();

// Count todos by status
App\Models\Todo::where('status', 'pending')->count();

// Get todos created today
$todos = App\Models\Todo::whereDate('created_at', today())->get();

// Complex queries
$todos = App\Models\Todo::where('status', 'pending')
    ->where('priority', 'high')
    ->orderBy('created_at', 'desc')
    ->get();

// Delete completed todos older than 30 days
App\Models\Todo::completed()
    ->where('completed_at', '<', now()->subDays(30))
    ->delete();
```

## AI Assistant Integration

This todo system is designed to work seamlessly with AI assistants like Claude. The AI can:

1. **Track Tasks**: Add todos for work items to be completed
2. **Update Progress**: Mark todos as in_progress or completed
3. **Store Context**: Save relevant information in the context field
4. **Maintain History**: Keep a record of what has been done
5. **Query Status**: Check what's pending, in progress, or completed

### Example AI Workflow

```bash
# AI starts a task
php artisan todo:add "Implement Ticket CRUD" --priority=high --status=in_progress

# AI updates progress
php artisan todo:update 1 --context="Created migration and model"

# AI marks subtask complete
php artisan todo:complete 1

# AI checks remaining work
php artisan todo:list --status=pending
```

## Model Methods

The `Todo` model includes helpful methods:

```php
// Scopes
Todo::pending()      // Get pending todos
Todo::inProgress()   // Get in-progress todos
Todo::completed()    // Get completed todos

// Instance methods
$todo->markAsCompleted()   // Mark as completed with timestamp
$todo->markAsInProgress()  // Mark as in progress
```

## Best Practices

1. **Be Descriptive**: Use clear, actionable titles
2. **Use Context**: Store relevant information for future reference
3. **Set Priorities**: Use priorities to focus on important tasks
4. **Update Status**: Keep status current to track progress accurately
5. **Clean Up**: Periodically remove old completed todos
6. **Use Filters**: Use filters to focus on relevant todos

## Examples

### Example 1: Feature Development

```bash
# Add the main task
php artisan todo:add "Build Ticket CRUD for admin" \
  --priority=high \
  --description="Complete CRUD interface for Ticket model, admin-only access"

# Start working
php artisan todo:update 1 --status=in_progress

# Add context as you work
php artisan todo:update 1 --context="Created migration with fields: title, description, status, priority, assigned_to"

# Complete when done
php artisan todo:complete 1
```

### Example 2: Bug Tracking

```bash
# Add bug as todo
php artisan todo:add "Fix validation error on login form" \
  --priority=high \
  --description="Users report validation errors even with correct credentials"

# Track investigation
php artisan todo:update 1 \
  --status=in_progress \
  --context="Found issue in OTP validation logic, needs fix in VerifyOTPCode action"

# Mark fixed
php artisan todo:complete 1
```

### Example 3: Daily Task Management

```bash
# Morning: Check what's pending
php artisan todo:list --status=pending

# Start highest priority task
php artisan todo:update 3 --status=in_progress

# End of day: Review progress
php artisan todo:list --all

# Check completed today
php artisan tinker
>>> App\Models\Todo::whereDate('completed_at', today())->get()
```

## Integration with Development Workflow

The todo system integrates seamlessly with your development workflow:

1. **Issue Tracking**: Convert GitHub issues to todos
2. **Sprint Planning**: Add sprint tasks as todos
3. **Code Reviews**: Track review items
4. **Documentation**: Track documentation needs
5. **Testing**: Track test coverage items

## Tips for AI Assistants

When working with AI assistants like Claude:

1. **Start with todos**: Have the AI create todos for complex tasks
2. **Track progress**: AI should update todos as it works
3. **Store decisions**: Use context field to store important decisions
4. **Review history**: Check completed todos to understand what was done
5. **Avoid duplication**: Check existing todos before creating new ones

## Troubleshooting

### Commands not found

Make sure you've run migrations:

```bash
php artisan migrate
```

### Permission errors

Ensure the `todos` table exists in your database:

```bash
php artisan tinker
>>> Schema::hasTable('todos')
```

### Clear cache if commands aren't recognized

```bash
php artisan config:clear
php artisan cache:clear
```

## Advanced Usage

### Bulk Operations with Tinker

```php
// Create multiple todos from an array
$tasks = [
    ['title' => 'Task 1', 'priority' => 'high'],
    ['title' => 'Task 2', 'priority' => 'medium'],
    ['title' => 'Task 3', 'priority' => 'low'],
];

foreach ($tasks as $task) {
    App\Models\Todo::create($task);
}

// Export todos to JSON
$todos = App\Models\Todo::all()->toJson();
file_put_contents('todos-backup.json', $todos);

// Import todos from JSON
$data = json_decode(file_get_contents('todos-backup.json'), true);
foreach ($data as $item) {
    App\Models\Todo::create($item);
}
```

## Conclusion

The todo management system provides a powerful, flexible way to track tasks and progress directly within your Laravel application. It's designed to work perfectly with both human developers and AI assistants, making it an ideal tool for modern development workflows.
