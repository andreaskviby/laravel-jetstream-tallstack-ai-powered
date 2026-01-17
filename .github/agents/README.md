# Claude Code Agents & Skills

This directory contains specialized AI agents and skills designed to enhance development productivity for this Laravel Jetstream TALL Stack project.

## 📋 Overview

The agents in this directory are designed to be used with Claude Code (via GitHub Copilot, Claude Desktop, or other MCP-compatible tools) to provide context-aware assistance for development tasks.

## 🤖 Available Agents

### Core Development Agents

1. **[Laravel Developer](laravel-developer.md)**
   - Laravel 11+ application development
   - MVC patterns and best practices
   - Eloquent ORM and database operations
   - Authentication and authorization
   - API development

2. **[Database & Migration Expert](database-migration-expert.md)**
   - Database schema design
   - Laravel migrations and seeders
   - Query optimization
   - Eloquent relationships
   - Database indexing strategies

3. **[Testing Expert](testing-expert.md)**
   - PHPUnit and Pest testing
   - Feature and unit tests
   - Livewire component testing
   - API testing
   - Test-driven development (TDD)

4. **[Frontend TALL Stack Expert](frontend-tall-stack-expert.md)**
   - Livewire component development
   - Tailwind CSS styling
   - Alpine.js interactivity
   - Responsive design
   - UI/UX best practices

5. **[API Developer](api-developer.md)**
   - RESTful API design
   - Laravel Sanctum authentication
   - API resources and collections
   - Rate limiting and throttling
   - API documentation

## 🎯 Project-Specific Skills

### Skill Modules

1. **[OTP Authentication Skills](skills/otp-authentication-skills.md)**
   - OTP generation and verification
   - Email/SMS delivery
   - Rate limiting and security
   - Local development prefill
   - Backup authentication methods

2. **[Team Management Skills](skills/team-management-skills.md)**
   - Team creation and deletion
   - Member invitations
   - Role-based permissions
   - Team switching
   - Activity tracking

3. **[AI Integration Skills](skills/ai-integration-skills.md)**
   - Laravel Prism setup and usage
   - Claude AI integration
   - MCP (Model Context Protocol) setup
   - Content moderation
   - AI-powered features

4. **[Security Best Practices](skills/security-best-practices.md)**
   - Input validation and sanitization
   - XSS and SQL injection prevention
   - CSRF protection
   - Secure file uploads
   - Session management
   - API security

## 🚀 How to Use These Agents

### With Claude Desktop (via MCP)

1. **Configure Claude Desktop:**
   ```json
   // ~/Library/Application Support/Claude/claude_desktop_config.json (macOS)
   {
     "mcpServers": {
       "laravel-project": {
         "command": "node",
         "args": ["/path/to/mcp-server/index.js"],
         "env": {
           "AGENTS_PATH": "/path/to/project/.github/agents"
         }
       }
     }
   }
   ```

2. **Start a conversation:**
   ```
   "Using the Laravel Developer agent, help me create a new Livewire component for managing posts"
   ```

### With GitHub Copilot Chat

1. Reference agents in your prompts:
   ```
   @workspace Using the context from .github/agents/laravel-developer.md, 
   create a new migration for a posts table with proper indexes
   ```

### Manual Reference

Simply open and read the relevant agent or skill file before asking questions or starting development tasks. The agents contain:
- Best practices
- Code examples
- Common patterns
- Project-specific knowledge

## 📚 Agent Structure

Each agent file contains:

### 1. Core Responsibilities
What the agent specializes in and what tasks it can help with.

### 2. Project-Specific Knowledge
Information specific to this Laravel Jetstream TALL Stack project:
- OTP authentication system
- Team management features
- TALL stack implementation
- Configuration details

### 3. Best Practices
Proven patterns and approaches for the technology stack.

### 4. Common Tasks & Solutions
Ready-to-use code examples for frequent development tasks.

### 5. Commands Reference
Artisan commands and CLI tools relevant to the agent's domain.

### 6. Resources
Links to official documentation and learning materials.

## 🎓 Skill Structure

Each skill file contains:

### 1. Overview
Description of what the skill covers.

### 2. Core Skills
Basic implementations with code examples.

### 3. Advanced Skills
Complex scenarios and optimizations.

### 4. Testing Skills
How to test the implementations.

### 5. Best Practices
Configuration and usage recommendations.

### 6. Troubleshooting
Common issues and their solutions.

## 💡 Tips for Effective Use

1. **Be Specific:** Reference specific agents or skills when asking questions
2. **Provide Context:** Include relevant code snippets or error messages
3. **Iterate:** Start with basic implementations and refine with follow-up questions
4. **Combine Skills:** Many tasks benefit from multiple agents (e.g., Laravel Developer + Testing Expert)
5. **Check Examples:** Review code examples in the agents before implementing

## 🔄 Updating Agents

Agents and skills should be updated when:
- New features are added to the project
- Coding patterns change
- Dependencies are upgraded
- Security best practices evolve
- Team conventions are established

To update an agent:
1. Edit the relevant markdown file
2. Add new sections or update existing ones
3. Include code examples
4. Test recommendations with actual code
5. Commit changes with descriptive message

## 🤝 Contributing New Agents

To add a new agent:

1. **Create the file:** Use kebab-case naming (e.g., `queue-worker-expert.md`)
2. **Follow the structure:** Use existing agents as templates
3. **Include examples:** Provide working code samples
4. **Project-specific:** Add context about this specific project
5. **Test thoroughly:** Ensure examples work
6. **Update this README:** Add the new agent to the list above

## 📖 Related Documentation

- [Installation Guide for AI Tools](../ISSUE_TEMPLATE/install_mcp.md)
- [Laravel Boost Setup](../ISSUE_TEMPLATE/install_laravel_boost.md)
- [Laravel AI SDK/Prism Setup](../ISSUE_TEMPLATE/install_laravel_ai_sdk.md)
- [Project README](../../README.md)
- [Architecture Documentation](../../ARCHITECTURE.md)

## 🎯 Quick Start Examples

### Example 1: Create a New Feature with OTP
```
Use the Laravel Developer and OTP Authentication Skills agents to help me:
1. Create a password reset feature using OTP
2. Include rate limiting
3. Add proper validation
4. Write tests
```

### Example 2: Optimize Database Queries
```
Use the Database & Migration Expert agent to:
1. Analyze this query for N+1 problems
2. Suggest proper indexes
3. Implement eager loading
```

### Example 3: Build a Livewire Component
```
Use the Frontend TALL Stack Expert agent to:
1. Create a real-time search component
2. Add Alpine.js interactions
3. Style with Tailwind CSS
4. Include loading states
```

## 🔐 Security Note

These agents contain project-specific information and best practices. Never share:
- API keys or credentials
- Production database details
- User data or PII
- Proprietary business logic

The agents themselves contain no secrets and are safe to commit to version control.

---

**Last Updated:** January 2026  
**Version:** 1.0.0  
**Maintained By:** Development Team

For questions or suggestions about agents and skills, please open an issue or contact the maintainers.
