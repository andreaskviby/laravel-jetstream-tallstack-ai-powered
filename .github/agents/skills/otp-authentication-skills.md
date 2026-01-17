# OTP Authentication Skills

## Overview
Skills for implementing, extending, and maintaining the OTP (One-Time Password) authentication system in this Laravel Jetstream TALL Stack project.

## Core Skills

### 1. Generate OTP Code

**Purpose:** Generate a secure 6-digit OTP code for user authentication.

**Implementation:**
```php
public function generateOTP(User $user): string
{
    // Generate 6-digit code
    $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    
    // Hash and store in cache
    Cache::put(
        "otp:{$user->id}",
        Hash::make($code),
        now()->addMinutes(config('auth.otp.expires_in', 10))
    );
    
    // Log OTP generation for security audit
    Log::info('OTP generated', [
        'user_id' => $user->id,
        'expires_at' => now()->addMinutes(config('auth.otp.expires_in', 10)),
    ]);
    
    return $code;
}
```

**Key Points:**
- Always use cryptographically secure random number generation
- Hash OTP before storing (never store plain text)
- Set appropriate expiration time (default: 10 minutes)
- Log generation for audit trail
- Use cache for temporary storage

### 2. Verify OTP Code

**Purpose:** Verify user-provided OTP code against stored hash.

**Implementation:**
```php
public function verifyOTP(User $user, string $code): bool
{
    $cacheKey = "otp:{$user->id}";
    $hashedCode = Cache::get($cacheKey);
    
    if (!$hashedCode) {
        Log::warning('OTP verification failed: code expired or not found', [
            'user_id' => $user->id,
        ]);
        return false;
    }
    
    // Verify code
    if (Hash::check($code, $hashedCode)) {
        // Clear OTP after successful verification
        Cache::forget($cacheKey);
        
        Log::info('OTP verified successfully', [
            'user_id' => $user->id,
        ]);
        
        return true;
    }
    
    // Increment failed attempts
    $attempts = Cache::increment("otp:attempts:{$user->id}");
    
    if ($attempts >= 3) {
        // Block further attempts for 15 minutes after 3 failures
        Cache::put("otp:blocked:{$user->id}", true, now()->addMinutes(15));
        Cache::forget($cacheKey);
        
        Log::warning('OTP blocked due to too many failed attempts', [
            'user_id' => $user->id,
        ]);
    }
    
    return false;
}
```

**Key Points:**
- Check for code expiration
- Use Hash::check for secure comparison
- Delete OTP after successful verification (one-time use)
- Implement rate limiting on failed attempts
- Log all verification attempts

### 3. Send OTP via Email

**Purpose:** Send OTP code to user's email address.

**Implementation:**
```php
public function sendOTP(User $user, string $code): void
{
    // Check if blocked
    if (Cache::has("otp:blocked:{$user->id}")) {
        throw new \Exception('Too many failed attempts. Please try again later.');
    }
    
    // Rate limit OTP sending (max 5 per hour)
    $sendKey = "otp:send:{$user->id}";
    $sendCount = Cache::get($sendKey, 0);
    
    if ($sendCount >= 5) {
        throw new \Exception('Too many OTP requests. Please try again later.');
    }
    
    Cache::increment($sendKey);
    Cache::put($sendKey, $sendCount + 1, now()->addHour());
    
    // Send email
    Mail::to($user->email)->send(new OTPCodeMail($code, $user));
    
    Log::info('OTP sent via email', [
        'user_id' => $user->id,
        'email' => $user->email,
    ]);
}
```

**Email Template Structure:**
```blade
@component('mail::message')
# Your Login Code

Hello {{ $user->name }},

Your one-time password (OTP) is:

@component('mail::panel')
# {{ $code }}
@endcomponent

This code will expire in {{ config('auth.otp.expires_in') }} minutes.

**Security Notice:** Never share this code with anyone. Our team will never ask for your OTP code.

@if(app()->environment('local'))
*You're using the local environment with prefilled OTP enabled. Use code: {{ config('auth.otp.default_code') }}*
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
```

### 4. Handle Local Development Prefill

**Purpose:** Simplify local development with prefilled OTP code.

**Implementation:**
```php
public function shouldPrefillOTP(): bool
{
    return app()->environment('local') 
        && config('auth.otp.prefill_local', true);
}

public function getOTPForEnvironment(User $user): string
{
    if ($this->shouldPrefillOTP()) {
        $code = config('auth.otp.default_code', '123456');
        
        // Still store hashed version for consistency
        Cache::put(
            "otp:{$user->id}",
            Hash::make($code),
            now()->addMinutes(config('auth.otp.expires_in', 10))
        );
        
        return $code;
    }
    
    return $this->generateOTP($user);
}
```

**Configuration:**
```php
// config/auth.php
'otp' => [
    'enabled' => env('OTP_ENABLED', true),
    'length' => env('OTP_LENGTH', 6),
    'expires_in' => env('OTP_EXPIRES_IN', 10), // minutes
    'prefill_local' => env('OTP_PREFILL_LOCAL', true),
    'default_code' => env('OTP_DEFAULT_CODE', '123456'),
],
```

### 5. Implement OTP Resend

**Purpose:** Allow users to request new OTP if previous one expired.

**Implementation:**
```php
public function resendOTP(User $user): array
{
    // Check if can resend (rate limiting)
    $lastSentKey = "otp:last_sent:{$user->id}";
    $lastSent = Cache::get($lastSentKey);
    
    if ($lastSent && now()->diffInSeconds($lastSent) < 60) {
        $waitTime = 60 - now()->diffInSeconds($lastSent);
        return [
            'success' => false,
            'message' => "Please wait {$waitTime} seconds before requesting a new code.",
        ];
    }
    
    // Clear previous OTP
    Cache::forget("otp:{$user->id}");
    
    // Generate and send new OTP
    $code = $this->getOTPForEnvironment($user);
    
    if (!$this->shouldPrefillOTP()) {
        $this->sendOTP($user, $code);
    }
    
    // Update last sent timestamp
    Cache::put($lastSentKey, now(), now()->addMinutes(5));
    
    return [
        'success' => true,
        'message' => 'New OTP code sent to your email.',
        'expires_in' => config('auth.otp.expires_in') * 60, // seconds
    ];
}
```

## Advanced Skills

### 6. OTP with SMS Support

**Purpose:** Send OTP via SMS as alternative to email.

**Implementation:**
```php
use Twilio\Rest\Client;

public function sendOTPViaSMS(User $user, string $code): void
{
    $twilio = new Client(
        config('services.twilio.sid'),
        config('services.twilio.token')
    );
    
    $message = "Your {config('app.name')} login code is: {$code}. Valid for {config('auth.otp.expires_in')} minutes.";
    
    $twilio->messages->create(
        $user->phone,
        [
            'from' => config('services.twilio.from'),
            'body' => $message,
        ]
    );
    
    Log::info('OTP sent via SMS', [
        'user_id' => $user->id,
        'phone' => $user->phone,
    ]);
}
```

### 7. OTP Analytics

**Purpose:** Track OTP usage patterns for security and UX improvements.

**Implementation:**
```php
public function recordOTPMetrics(User $user, string $action, bool $success): void
{
    DB::table('otp_metrics')->insert([
        'user_id' => $user->id,
        'action' => $action, // 'generated', 'verified', 'failed', 'expired'
        'success' => $success,
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'created_at' => now(),
    ]);
}

public function getOTPStats(): array
{
    return [
        'total_generated' => DB::table('otp_metrics')
            ->where('action', 'generated')
            ->whereDate('created_at', today())
            ->count(),
        'total_verified' => DB::table('otp_metrics')
            ->where('action', 'verified')
            ->where('success', true)
            ->whereDate('created_at', today())
            ->count(),
        'failed_attempts' => DB::table('otp_metrics')
            ->where('action', 'verified')
            ->where('success', false)
            ->whereDate('created_at', today())
            ->count(),
    ];
}
```

### 8. Backup Authentication Method

**Purpose:** Provide backup codes for users who can't receive OTP.

**Implementation:**
```php
public function generateBackupCodes(User $user): array
{
    $codes = [];
    
    for ($i = 0; $i < 10; $i++) {
        $codes[] = Str::upper(Str::random(8));
    }
    
    // Store hashed backup codes
    $user->backup_codes = collect($codes)->map(fn($code) => Hash::make($code))->toArray();
    $user->save();
    
    return $codes; // Return plain codes to user (only shown once)
}

public function verifyBackupCode(User $user, string $code): bool
{
    foreach ($user->backup_codes ?? [] as $index => $hashedCode) {
        if (Hash::check($code, $hashedCode)) {
            // Remove used backup code
            $backupCodes = $user->backup_codes;
            unset($backupCodes[$index]);
            $user->backup_codes = array_values($backupCodes);
            $user->save();
            
            return true;
        }
    }
    
    return false;
}
```

## Testing Skills

### Test OTP Generation
```php
public function test_generates_valid_otp_code(): void
{
    $user = User::factory()->create();
    $service = new OTPService();
    
    $code = $service->generateOTP($user);
    
    $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
    $this->assertTrue(Cache::has("otp:{$user->id}"));
}
```

### Test OTP Expiration
```php
public function test_otp_expires_after_configured_time(): void
{
    $user = User::factory()->create();
    $service = new OTPService();
    
    $code = $service->generateOTP($user);
    
    $this->travel(11)->minutes();
    
    $this->assertFalse($service->verifyOTP($user, $code));
}
```

## Configuration Best Practices

1. **Environment-specific settings:** Always use `.env` for configuration
2. **Security:** Never log plain OTP codes in production
3. **Expiration:** Keep OTP lifetime short (5-10 minutes)
4. **Rate limiting:** Prevent abuse with request limits
5. **Monitoring:** Track failed attempts and suspicious patterns

## Troubleshooting

### Common Issues:
1. **OTP not received:** Check mail configuration and queue status
2. **OTP expired immediately:** Verify cache driver is working
3. **Can't verify OTP:** Ensure Hash::check uses same algorithm
4. **Too many requests:** Check rate limiting configuration

---

**Related Skills:**
- [Team Management Skills](team-management-skills.md)
- [Security Best Practices](security-best-practices.md)
- [Email Configuration Skills](email-configuration-skills.md)
