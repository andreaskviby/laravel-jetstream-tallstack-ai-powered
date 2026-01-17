# AI Integration Skills

## Overview
Skills for integrating AI capabilities using Claude AI, Laravel Prism, Laravel AI SDK, and MCP (Model Context Protocol) in this Laravel Jetstream TALL Stack project.

## Core Skills

### 1. Setup Laravel Prism

**Purpose:** Configure Laravel Prism for multi-provider AI integration.

**Installation:**
```bash
composer require echolabsdev/prism
php artisan vendor:publish --provider="EchoLabs\Prism\PrismServiceProvider"
```

**Configuration:**
```php
// config/prism.php
return [
    'default_provider' => env('PRISM_PROVIDER', 'anthropic'),
    
    'providers' => [
        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-3-5-sonnet-20241022'),
            'max_tokens' => env('ANTHROPIC_MAX_TOKENS', 2000),
        ],
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4'),
            'max_tokens' => env('OPENAI_MAX_TOKENS', 2000),
        ],
    ],
    
    'cache' => [
        'enabled' => env('PRISM_CACHE_ENABLED', true),
        'ttl' => env('PRISM_CACHE_TTL', 3600),
    ],
];
```

**Environment Variables:**
```env
PRISM_PROVIDER=anthropic
ANTHROPIC_API_KEY=your-api-key-here
ANTHROPIC_MODEL=claude-3-5-sonnet-20241022
ANTHROPIC_MAX_TOKENS=2000

PRISM_CACHE_ENABLED=true
PRISM_CACHE_TTL=3600
```

### 2. Create AI Service

**Purpose:** Central service for AI operations across the application.

**Implementation:**
```php
<?php

namespace App\Services;

use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Enums\Provider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $provider;
    protected string $model;

    public function __construct()
    {
        $this->provider = config('prism.default_provider');
        $this->model = config("prism.providers.{$this->provider}.model");
    }

    /**
     * Generate text completion
     */
    public function generate(string $prompt, array $options = []): string
    {
        $cacheKey = $this->getCacheKey($prompt, $options);
        
        if (config('prism.cache.enabled') && Cache::has($cacheKey)) {
            Log::info('AI response served from cache', ['prompt_hash' => md5($prompt)]);
            return Cache::get($cacheKey);
        }

        try {
            $response = Prism::text()
                ->using(Provider::from($this->provider), $this->model)
                ->withPrompt($prompt)
                ->withMaxTokens($options['max_tokens'] ?? config("prism.providers.{$this->provider}.max_tokens"))
                ->withTemperature($options['temperature'] ?? 0.7)
                ->generate();

            if (config('prism.cache.enabled')) {
                Cache::put($cacheKey, $response, config('prism.cache.ttl'));
            }

            Log::info('AI response generated', [
                'provider' => $this->provider,
                'model' => $this->model,
                'prompt_length' => strlen($prompt),
                'response_length' => strlen($response),
            ]);

            return $response;
        } catch (\Exception $e) {
            Log::error('AI generation failed', [
                'error' => $e->getMessage(),
                'provider' => $this->provider,
            ]);
            throw $e;
        }
    }

    /**
     * Generate with system prompt and context
     */
    public function generateWithContext(
        string $prompt,
        string $systemPrompt,
        array $context = [],
        array $options = []
    ): string {
        $contextString = !empty($context) ? json_encode($context) : '';
        
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $prompt],
        ];
        
        if ($contextString) {
            $messages[] = ['role' => 'assistant', 'content' => "Context: {$contextString}"];
        }

        try {
            $response = Prism::text()
                ->using(Provider::from($this->provider), $this->model)
                ->withMessages($messages)
                ->withMaxTokens($options['max_tokens'] ?? config("prism.providers.{$this->provider}.max_tokens"))
                ->withTemperature($options['temperature'] ?? 0.7)
                ->generate();

            return $response;
        } catch (\Exception $e) {
            Log::error('AI context generation failed', [
                'error' => $e->getMessage(),
                'provider' => $this->provider,
            ]);
            throw $e;
        }
    }

    /**
     * Analyze and extract structured data
     */
    public function analyze(string $text, string $analysisType): array
    {
        $prompts = [
            'sentiment' => "Analyze the sentiment of this text and return JSON with keys: sentiment (positive/negative/neutral), confidence (0-1), reasoning. Text: {$text}",
            'summary' => "Summarize this text in 2-3 sentences. Return JSON with keys: summary, key_points (array). Text: {$text}",
            'keywords' => "Extract keywords from this text. Return JSON with keys: keywords (array), topics (array). Text: {$text}",
        ];

        $prompt = $prompts[$analysisType] ?? throw new \InvalidArgumentException("Unknown analysis type: {$analysisType}");

        $response = $this->generate($prompt);
        
        return json_decode($response, true) ?? [];
    }

    /**
     * Generate cache key for responses
     */
    protected function getCacheKey(string $prompt, array $options): string
    {
        return 'ai:' . md5($prompt . json_encode($options) . $this->provider . $this->model);
    }

    /**
     * Clear AI cache
     */
    public function clearCache(): void
    {
        // Implementation depends on cache driver
        Cache::flush();
    }
}
```

### 3. AI-Powered OTP Messages

**Purpose:** Generate contextual OTP email messages using AI.

**Implementation:**
```php
public function generateOTPMessage(User $user, string $code): string
{
    $aiService = app(AIService::class);
    
    $systemPrompt = "You are an email content writer for a Laravel application. Generate friendly, professional, and secure OTP email messages.";
    
    $prompt = "Generate a friendly OTP email message for {$user->name}. The code is {$code} and expires in " . config('auth.otp.expires_in') . " minutes. Include security reminders.";
    
    $context = [
        'app_name' => config('app.name'),
        'user_name' => $user->name,
        'security_level' => 'high',
    ];
    
    return $aiService->generateWithContext($prompt, $systemPrompt, $context);
}
```

### 4. Smart Team Invitation Messages

**Purpose:** Create personalized team invitation emails using AI.

**Implementation:**
```php
public function generateInvitationMessage(TeamInvitation $invitation): string
{
    $aiService = app(AIService::class);
    
    $systemPrompt = "You are a professional email writer specializing in team collaboration invitations. Write warm, welcoming messages.";
    
    $prompt = "Create a personalized team invitation for {$invitation->email} to join {$invitation->team->name}. The role is {$invitation->role}. Make it friendly and professional.";
    
    $context = [
        'team_name' => $invitation->team->name,
        'team_owner' => $invitation->team->owner->name,
        'role' => $invitation->role,
        'member_count' => $invitation->team->users->count(),
    ];
    
    return $aiService->generateWithContext($prompt, $systemPrompt, $context);
}
```

### 5. Content Moderation

**Purpose:** Use AI to moderate user-generated content.

**Implementation:**
```php
public function moderateContent(string $content): array
{
    $aiService = app(AIService::class);
    
    $systemPrompt = "You are a content moderator. Analyze text for inappropriate content, spam, or security risks.";
    
    $prompt = "Analyze this content and return JSON with: is_safe (boolean), issues (array), severity (low/medium/high), recommendation. Content: {$content}";
    
    $response = $aiService->generate($prompt);
    
    return json_decode($response, true) ?? [
        'is_safe' => true,
        'issues' => [],
        'severity' => 'low',
        'recommendation' => 'Content appears safe.',
    ];
}

// Usage in controller
public function store(Request $request)
{
    $moderation = $this->moderateContent($request->input('content'));
    
    if (!$moderation['is_safe']) {
        return response()->json([
            'error' => 'Content violates community guidelines.',
            'issues' => $moderation['issues'],
        ], 422);
    }
    
    // Proceed with storing content
}
```

### 6. AI-Assisted Code Review

**Purpose:** Use AI to review code changes before commits.

**Implementation:**
```php
public function reviewCode(string $code, string $language = 'php'): array
{
    $aiService = app(AIService::class);
    
    $systemPrompt = "You are a senior {$language} developer and code reviewer. Analyze code for bugs, security issues, performance problems, and best practices.";
    
    $prompt = "Review this {$language} code and return JSON with: issues (array with type, severity, line, description), suggestions (array), security_concerns (array). Code:\n\n{$code}";
    
    $response = $aiService->generateWithContext($prompt, $systemPrompt, [
        'language' => $language,
        'framework' => 'Laravel',
    ]);
    
    return json_decode($response, true) ?? [];
}
```

### 7. Intelligent Search

**Purpose:** Enhance search with AI-powered semantic understanding.

**Implementation:**
```php
public function intelligentSearch(string $query, string $context = 'posts'): Collection
{
    $aiService = app(AIService::class);
    
    // Extract search intent and keywords
    $analysisPrompt = "Analyze this search query and return JSON with: intent, keywords (array), filters (array). Query: {$query}";
    
    $analysis = $aiService->analyze($analysisPrompt, 'keywords');
    
    // Perform search based on AI analysis
    $results = Post::query()
        ->when($analysis['keywords'] ?? [], function ($q, $keywords) {
            foreach ($keywords as $keyword) {
                $q->orWhere('title', 'like', "%{$keyword}%")
                  ->orWhere('content', 'like', "%{$keyword}%");
            }
        })
        ->get();
    
    return $results;
}
```

### 8. Chatbot Integration

**Purpose:** Create AI-powered chatbot for user support.

**Implementation:**
```php
public function chat(string $message, array $conversationHistory = []): string
{
    $aiService = app(AIService::class);
    
    $systemPrompt = "You are a helpful assistant for " . config('app.name') . ", a Laravel application with team management and OTP authentication. Help users with their questions.";
    
    $messages = [
        ['role' => 'system', 'content' => $systemPrompt],
    ];
    
    // Add conversation history
    foreach ($conversationHistory as $msg) {
        $messages[] = [
            'role' => $msg['role'],
            'content' => $msg['content'],
        ];
    }
    
    // Add current message
    $messages[] = [
        'role' => 'user',
        'content' => $message,
    ];
    
    return Prism::text()
        ->using(Provider::from($this->provider), $this->model)
        ->withMessages($messages)
        ->generate();
}

// Livewire component for chat
class ChatBot extends Component
{
    public $messages = [];
    public $input = '';
    
    public function sendMessage()
    {
        $this->messages[] = [
            'role' => 'user',
            'content' => $this->input,
        ];
        
        $response = app(AIService::class)->chat(
            $this->input,
            $this->messages
        );
        
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $response,
        ];
        
        $this->input = '';
    }
    
    public function render()
    {
        return view('livewire.chat-bot');
    }
}
```

## MCP (Model Context Protocol) Integration

### 9. Setup MCP Server

**Purpose:** Enable AI to understand application context via MCP.

**Installation:**
```bash
npm install -g @modelcontextprotocol/server-laravel
```

**MCP Configuration:**
```json
// mcp-config.json
{
  "mcpServers": {
    "laravel-app": {
      "command": "node",
      "args": ["./mcp-server/index.js"],
      "env": {
        "LARAVEL_PATH": "/absolute/path/to/project",
        "DB_CONNECTION": "mysql"
      }
    }
  }
}
```

**MCP Service Implementation:**
```php
<?php

namespace App\Services;

class MCPService
{
    /**
     * Provide application context to MCP
     */
    public function getApplicationContext(): array
    {
        return [
            'models' => $this->getModels(),
            'routes' => $this->getRoutes(),
            'config' => $this->getConfiguration(),
            'database' => $this->getDatabaseSchema(),
        ];
    }

    protected function getModels(): array
    {
        return [
            'User' => [
                'attributes' => ['id', 'name', 'email', 'current_team_id'],
                'relationships' => ['teams', 'ownedTeams'],
            ],
            'Team' => [
                'attributes' => ['id', 'name', 'user_id', 'personal_team'],
                'relationships' => ['owner', 'users', 'teamInvitations'],
            ],
        ];
    }

    protected function getRoutes(): array
    {
        $routes = [];
        
        foreach (Route::getRoutes() as $route) {
            $routes[] = [
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
            ];
        }
        
        return $routes;
    }

    protected function getConfiguration(): array
    {
        return [
            'app_name' => config('app.name'),
            'environment' => app()->environment(),
            'otp_enabled' => config('auth.otp.enabled'),
            'teams_enabled' => true,
        ];
    }

    protected function getDatabaseSchema(): array
    {
        $tables = DB::select('SHOW TABLES');
        $schema = [];
        
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            $columns = DB::select("DESCRIBE {$tableName}");
            $schema[$tableName] = $columns;
        }
        
        return $schema;
    }
}
```

## Testing AI Features

### Test AI Service
```php
public function test_ai_service_generates_text(): void
{
    $service = app(AIService::class);
    
    $response = $service->generate('Hello, AI!');
    
    $this->assertNotEmpty($response);
    $this->assertIsString($response);
}
```

### Test Content Moderation
```php
public function test_moderates_inappropriate_content(): void
{
    $service = app(AIService::class);
    
    $result = $service->moderateContent('This is inappropriate content');
    
    $this->assertArrayHasKey('is_safe', $result);
    $this->assertArrayHasKey('issues', $result);
}
```

## Best Practices

1. **API Key Security:** Never commit API keys; use environment variables
2. **Rate Limiting:** Implement limits to prevent excessive API usage
3. **Caching:** Cache AI responses to reduce costs and latency
4. **Error Handling:** Gracefully handle API failures
5. **Logging:** Track AI usage for monitoring and debugging
6. **Cost Management:** Monitor API usage and costs
7. **Privacy:** Be cautious about sending user data to external APIs

## Cost Optimization

```php
// Implement usage tracking
public function trackUsage(string $operation, int $tokens): void
{
    DB::table('ai_usage')->insert([
        'operation' => $operation,
        'tokens' => $tokens,
        'cost' => $this->calculateCost($tokens),
        'created_at' => now(),
    ]);
}

// Monitor monthly costs
public function getMonthlyUsage(): array
{
    return DB::table('ai_usage')
        ->whereMonth('created_at', now()->month)
        ->select(
            DB::raw('SUM(tokens) as total_tokens'),
            DB::raw('SUM(cost) as total_cost'),
            DB::raw('COUNT(*) as total_requests')
        )
        ->first();
}
```

---

**Related Skills:**
- [OTP Authentication Skills](otp-authentication-skills.md)
- [Team Management Skills](team-management-skills.md)
- [Security Best Practices](security-best-practices.md)
