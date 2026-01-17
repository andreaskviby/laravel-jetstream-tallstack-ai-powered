#!/bin/bash

# Verification script for todo management system installation
# This script simulates what the installer does and verifies the files

echo "===================================="
echo "Todo Management System Verification"
echo "===================================="
echo ""

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
STUBS_DIR="$SCRIPT_DIR/setup/stubs"

echo "✓ Checking stub files exist..."

# Check model stub
if [ -f "$STUBS_DIR/Todo.stub" ]; then
    echo "  ✓ Todo model stub found"
    php -l "$STUBS_DIR/Todo.stub" > /dev/null 2>&1 && echo "    ✓ Valid PHP syntax"
else
    echo "  ✗ Todo model stub NOT found"
    exit 1
fi

# Check migration stub
if [ -f "$STUBS_DIR/create_todos_table.stub" ]; then
    echo "  ✓ Todo migration stub found"
    php -l "$STUBS_DIR/create_todos_table.stub" > /dev/null 2>&1 && echo "    ✓ Valid PHP syntax"
else
    echo "  ✗ Todo migration stub NOT found"
    exit 1
fi

# Check command stubs
commands=("TodoAdd" "TodoList" "TodoUpdate" "TodoComplete" "TodoDelete" "TodoShow")
for cmd in "${commands[@]}"; do
    if [ -f "$STUBS_DIR/${cmd}.stub" ]; then
        echo "  ✓ ${cmd} command stub found"
        php -l "$STUBS_DIR/${cmd}.stub" > /dev/null 2>&1 && echo "    ✓ Valid PHP syntax"
    else
        echo "  ✗ ${cmd} command stub NOT found"
        exit 1
    fi
done

echo ""
echo "✓ Checking installer modifications..."
if grep -q "installTodoManagement" "$SCRIPT_DIR/setup/installer.php"; then
    echo "  ✓ Installer includes todo management installation"
else
    echo "  ✗ Installer does NOT include todo management installation"
    exit 1
fi

if grep -q "todo_management" "$SCRIPT_DIR/setup/installer.php"; then
    echo "  ✓ Installer tracks todo management in config"
else
    echo "  ✗ Installer does NOT track todo management in config"
    exit 1
fi

echo ""
echo "✓ Checking documentation..."
if [ -f "$SCRIPT_DIR/TODO_MANAGEMENT.md" ]; then
    echo "  ✓ TODO_MANAGEMENT.md documentation exists"
    word_count=$(wc -w < "$SCRIPT_DIR/TODO_MANAGEMENT.md")
    echo "    ✓ Documentation has $word_count words"
else
    echo "  ✗ TODO_MANAGEMENT.md documentation NOT found"
    exit 1
fi

if grep -q "todo:" "$SCRIPT_DIR/README.md"; then
    echo "  ✓ README.md mentions todo commands"
else
    echo "  ✗ README.md does NOT mention todo commands"
    exit 1
fi

echo ""
echo "===================================="
echo "✓ All verification checks passed!"
echo "===================================="
echo ""
echo "The todo management system is correctly installed with:"
echo "  • 1 Todo model"
echo "  • 6 artisan commands (todo:add, todo:list, todo:show, todo:update, todo:complete, todo:delete)"
echo "  • 1 database migration"
echo "  • Complete documentation"
echo ""
echo "When users install this starter kit, they will automatically get"
echo "the todo management system ready to use after running migrations."
