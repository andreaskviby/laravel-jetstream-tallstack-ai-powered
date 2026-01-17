#!/bin/bash

# AI Landing Page Generator Feature Installation Script
# This script installs the AI Landing Page Generator feature for Laravel Jetstream TALL Stack

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}AI Landing Page Generator Feature Installer${NC}"
echo -e "${BLUE}================================================${NC}"
echo ""

# Check if we're in a Laravel project
if [ ! -f "$PROJECT_ROOT/artisan" ]; then
    echo -e "${RED}Error: This doesn't appear to be a Laravel project.${NC}"
    echo "Please run this script from the setup directory of your Laravel project."
    exit 1
fi

echo -e "${YELLOW}Installing AI Landing Page Generator feature...${NC}"
echo ""

# Create necessary directories
echo -e "${GREEN}Creating directories...${NC}"
mkdir -p "$PROJECT_ROOT/app/Models"
mkdir -p "$PROJECT_ROOT/app/Services"
mkdir -p "$PROJECT_ROOT/app/Http/Livewire"  # Legacy path for Laravel 10 and Jetstream compatibility
mkdir -p "$PROJECT_ROOT/resources/views/livewire"
mkdir -p "$PROJECT_ROOT/resources/views/todos"
mkdir -p "$PROJECT_ROOT/database/migrations"

# Copy Model
echo -e "${GREEN}Installing Todo model...${NC}"
cp "$SCRIPT_DIR/stubs/Todo.stub" "$PROJECT_ROOT/app/Models/Todo.php"

# Copy Service
echo -e "${GREEN}Installing ResearcherAgentService...${NC}"
cp "$SCRIPT_DIR/stubs/ResearcherAgentService.stub" "$PROJECT_ROOT/app/Services/ResearcherAgentService.php"

# Copy Livewire Component
echo -e "${GREEN}Installing TodoManager Livewire component...${NC}"
cp "$SCRIPT_DIR/stubs/TodoManager.stub" "$PROJECT_ROOT/app/Http/Livewire/TodoManager.php"

# Copy Views
echo -e "${GREEN}Installing views...${NC}"
cp "$SCRIPT_DIR/stubs/todo-manager.blade.stub" "$PROJECT_ROOT/resources/views/livewire/todo-manager.blade.php"
cp "$SCRIPT_DIR/stubs/todos-index.blade.stub" "$PROJECT_ROOT/resources/views/todos/index.blade.php"

# Copy Migration
echo -e "${GREEN}Installing migration...${NC}"
TIMESTAMP=$(date +%Y_%m_%d_%H%M%S)
cp "$SCRIPT_DIR/stubs/create_todos_table.stub" "$PROJECT_ROOT/database/migrations/${TIMESTAMP}_create_todos_table.php"

# Update or create services config
if [ ! -f "$PROJECT_ROOT/config/services.php" ]; then
    echo -e "${GREEN}Creating services config...${NC}"
    cp "$SCRIPT_DIR/stubs/services.config.stub" "$PROJECT_ROOT/config/services.php"
else
    echo -e "${YELLOW}Note: config/services.php already exists.${NC}"
    echo -e "${YELLOW}Please manually add the Claude configuration from setup/stubs/services.config.stub${NC}"
fi

echo ""
echo -e "${GREEN}✓ Files installed successfully!${NC}"
echo ""
echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}Manual Steps Required${NC}"
echo -e "${BLUE}================================================${NC}"
echo ""

echo -e "${YELLOW}1. Run database migration:${NC}"
echo "   php artisan migrate"
echo ""

echo -e "${YELLOW}2. Add Claude AI API key to .env:${NC}"
echo "   CLAUDE_API_KEY=your-api-key-here"
echo "   CLAUDE_MODEL=claude-3-5-sonnet-20241022"
echo ""

echo -e "${YELLOW}3. Add route to routes/web.php:${NC}"
echo "   Route::get('/todos', TodoManager::class)->name('todos.index');"
echo "   (See setup/stubs/web-routes-snippet.stub for full example)"
echo ""

echo -e "${YELLOW}4. Update navigation menu in resources/views/navigation-menu.blade.php:${NC}"
echo "   Add the navigation links from setup/stubs/navigation-menu-snippet.stub"
echo "   and setup/stubs/responsive-navigation-menu-snippet.stub"
echo ""

echo -e "${YELLOW}5. Clear application cache:${NC}"
echo "   php artisan config:clear"
echo "   php artisan cache:clear"
echo "   php artisan view:clear"
echo "   composer dump-autoload"
echo ""

echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}Documentation${NC}"
echo -e "${BLUE}================================================${NC}"
echo ""
echo "Full feature documentation: setup/stubs/TODO_FEATURE_README.stub"
echo "Installation guide: setup/INSTALL_TODO_FEATURE.md"
echo ""
echo -e "${GREEN}Installation complete! Follow the manual steps above to finish setup.${NC}"
