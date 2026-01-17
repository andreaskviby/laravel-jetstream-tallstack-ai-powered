# Optional Features Setup Guide

This guide explains how to add optional features to your Laravel Jetstream TALL Stack AI-Powered application using the provided integration stubs.

## Overview

All optional features are provided as well-documented stub files in the `setup/stubs/` directory. Each stub contains:
- Complete implementation examples
- Environment configuration instructions
- Migration templates
- Controller code
- View examples
- Security best practices
- Testing information

## Quick Setup Checklist

### Before You Start
- ✅ Complete the main installation
- ✅ Run `php artisan migrate`
- ✅ Ensure your application is working

### Available Features
- 🎨 [Filament 4 Admin Panel](#filament-4-admin-panel)
- 💳 [Payment Integration](#payment-integration) (Stripe, Lemon Squeezy, PayPal)
- 🔐 [Social Login](#social-login) (Google, Facebook, GitHub, etc.)
- 📁 [Split Storage](#split-storage) (Local + S3/Cloud)

---

## Filament 4 Admin Panel

### What You Get
- Modern admin panel built with TALL stack
- Form builders and table builders
- Resource management
- User management interface
- Plugin ecosystem

### Installation Steps

1. **Run the installation helper:**
   ```bash
   php setup/stubs/filament-install.stub
   ```

2. **Follow the prompts** to create an admin user

3. **Access your admin panel:**
   ```
   http://localhost:8000/admin
   ```

4. **Create your first resource:**
   ```bash
   php artisan make:filament-resource Post
   ```

### Resources
- Stub file: `setup/stubs/filament-install.stub`
- Documentation: https://filamentphp.com/docs
- Plugins: https://filamentphp.com/plugins

---

## Payment Integration

Choose the payment provider that best fits your needs:

### Option 1: Stripe (via Laravel Cashier)

**Best for:** Subscriptions, most apps, great documentation

1. **Install Cashier:**
   ```bash
   composer require laravel/cashier
   ```

2. **Review the integration stub:**
   ```bash
   cat setup/stubs/StripeIntegration.stub
   ```

3. **Add Billable trait to User model:**
   ```php
   use Laravel\Cashier\Billable;
   
   class User extends Authenticatable
   {
       use Billable;
   }
   ```

4. **Run migrations:**
   ```bash
   php artisan migrate
   ```

5. **Configure .env:**
   ```env
   STRIPE_KEY=pk_test_your_key
   STRIPE_SECRET=sk_test_your_secret
   STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
   ```

6. **Implement subscription logic** using examples from the stub

### Option 2: Lemon Squeezy

**Best for:** SaaS products, global compliance, tax handling

1. **Install package:**
   ```bash
   composer require lemonsqueezy/laravel
   ```

2. **Review the integration stub:**
   ```bash
   cat setup/stubs/LemonSqueezyIntegration.stub
   ```

3. **Publish config:**
   ```bash
   php artisan vendor:publish --tag="lemon-squeezy-config"
   ```

4. **Configure .env:**
   ```env
   LEMON_SQUEEZY_API_KEY=your_api_key
   LEMON_SQUEEZY_STORE_ID=your_store_id
   LEMON_SQUEEZY_SIGNING_SECRET=your_webhook_secret
   ```

5. **Run migrations:**
   ```bash
   php artisan migrate
   ```

6. **Implement checkout logic** using examples from the stub

### Option 3: PayPal

**Best for:** PayPal users, global reach

1. **Install PayPal SDK:**
   ```bash
   composer require paypal/rest-api-sdk-php
   ```

2. **Review the integration stub:**
   ```bash
   cat setup/stubs/PayPalIntegration.stub
   ```

3. **Create config file:**
   ```bash
   # Copy config example from stub to config/paypal.php
   ```

4. **Configure .env:**
   ```env
   PAYPAL_MODE=sandbox
   PAYPAL_SANDBOX_CLIENT_ID=your_client_id
   PAYPAL_SANDBOX_CLIENT_SECRET=your_secret
   ```

5. **Implement payment logic** using examples from the stub

### Payment Provider Comparison

| Feature | Stripe | Lemon Squeezy | PayPal |
|---------|--------|---------------|--------|
| Laravel Package | ✅ Official | ✅ Official | ❌ Community |
| Subscriptions | ✅ Excellent | ✅ Good | ⚠️ Manual |
| Tax Handling | ⚠️ Manual | ✅ Automatic | ⚠️ Manual |
| Setup Difficulty | Easy | Very Easy | Moderate |

---

## Social Login

### What You Get
- OAuth authentication with major providers
- Automatic account creation/linking
- Avatar sync from social profiles
- Seamless user experience

### Installation Steps

1. **Install Socialite:**
   ```bash
   composer require laravel/socialite
   ```

2. **Review the integration stub:**
   ```bash
   cat setup/stubs/SocialiteIntegration.stub
   ```

3. **Add migration:**
   ```bash
   php artisan make:migration add_social_login_columns_to_users_table
   ```
   
   Copy migration code from the stub file.

4. **Run migration:**
   ```bash
   php artisan migrate
   ```

5. **Configure OAuth apps:**
   - **Google**: https://console.developers.google.com/
   - **Facebook**: https://developers.facebook.com/
   - **GitHub**: https://github.com/settings/developers
   - **Twitter**: https://developer.twitter.com/

6. **Add to .env:**
   ```env
   GOOGLE_CLIENT_ID=your_client_id
   GOOGLE_CLIENT_SECRET=your_secret
   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
   
   # Repeat for other providers
   ```

7. **Add to config/services.php:**
   ```php
   'google' => [
       'client_id' => env('GOOGLE_CLIENT_ID'),
       'client_secret' => env('GOOGLE_CLIENT_SECRET'),
       'redirect' => env('GOOGLE_REDIRECT_URI'),
   ],
   ```

8. **Create controller:**
   ```bash
   php artisan make:controller Auth/SocialLoginController
   ```
   
   Copy controller code from the stub file.

9. **Add routes** (copy from stub file)

10. **Add login buttons** to your login view

### Supported Providers
- ✅ Google (2FA, most popular)
- ✅ Facebook (social networks)
- ✅ GitHub (developer tools)
- ✅ Twitter/X (social apps)
- 🔧 LinkedIn, Apple, Microsoft (via SocialiteProviders)

---

## Split Storage

### What You Get
- Multiple storage disks (local, S3, Spaces, R2)
- Automatic file type routing
- Cost optimization strategies
- CDN integration support

### Use Cases
- Store avatars locally (fast, cheap)
- Store product images on S3 (scalable)
- Keep private documents secure (S3 private)
- Serve assets via CDN (fast global delivery)

### Installation Steps

1. **For S3 support, install package:**
   ```bash
   composer require league/flysystem-aws-s3-v3 "^3.0"
   ```

2. **Review the configuration stub:**
   ```bash
   cat setup/stubs/FlysystemSplitStorage.stub
   ```

3. **Configure .env:**
   ```env
   # AWS S3
   AWS_ACCESS_KEY_ID=your_key
   AWS_SECRET_ACCESS_KEY=your_secret
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=your_bucket
   AWS_URL=https://your-bucket.s3.amazonaws.com
   
   # DigitalOcean Spaces (optional)
   DO_SPACES_KEY=your_key
   DO_SPACES_SECRET=your_secret
   DO_SPACES_ENDPOINT=https://nyc3.digitaloceanspaces.com
   DO_SPACES_BUCKET=your_bucket
   ```

4. **Update config/filesystems.php:**
   
   Copy disk configurations from the stub file.

5. **Create storage service:**
   ```bash
   php artisan make:service FileStorageService
   ```
   
   Copy service code from the stub file.

6. **Create storage link:**
   ```bash
   php artisan storage:link
   ```

7. **Use in your controllers:**
   ```php
   // Store avatar locally
   Storage::disk('avatars')->putFile('uploads', $request->file('avatar'));
   
   // Store product image on S3
   Storage::disk('s3-products')->putFile('products', $request->file('image'));
   ```

### Storage Strategy Recommendations

| File Type | Disk | Why |
|-----------|------|-----|
| User Avatars | Local | Fast, small files |
| Product Images | S3 Public | Scalable, CDN-ready |
| User Documents | S3 Private | Secure, durable |
| Temp Files | Local | Fast, auto-cleanup |
| Backups | Glacier/B2 | Cheap long-term |

---

## Best Practices

### Security
- ✅ Always use HTTPS in production
- ✅ Validate file uploads (type, size)
- ✅ Use environment variables for credentials
- ✅ Never commit secrets to version control
- ✅ Implement rate limiting on login endpoints
- ✅ Use temporary URLs for private files

### Performance
- ✅ Optimize images before upload
- ✅ Use CDN for public assets
- ✅ Cache frequently accessed data
- ✅ Use queues for slow operations (email, processing)
- ✅ Enable Laravel optimization commands in production

### Cost Optimization
- ✅ Use appropriate storage classes
- ✅ Implement lifecycle policies
- ✅ Monitor usage and set alerts
- ✅ Clean up unused files regularly
- ✅ Choose right storage provider for your needs

### Development Workflow
- ✅ Test integrations in sandbox/test mode first
- ✅ Use test cards/accounts for payments
- ✅ Keep local and production configs separate
- ✅ Document your customizations
- ✅ Use version control for configuration files

---

## Troubleshooting

### Payment Issues
- Check API keys are correct and active
- Verify webhook URLs are configured
- Test in sandbox mode first
- Check logs for error details

### Social Login Issues
- Verify OAuth credentials
- Check redirect URLs match exactly
- Enable required APIs in provider console
- Clear browser cache if issues persist

### Storage Issues
- Verify S3 credentials and permissions
- Check bucket policies
- Ensure storage:link was run
- Test disk connectivity with tinker

### Getting Help
- Review stub file documentation
- Check Laravel documentation
- Search GitHub issues
- Join Laravel Discord/Slack
- Post on Laracasts forum

---

## Next Steps

After setting up your desired features:

1. **Test thoroughly** in development
2. **Update documentation** for your team
3. **Configure monitoring** and alerts
4. **Set up backups** for critical data
5. **Plan for scaling** as needed

---

## Resources

- [FEATURES_COMPARISON.md](FEATURES_COMPARISON.md) - Detailed feature comparison
- [Laravel Docs](https://laravel.com/docs) - Official documentation
- [Filament Docs](https://filamentphp.com/docs) - Admin panel docs
- [Cashier Docs](https://laravel.com/docs/billing) - Stripe billing
- [Socialite Docs](https://laravel.com/docs/socialite) - Social auth

---

**Need help?** Open an issue on GitHub or consult the stub files for detailed implementation examples.
