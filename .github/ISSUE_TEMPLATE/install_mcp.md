---
name: Install MCP (Model Context Protocol)
about: Guide for installing and configuring MCP for AI integration
title: '[SETUP] Install Model Context Protocol (MCP)'
labels: ['enhancement', 'ai-tools', 'setup']
assignees: ''
---

## Model Context Protocol (MCP) Installation

MCP enables seamless communication between AI models and your Laravel application, providing context-aware AI interactions.

### Prerequisites
- Node.js 18+ and npm/pnpm
- Laravel application running
- Claude API access (or compatible AI provider)

### Installation Steps

1. **Install MCP Server:**
   ```bash
   npm install -g @modelcontextprotocol/server-laravel
   ```
   
   Or with pnpm:
   ```bash
   pnpm add -g @modelcontextprotocol/server-laravel
   ```

2. **Initialize MCP Configuration:**
   ```bash
   npx @modelcontextprotocol/create-server laravel-mcp
   cd laravel-mcp
   npm install
   ```

3. **Configure MCP Server:**
   Create `mcp-config.json` in your project root:
   ```json
   {
     "mcpServers": {
       "laravel": {
         "command": "node",
         "args": ["./laravel-mcp/index.js"],
         "env": {
           "LARAVEL_PATH": "/path/to/your/laravel/project",
           "DB_CONNECTION": "mysql",
           "AI_PROVIDER": "claude"
         }
       }
     }
   }
   ```

4. **Add MCP Tools to Laravel:**
   Create `app/Services/MCPService.php`:
   ```php
   <?php

   namespace App\Services;

   class MCPService
   {
       public function getContext(): array
       {
           return [
               'models' => $this->getModels(),
               'routes' => $this->getRoutes(),
               'config' => $this->getConfig(),
           ];
       }

       protected function getModels(): array
       {
           // Return available models
       }

       protected function getRoutes(): array
       {
           // Return application routes
       }

       protected function getConfig(): array
       {
           // Return relevant configuration
       }
   }
   ```

5. **Configure Claude Desktop (if using):**
   Add to `~/Library/Application Support/Claude/claude_desktop_config.json` (macOS):
   ```json
   {
     "mcpServers": {
       "laravel-project": {
         "command": "node",
         "args": ["/absolute/path/to/laravel-mcp/index.js"]
       }
     }
   }
   ```

6. **Test MCP Connection:**
   ```bash
   npm run test
   ```

### Features Available After Installation
- 🔍 Context-aware code understanding
- 📊 Database schema access
- 🛣️ Route inspection
- 📝 Configuration reading
- 🔄 Real-time application state
- 💬 AI-powered debugging

### MCP Tools Available

**Database Tools:**
- `list-tables` - List all database tables
- `describe-table` - Get table schema
- `query-database` - Execute read-only queries

**Laravel Tools:**
- `list-routes` - Get all application routes
- `show-config` - Display configuration values
- `list-models` - Show Eloquent models
- `show-migration` - Display migration files

**Development Tools:**
- `run-artisan` - Execute artisan commands
- `show-logs` - Read application logs
- `list-jobs` - Show queued jobs

### Security Considerations
- ⚠️ MCP has access to your application - use read-only mode in production
- 🔒 Configure allowed commands whitelist
- 🛡️ Set proper file permissions
- 🔐 Never expose sensitive credentials

### Usage Examples

In Claude Desktop with MCP enabled:
```
"Show me the schema for the users table"
"List all routes related to teams"
"What's the current database configuration?"
```

### Documentation
- [MCP Documentation](https://modelcontextprotocol.io)
- [MCP Laravel Server](https://github.com/modelcontextprotocol/server-laravel)
- [Claude Desktop Integration](https://claude.ai/docs/mcp)

### Verification Checklist
- [ ] MCP server installed globally
- [ ] Configuration file created
- [ ] Claude Desktop config updated (if applicable)
- [ ] MCP service created in Laravel
- [ ] Test connection successful
- [ ] At least one MCP tool tested

### Related Issues
- #XX - Install Laravel Boost
- #XX - Install Laravel AI SDK
- #XX - Create Claude Code Agents

---

**Note:** MCP provides the foundation for AI-powered development workflows. Complete this before creating agents.
