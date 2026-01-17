# Security Best Practices Skills

## Overview
Security best practices and skills specific to this Laravel Jetstream TALL Stack project with OTP authentication and team management.

## Core Security Skills

### 1. Input Validation & Sanitization

**Purpose:** Prevent injection attacks and ensure data integrity.

**Implementation:**
```php
// Form Request validation
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Post::class);
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-_]+$/', // Alphanumeric with common chars
            ],
            'content' => [
                'required',
                'string',
                'max:50000',
            ],
            'status' => [
                'required',
                Rule::in(['draft', 'published', 'archived']),
            ],
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'tags' => [
                'nullable',
                'array',
                'max:10',
            ],
            'tags.*' => [
                'integer',
                'exists:tags,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.regex' => 'Title can only contain letters, numbers, spaces, hyphens, and underscores.',
        ];
    }

    /**
     * Prepare data for validation
     */
    protected function prepareForValidation(): void
    {
        // Sanitize inputs
        $this->merge([
            'title' => strip_tags($this->title),
            'content' => $this->sanitizeHtml($this->content),
        ]);
    }

    /**
     * Sanitize HTML content
     */
    protected function sanitizeHtml(string $html): string
    {
        // Use HTMLPurifier or similar
        $config = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($html);
    }
}
```

### 2. SQL Injection Prevention

**Purpose:** Prevent SQL injection attacks using Eloquent and query builder.

**Safe Practices:**
```php
// ✅ GOOD: Using Eloquent and parameter binding
public function getUserPosts(int $userId): Collection
{
    return Post::where('user_id', $userId)
        ->where('status', 'published')
        ->get();
}

// ✅ GOOD: Query builder with bindings
public function searchPosts(string $term): Collection
{
    return DB::table('posts')
        ->where('title', 'like', '%' . $term . '%')
        ->orWhere('content', 'like', '%' . $term . '%')
        ->get();
}

// ✅ GOOD: Raw queries with parameter binding
public function complexQuery(array $ids): Collection
{
    return DB::select(
        'SELECT * FROM posts WHERE id IN (?) AND status = ?',
        [implode(',', $ids), 'published']
    );
}

// ❌ BAD: Never concatenate user input
public function dangerousQuery(string $userInput): Collection
{
    // NEVER DO THIS!
    return DB::select("SELECT * FROM posts WHERE title = '{$userInput}'");
}
```

### 3. XSS (Cross-Site Scripting) Prevention

**Purpose:** Prevent XSS attacks in views and responses.

**Blade Template Security:**
```blade
{{-- ✅ GOOD: Automatic escaping --}}
<h1>{{ $post->title }}</h1>
<p>{{ $user->name }}</p>

{{-- ✅ GOOD: For trusted HTML (use with caution) --}}
<div>{!! Purifier::clean($post->content) !!}</div>

{{-- ❌ BAD: Never output unescaped user input --}}
<div>{!! $post->content !!}</div> {{-- DANGEROUS! --}}

{{-- ✅ GOOD: Attribute escaping --}}
<input type="text" value="{{ old('title', $post->title) }}">
<a href="{{ route('posts.show', $post) }}">Read more</a>

{{-- ✅ GOOD: JavaScript data --}}
<script>
    const postData = @json($post); // Safe JSON encoding
</script>

{{-- ❌ BAD: Direct embedding --}}
<script>
    const postData = {!! $post !!}; // DANGEROUS!
</script>
```

**API Response Security:**
```php
// Use API Resources to control output
public function show(Post $post): JsonResponse
{
    return response()->json(new PostResource($post));
}

// Never directly return Eloquent models
// return response()->json($post); // Can expose hidden attributes
```

### 4. CSRF Protection

**Purpose:** Prevent Cross-Site Request Forgery attacks.

**Implementation:**
```blade
{{-- Laravel automatically includes CSRF token in forms --}}
<form method="POST" action="/posts">
    @csrf
    {{-- Form fields --}}
</form>

{{-- For AJAX requests --}}
<script>
// Laravel sets X-CSRF-TOKEN header automatically if meta tag exists
<meta name="csrf-token" content="{{ csrf_token() }}">

// Axios configuration
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

// Fetch API
fetch('/api/posts', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json',
    },
    body: JSON.stringify(data),
});
</script>
```

**Excluding Routes from CSRF:**
```php
// app/Http/Middleware/VerifyCsrfToken.php
protected $except = [
    'api/*', // API routes typically use token authentication
    'webhooks/*', // Webhooks with signature verification
];
```

### 5. Authentication & Authorization

**Purpose:** Secure access to resources and actions.

**OTP Security Enhancements:**
```php
public function verifyOTPWithRateLimiting(User $user, string $code): bool
{
    $key = "otp:attempts:{$user->id}";
    $blockKey = "otp:blocked:{$user->id}";
    
    // Check if blocked
    if (Cache::has($blockKey)) {
        throw new TooManyAttemptsException('Account temporarily locked. Try again in 15 minutes.');
    }
    
    // Get cached OTP
    $hashedCode = Cache::get("otp:{$user->id}");
    
    if (!$hashedCode) {
        return false;
    }
    
    // Verify with constant-time comparison
    if (!Hash::check($code, $hashedCode)) {
        $attempts = Cache::increment($key);
        
        if ($attempts >= 3) {
            Cache::put($blockKey, true, now()->addMinutes(15));
            Cache::forget("otp:{$user->id}");
            
            // Notify user of suspicious activity
            Mail::to($user->email)->send(new SuspiciousActivityAlert($user));
        }
        
        return false;
    }
    
    // Success - clear everything
    Cache::forget("otp:{$user->id}");
    Cache::forget($key);
    
    return true;
}
```

**Policy-Based Authorization:**
```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;

class PostPolicy
{
    public function view(User $user, Post $post): bool
    {
        // Public posts can be viewed by anyone
        if ($post->status === 'published') {
            return true;
        }
        
        // Drafts only visible to author or team admins
        return $user->id === $post->user_id 
            || $user->hasTeamRole($post->team, 'admin');
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id 
            || $user->hasTeamRole($post->team, ['admin', 'editor']);
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id 
            || $user->hasTeamRole($post->team, 'admin');
    }
}
```

### 6. Secure File Uploads

**Purpose:** Prevent malicious file uploads.

**Implementation:**
```php
public function handleFileUpload(Request $request): string
{
    $request->validate([
        'file' => [
            'required',
            'file',
            'max:10240', // 10MB
            'mimes:jpeg,png,pdf,doc,docx',
        ],
    ]);
    
    $file = $request->file('file');
    
    // Generate unique filename
    $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
    
    // Verify MIME type
    $mimeType = $file->getMimeType();
    $allowedMimes = ['image/jpeg', 'image/png', 'application/pdf'];
    
    if (!in_array($mimeType, $allowedMimes)) {
        throw ValidationException::withMessages([
            'file' => 'Invalid file type.',
        ]);
    }
    
    // Store in private storage (not publicly accessible)
    $path = $file->storeAs('uploads', $filename, 'private');
    
    // Scan for malware (if available)
    if (class_exists('VirusScan')) {
        $scan = VirusScan::scan(storage_path("app/{$path}"));
        if ($scan->infected()) {
            Storage::disk('private')->delete($path);
            throw new Exception('File contains malware.');
        }
    }
    
    return $path;
}

// Controller method to download file
public function download(string $filename)
{
    // Verify user authorization
    $this->authorize('download', $filename);
    
    $path = "uploads/{$filename}";
    
    if (!Storage::disk('private')->exists($path)) {
        abort(404);
    }
    
    return Storage::disk('private')->download($path);
}
```

### 7. Secure Session Management

**Purpose:** Protect user sessions from hijacking.

**Configuration:**
```php
// config/session.php
return [
    'driver' => env('SESSION_DRIVER', 'database'),
    'lifetime' => env('SESSION_LIFETIME', 120), // minutes
    'expire_on_close' => false,
    'encrypt' => true,
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => 'sessions',
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env('SESSION_COOKIE', 'laravel_session'),
    'path' => '/',
    'domain' => env('SESSION_DOMAIN'),
    'secure' => env('SESSION_SECURE_COOKIE', true), // HTTPS only
    'http_only' => true, // Prevent JavaScript access
    'same_site' => 'lax', // CSRF protection
];
```

**Session Regeneration:**
```php
// After successful OTP verification
public function verifyOTP(Request $request)
{
    $user = User::where('email', $request->email)->first();
    
    if ($this->otpService->verify($user, $request->code)) {
        // Regenerate session ID to prevent fixation
        $request->session()->regenerate();
        
        // Login user
        Auth::login($user);
        
        // Log successful login
        Log::info('User logged in', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->intended('/dashboard');
    }
    
    return back()->withErrors(['code' => 'Invalid or expired code.']);
}
```

### 8. API Security

**Purpose:** Secure API endpoints with proper authentication and rate limiting.

**Sanctum Token Authentication:**
```php
// routes/api.php
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::apiResource('posts', PostController::class);
    Route::apiResource('teams', TeamController::class);
});

// Create token with abilities
$token = $user->createToken('api-token', ['posts:read', 'posts:create'])->plainTextToken;

// Check abilities in controller
public function store(Request $request)
{
    if (!$request->user()->tokenCan('posts:create')) {
        return response()->json(['error' => 'Forbidden'], 403);
    }
    
    // Create post
}
```

**Rate Limiting:**
```php
// app/Providers/RouteServiceProvider.php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

protected function configureRateLimiting()
{
    // API rate limiting
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });
    
    // Strict limit for OTP endpoints
    RateLimiter::for('otp', function (Request $request) {
        return Limit::perMinute(5)
            ->by($request->email ?: $request->ip())
            ->response(function () {
                return response()->json([
                    'message' => 'Too many attempts. Please try again later.',
                ], 429);
            });
    });
    
    // Login attempts
    RateLimiter::for('login', function (Request $request) {
        return Limit::perMinute(10)->by($request->email . $request->ip());
    });
}
```

### 9. Logging & Monitoring

**Purpose:** Track security events for audit and incident response.

**Security Event Logging:**
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SecurityLogger
{
    public static function logLoginAttempt(User $user, bool $success, string $reason = null): void
    {
        Log::channel('security')->info('Login attempt', [
            'user_id' => $user->id,
            'email' => $user->email,
            'success' => $success,
            'reason' => $reason,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
    }

    public static function logSuspiciousActivity(User $user, string $activity, array $details = []): void
    {
        Log::channel('security')->warning('Suspicious activity', [
            'user_id' => $user->id,
            'activity' => $activity,
            'details' => $details,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
        
        // Alert administrators
        // Notification::route('slack', config('services.slack.security'))
        //     ->notify(new SuspiciousActivityNotification($user, $activity));
    }

    public static function logDataAccess(User $user, string $resource, string $action): void
    {
        Log::channel('audit')->info('Data access', [
            'user_id' => $user->id,
            'resource' => $resource,
            'action' => $action,
            'ip' => request()->ip(),
            'timestamp' => now(),
        ]);
    }
}
```

### 10. Environment Security

**Purpose:** Secure configuration and secrets management.

**Best Practices:**
```php
// ✅ GOOD: Use environment variables
$apiKey = env('CLAUDE_API_KEY');
$dbPassword = env('DB_PASSWORD');

// ❌ BAD: Never hardcode secrets
$apiKey = 'sk-ant-api03-...'; // NEVER DO THIS!

// ✅ GOOD: Validate environment in production
if (app()->environment('production')) {
    if (empty(config('app.key'))) {
        throw new Exception('Application key not set!');
    }
    
    if (!config('session.secure')) {
        throw new Exception('Secure cookies must be enabled in production!');
    }
}

// ✅ GOOD: Encrypt sensitive configuration
// config/services.php
return [
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'), // Encrypted in .env
    ],
];
```

**File Permissions:**
```bash
# Set proper permissions
chmod 600 .env
chmod 755 storage
chmod 755 bootstrap/cache

# Ensure web server can't write to app code
chmod 444 app/**/*.php
chmod 444 config/*.php
```

## Security Checklist

- [ ] All user inputs validated and sanitized
- [ ] SQL queries use parameter binding
- [ ] XSS protection in all views
- [ ] CSRF protection enabled
- [ ] File uploads validated and scanned
- [ ] Passwords/OTPs hashed with bcrypt
- [ ] HTTPS enabled in production
- [ ] Session security configured
- [ ] Rate limiting implemented
- [ ] Security events logged
- [ ] Regular security updates applied
- [ ] Environment variables secured
- [ ] API authentication enforced
- [ ] Authorization policies implemented
- [ ] Error messages don't leak information

## Common Vulnerabilities to Avoid

1. **Mass Assignment:** Use `$fillable` or `$guarded` in models
2. **Insecure Deserialization:** Validate serialized data
3. **Directory Traversal:** Validate file paths
4. **Information Disclosure:** Don't expose stack traces
5. **Unvalidated Redirects:** Validate redirect URLs
6. **Weak Cryptography:** Use modern encryption
7. **Missing Authorization:** Check permissions on every action

---

**Related Skills:**
- [OTP Authentication Skills](otp-authentication-skills.md)
- [Team Management Skills](team-management-skills.md)
- [AI Integration Skills](ai-integration-skills.md)
