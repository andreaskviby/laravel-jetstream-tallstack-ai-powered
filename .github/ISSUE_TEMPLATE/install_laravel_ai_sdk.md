---
name: Install Laravel AI SDK or Prism
about: Guide for installing Laravel AI SDK or Laravel Prism for AI integration
title: '[SETUP] Install Laravel AI SDK / Laravel Prism'
labels: ['enhancement', 'ai-tools', 'setup']
assignees: ''
---

## Laravel AI SDK / Laravel Prism Installation

Choose between Laravel AI SDK (official) or Laravel Prism (community favorite) for AI integration in your Laravel application.

### Option A: Laravel AI SDK (Official)

#### Prerequisites
- Laravel 11+ installed
- PHP 8.2 or higher
- OpenAI, Anthropic, or compatible API key

#### Installation Steps

1. **Install Laravel AI SDK:**
   ```bash
   composer require laravel/ai
   ```

2. **Publish Configuration:**
   ```bash
   php artisan vendor:publish --tag=ai-config
   ```

3. **Configure AI Provider:**
   Add to `.env`:
   ```env
   AI_PROVIDER=anthropic
   ANTHROPIC_API_KEY=your-api-key-here
   ANTHROPIC_MODEL=claude-3-5-sonnet-20241022
   ```

4. **Test Installation:**
   ```bash
   php artisan tinker
   >>> use Laravel\AI\Facades\AI;
   >>> AI::chat('Hello, AI!');
   ```

#### Features
- ✨ Multi-provider support (OpenAI, Anthropic, etc.)
- 💬 Chat completions
- 🔄 Streaming responses
- 🎯 Function calling
- 📊 Embeddings generation

---

### Option B: Laravel Prism (Recommended for this Project)

#### Prerequisites
- Laravel 11+ installed
- PHP 8.2 or higher
- API key for chosen provider

#### Installation Steps

1. **Install Laravel Prism:**
   ```bash
   composer require echolabsdev/prism
   ```

2. **Publish Configuration:**
   ```bash
   php artisan vendor:publish --provider="EchoLabs\Prism\PrismServiceProvider"
   ```

3. **Configure Providers:**
   Add to `.env`:
   ```env
   PRISM_PROVIDER=anthropic
   ANTHROPIC_API_KEY=your-api-key-here
   ANTHROPIC_MODEL=claude-3-5-sonnet-20241022
   
   # Optional: Multiple providers
   OPENAI_API_KEY=your-openai-key
   OPENAI_MODEL=gpt-4
   ```

4. **Create AI Service:**
   ```bash
   php artisan make:service AIService
   ```

5. **Basic Integration:**
   ```php
   <?php

   namespace App\Services;

   use EchoLabs\Prism\Prism;
   use EchoLabs\Prism\Enums\Provider;

   class AIService
   {
       public function chat(string $message): string
       {
           return Prism::text()
               ->using(Provider::Anthropic, 'claude-3-5-sonnet-20241022')
               ->withPrompt($message)
               ->generate();
       }

       public function generateWithContext(string $prompt, array $context): string
       {
           return Prism::text()
               ->using(Provider::Anthropic, 'claude-3-5-sonnet-20241022')
               ->withSystemPrompt('You are a Laravel development assistant.')
               ->withMessages([
                   ['role' => 'user', 'content' => $prompt],
                   ['role' => 'assistant', 'content' => json_encode($context)],
               ])
               ->generate();
       }
   }
   ```

6. **Test Installation:**
   ```bash
   php artisan tinker
   >>> app(App\Services\AIService::class)->chat('Hello!');
   ```

#### Features
- 🎨 Clean, fluent API
- 🔄 Multiple provider support
- 💬 Structured outputs
- 🎯 Tool/function calling
- 📦 Response caching
- 🔀 Provider switching
- 🎭 Prompt templates

---

### Integration Examples

#### OTP Generation with AI
```php
use App\Services\AIService;

class OTPService
{
    public function generateSmartOTP(User $user): string
    {
        $ai = app(AIService::class);
        
        // Generate context-aware OTP message
        $message = $ai->generateWithContext(
            "Generate a friendly OTP email message for {$user->name}",
            ['context' => 'login', 'security_level' => 'high']
        );
        
        return $message;
    }
}
```

#### Team Invitation Enhancement
```php
public function generateInvitationMessage(TeamInvitation $invitation): string
{
    return Prism::text()
        ->using(Provider::Anthropic, 'claude-3-5-sonnet-20241022')
        ->withSystemPrompt('Generate personalized team invitation messages.')
        ->withPrompt("Create an invitation for {$invitation->email} to join {$invitation->team->name}")
        ->generate();
}
```

#### AI-Powered Code Reviews
```php
public function reviewCode(string $code): array
{
    $analysis = Prism::text()
        ->using(Provider::Anthropic, 'claude-3-5-sonnet-20241022')
        ->withSystemPrompt('You are a Laravel code reviewer.')
        ->withPrompt("Review this code:\n\n{$code}")
        ->generate();
    
    return json_decode($analysis, true);
}
```

---

### Configuration Options

#### Prism Config (`config/prism.php`)
```php
return [
    'providers' => [
        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-3-5-sonnet-20241022'),
        ],
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4'),
        ],
    ],
    
    'defaults' => [
        'provider' => env('PRISM_PROVIDER', 'anthropic'),
        'temperature' => 0.7,
        'max_tokens' => 2000,
    ],
];
```

---

### Documentation
- [Laravel AI SDK](https://laravel.com/docs/ai)
- [Laravel Prism](https://github.com/echolabsdev/prism)
- [Prism Documentation](https://prism.echolabs.dev)
- [Anthropic API](https://docs.anthropic.com)

### Verification Checklist
- [ ] AI SDK/Prism installed via Composer
- [ ] Configuration published
- [ ] API keys configured in `.env`
- [ ] AIService created (if using Prism)
- [ ] Test chat completion successful
- [ ] Integration with at least one feature (OTP/Teams)

### Related Issues
- #XX - Install Laravel Boost
- #XX - Install MCP
- #XX - Create Claude Code Agents

---

**Recommendation:** Use Laravel Prism for this project as it offers better multi-provider support and more flexibility for AI-powered features.

**Note:** Complete this installation before creating AI-powered features and agents.
