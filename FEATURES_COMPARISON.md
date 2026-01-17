# Laravel TALL Stack Starter Kits - Feature Comparison

This document provides a comprehensive comparison of popular Laravel TALL stack starter kits and their features, helping you understand how this starter kit compares and what makes it unique.

## What is TALL Stack?

TALL is an acronym for:
- **T**ailwind CSS - Utility-first CSS framework
- **A**lpine.js - Minimal JavaScript framework for interactive components
- **L**ivewire - Full-stack framework for Laravel that makes building dynamic interfaces simple
- **L**aravel - The PHP framework for web artisans

---

## Popular Laravel Starter Kits Comparison

### Core Starter Kits

| Feature | Laravel Breeze | Laravel Jetstream | This Starter Kit |
|---------|---------------|-------------------|------------------|
| **Philosophy** | Minimal & Simple | Comprehensive | AI-Powered & Modern |
| **Authentication** | Email/Password | Email/Password + 2FA | OTP (Passwordless) |
| **Frontend Stack** | TALL Optional | TALL (Livewire) | TALL (Livewire) |
| **Profile Management** | Basic | Advanced (with photos) | Jetstream-based |
| **Team Management** | ❌ | ✅ | ✅ Enhanced |
| **API Support** | ❌ | ✅ (Sanctum) | ✅ (Sanctum) |
| **Two-Factor Auth** | ❌ | ✅ | OTP-based |
| **Email Verification** | ✅ | ✅ | ✅ |
| **Session Management** | Basic | Advanced | Advanced |
| **Social Login** | ❌ | ❌ | ✅ (Optional) |
| **AI Integration** | ❌ | ❌ | ✅ Claude AI |
| **Payment Integration** | ❌ | ❌ | ✅ (Optional) |
| **Admin Panel** | ❌ | ❌ | ✅ Filament (Optional) |
| **Split Storage** | ❌ | ❌ | ✅ (Optional) |
| **Setup Complexity** | Very Easy | Moderate | Easy (Interactive) |
| **Learning Curve** | Low | Medium | Medium |
| **Best For** | Simple apps | SaaS products | AI-powered SaaS |

### SaaS-Focused Starter Kits

| Feature | Laravel Spark | Wave | SaaSykit | This Starter Kit |
|---------|---------------|------|----------|------------------|
| **Cost** | $99-$199 | Free | $299-$499 | Free |
| **TALL Stack** | Optional | ✅ Native | ✅ Available | ✅ Native |
| **Philosophy** | Official SaaS Kit | Community SaaS | Commercial SaaS | AI-First SaaS |
| **Billing/Payments** | Stripe, Paddle | Stripe, Paddle | Stripe, Paddle, Lemon | Stubs Included |
| **Subscription Management** | ✅ Advanced | ✅ | ✅ Advanced | Via Stubs |
| **Per-Seat Billing** | ✅ | ❌ | ✅ | Via Stubs |
| **Team Management** | ✅ Advanced | ✅ | ✅ Advanced | ✅ Enhanced |
| **Admin Panel** | Basic | Filament/Voyager | Custom | Filament (Optional) |
| **Blog/Content** | ❌ | ✅ Built-in | ✅ | Manual |
| **Metrics/Analytics** | Basic | ❌ | ✅ (MRR, Churn) | Manual |
| **Multi-tenancy** | Via Teams | ✅ | ✅ | Via Jetstream |
| **Email Providers** | Default | ✅ Multiple | ✅ Multiple | Default |
| **Social Login** | ❌ | ✅ | ✅ | ✅ (Optional) |
| **AI Integration** | ❌ | ❌ | ❌ | ✅ Claude |
| **API Support** | ✅ | ✅ | ✅ | ✅ Sanctum |
| **Notifications** | ✅ | ✅ | ✅ Advanced | ✅ |
| **User Impersonation** | ❌ | ✅ | ✅ | Manual |
| **Role Management** | ✅ | ✅ | ✅ | Via Jetstream |
| **Support/Docs** | Official | Community | Commercial | Open Source |
| **Updates** | Annual License | Free Forever | Annual License | Free Forever |
| **Best For** | Laravel Devs | Open Source Fans | Feature-Rich SaaS | AI-Powered Apps |

**Key Differences:**
- **Laravel Spark**: Official Laravel product, premium pricing, deep Laravel integration
- **Wave**: Free and open-source, community-driven, includes blog/changelog
- **SaaSykit**: Commercial product with advanced metrics and multi-tenancy features
- **This Starter Kit**: Free, AI-focused, modular stub-based approach

### Rapid Development Kits

| Feature | LaraFast | Artiplate | Streamline | This Starter Kit |
|---------|----------|-----------|------------|------------------|
| **Cost** | Paid | Paid | Paid | Free |
| **Focus** | Speed | Startups | Quick Deploy | Flexibility |
| **Billing** | ✅ Stripe | ✅ | ✅ Lemon Squeezy | Stubs (3 options) |
| **Authentication** | ✅ | ✅ | ✅ Social | ✅ OTP + Social |
| **Admin Panel** | ✅ | ✅ | ✅ | Filament (Optional) |
| **Blog/SEO** | ✅ | ✅ | ✅ | Manual |
| **Themes** | ✅ | ✅ | ✅ Customizable | Tailwind Base |
| **AI Features** | ❌ | ❌ | ❌ | ✅ Claude |
| **Best For** | MVPs | Early Stage | Quick Launch | Custom Solutions |

### Admin Panel Options

| Feature | Filament | Nova | Voyager | This Kit + Filament |
|---------|----------|------|---------|---------------------|
| **Cost** | Free (v4) | Paid (~$99/site) | Free | Free |
| **TALL Stack** | ✅ Native | ❌ Vue-based | ❌ Traditional | ✅ Native |
| **Table Builder** | ✅ Advanced | ✅ | ✅ Basic | ✅ |
| **Form Builder** | ✅ Advanced | ✅ | ✅ Basic | ✅ |
| **Notifications** | ✅ | ✅ | ✅ | ✅ |
| **Media Library** | ✅ | ✅ | ✅ | ✅ |
| **Multi-tenancy** | ✅ Plugin | ✅ | ❌ | ✅ Plugin |
| **Learning Curve** | Medium | Medium | Low | Medium |
| **Customization** | High | High | Medium | High |
| **Modern UI** | ✅ Excellent | ✅ Good | ⚠️ Dated | ✅ Excellent |

---

## Payment Gateway Comparison

| Feature | Stripe (Cashier) | PayPal | Lemon Squeezy | Paddle |
|---------|------------------|--------|---------------|--------|
| **Official Laravel Package** | ✅ Yes | ❌ No | ✅ Yes | ✅ Yes |
| **One-time Payments** | ✅ | ✅ | ✅ | ✅ |
| **Subscriptions** | ✅ Advanced | ✅ Basic | ✅ Advanced | ✅ Advanced |
| **Webhooks** | ✅ | ✅ | ✅ | ✅ |
| **Invoice PDF** | ✅ | Limited | Roadmap | ✅ |
| **Tax Handling** | ✅ | Limited | ✅ Automatic | ✅ Automatic |
| **Payment Methods** | Cards, Wallets | PayPal, Cards | Multiple | Multiple |
| **Global Support** | Excellent | Excellent | Growing | Excellent |
| **Merchant of Record** | ❌ | ❌ | ✅ | ✅ |
| **Setup Complexity** | Low | Medium | Very Low | Low |
| **Fees** | 2.9% + 30¢ | 2.9% + 30¢ | 2.9% + 30¢ | 5% + 50¢ |
| **Best For** | Most apps | PayPal users | SaaS products | International |

**Package Names:**
- Stripe: `laravel/cashier` (official)
- PayPal: Custom integration or community packages
- Lemon Squeezy: `lemonsqueezy/laravel` (official)
- Paddle: `laravel/cashier-paddle` (official)

---

## Social Authentication Comparison

| Provider | Native Socialite Support | Users Worldwide | Use Case |
|----------|-------------------------|-----------------|----------|
| **Google** | ✅ | 4.3B+ | Universal login |
| **Facebook** | ✅ | 2.9B+ | Social networks |
| **GitHub** | ✅ | 100M+ | Developer tools |
| **Twitter/X** | ✅ | 550M+ | Social apps |
| **LinkedIn** | Via Provider | 930M+ | Professional apps |
| **Apple** | Via Provider | 2B+ | iOS apps |
| **Microsoft** | Via Provider | 1.4B+ | Enterprise apps |

**Setup Requirements:**
- Package: `laravel/socialite`
- Configuration: OAuth credentials from each provider
- Database: Additional columns for social IDs
- Routes: Redirect and callback endpoints

---

## File Storage Options

| Storage Type | Use Case | Cost | Speed | Best For |
|--------------|----------|------|-------|----------|
| **Local** | Development, temp files | Free | Fastest | Dev environment |
| **S3** | Production assets | Pay per GB | Fast | Scalable storage |
| **DigitalOcean Spaces** | Production assets | ~$5/250GB | Fast | Cost-effective |
| **Cloudflare R2** | Public assets | Free egress | Fast | High traffic |
| **Backblaze B2** | Backups, archives | ~$5/TB | Moderate | Long-term storage |

**Split Storage Strategy (Recommended):**
- **Local/Public**: User avatars, small assets (< 1MB)
- **S3/Cloud**: Product images, videos, large files
- **Separate Bucket**: User-generated content (UGC)
- **CDN**: Serve static assets globally

---

## Detailed Starter Kit Descriptions

### 1. Laravel Spark (Premium Official)

**Price**: $99/project or $199/unlimited projects (annual updates)  
**Maintained by**: Taylor Otwell (Laravel creator)

**Key Features**:
- **Subscription Billing**: Native Stripe and Paddle integration via Laravel Cashier
- **Billing Portal**: Isolated customer portal for subscription management
- **Per-Seat Pricing**: Dynamic user/team-based billing
- **Team Management**: Full organization support with role-based permissions
- **Invoicing**: PDF invoices with automatic generation
- **Trial Support**: Easy trial period configuration
- **Multi-Gateway**: Switch between Stripe and Paddle with minimal changes

**Best For**: SaaS founders and teams who want official Laravel support and prefer not to build billing infrastructure from scratch.

**Pros**: Deep Laravel integration, maintained by core team, comprehensive billing features  
**Cons**: Premium pricing, requires annual license renewal for updates

### 2. Wave (Free Open Source)

**Price**: Free & Open Source  
**Repository**: GitHub - thedevdojo/wave

**Key Features**:
- **Complete SaaS Suite**: Authentication, subscriptions, roles, notifications
- **Built-in Blog**: Content management with SEO tools
- **Admin Dashboard**: Filament or Voyager integration
- **User Impersonation**: Support and debugging capability
- **Changelog Module**: Keep users informed of updates
- **Username Login**: Flexible authentication options
- **Multi-provider Billing**: Stripe and Paddle support
- **API Ready**: Built-in API for integrations

**Best For**: Developers who want a free, comprehensive SaaS foundation with active community support.

**Pros**: Free forever, active community, feature-rich  
**Cons**: Community support only, requires more customization for unique needs

### 3. SaaSykit (Premium Commercial)

**Price**: $299-$499 (with annual updates)  
**Focus**: Enterprise-grade SaaS features

**Key Features**:
- **Advanced Metrics**: MRR (Monthly Recurring Revenue), Churn Rate, ARPU
- **Multi-Payment**: Stripe, Paddle, and Lemon Squeezy support
- **Multi-Tenancy**: Full tenant isolation and management
- **Multi-Email Providers**: Flexible email service integration
- **Built-in Blog**: SEO-optimized content platform
- **Advanced Analytics**: Business intelligence dashboards
- **User Dashboard**: Comprehensive user management
- **Admin Dashboard**: Full-featured administration panel

**Best For**: Teams building feature-rich SaaS products who need advanced metrics and are willing to invest in a premium solution.

**Pros**: Enterprise features, advanced analytics, comprehensive documentation  
**Cons**: Higher cost, commercial license required

### 4. LaraFast (Premium MVP Tool)

**Price**: Paid (various tiers)  
**Focus**: Rapid MVP development

**Key Features**:
- **Quick Setup**: Designed for speed and rapid prototyping
- **Stripe Integration**: Built-in subscription billing
- **Core Functions**: Essential web application features
- **Scalable Foundation**: Production-ready architecture
- **Theme System**: Pre-built themes for quick styling

**Best For**: Entrepreneurs and developers who need to launch MVPs quickly.

**Pros**: Speed-focused, good for prototypes  
**Cons**: Commercial license, may require customization for complex needs

### 5. Artiplate (Premium Startup Kit)

**Price**: Paid  
**Focus**: Startup and early-stage projects

**Key Features**:
- **Authentication System**: Complete user management
- **Theme Engine**: Customizable design system
- **Payment Integration**: Built-in payment processing
- **Notification System**: Real-time notifications
- **Startup Optimized**: Features for MVP development

**Best For**: Startups in early stages looking for rapid development tools.

**Pros**: Startup-focused features, rapid setup  
**Cons**: Commercial pricing, limited to startup-focused features

### 6. Streamline (Premium Quick Deploy)

**Price**: Paid  
**Focus**: Fast deployment with modern features

**Key Features**:
- **Social Authentication**: Built-in OAuth providers
- **Role & Permissions**: Comprehensive access control
- **Lemon Squeezy Integration**: Modern payment solution
- **Blog Platform**: SEO and content marketing
- **Customizable UI**: Flexible design system
- **Quick Deployment**: Production-ready from start

**Best For**: Teams prioritizing quick deployment with modern payment solutions.

**Pros**: Modern stack, Lemon Squeezy integration, quick setup  
**Cons**: Commercial license, premium pricing

### 7. Laravel Fortify (Free Backend Only)

**Price**: Free  
**Official**: Laravel Package

**Key Features**:
- **Backend Authentication**: No frontend included
- **Headless Design**: Perfect for APIs and SPAs
- **2FA Support**: Two-factor authentication
- **Email Verification**: Built-in email workflows
- **Password Reset**: Secure reset functionality

**Best For**: Teams building custom UIs, mobile apps, or SPAs that need backend authentication only.

**Pros**: Official Laravel package, flexible, no UI constraints  
**Cons**: No frontend, requires custom UI implementation

### 8. Ship Fast Labs Kits (Premium Modern)

**Price**: Paid  
**Focus**: Modern development workflow

**Key Features**:
- **Multiple Stacks**: React, Vue, or Livewire
- **Code Quality Tools**: Pint, PHPStan integration
- **Advanced Auth**: Modern authentication patterns
- **Deployment Ready**: CI/CD configurations included
- **Best Practices**: Opinionated structure

**Best For**: Teams prioritizing code quality and modern development practices.

**Pros**: High code quality standards, modern tooling  
**Cons**: Premium pricing, opinionated structure

---

## This Starter Kit: Unique Features

### 🎯 What Sets This Kit Apart

1. **OTP Authentication (Passwordless)**
   - Modern, password-free authentication
   - Email-based one-time codes
   - Prefilled codes for local development
   - More secure than traditional passwords

2. **AI Integration Ready**
   - Claude AI API pre-configured
   - OAuth setup for AI features
   - Ready for AI-powered applications
   - Future-proof architecture

3. **Interactive Installer**
   - Guided setup wizard
   - Database creation automation
   - Laravel Herd integration
   - Environment detection

4. **Enhanced Team Features**
   - Beautiful invitation emails
   - Improved team management
   - Based on Jetstream teams
   - Production-ready

5. **Optional Feature Integration**
   - Filament 4 admin panel setup
   - Multiple payment gateway options
   - Social login (Socialite) support
   - Split filesystem configuration
   - All through interactive installer

---

## Feature Matrix: This Starter Kit

### ✅ Included Out of the Box

- Laravel 11+ (latest stable)
- Jetstream with Livewire (TALL stack)
- OTP authentication system
- Team management (enhanced)
- MySQL & SQLite support
- Claude AI integration
- Laravel Herd mail configuration
- Interactive installation wizard
- Comprehensive documentation
- Security best practices

### 🔧 Optional via Installer (Planned)

- **Filament 4**: Modern admin panel
- **Payment Gateways**: Stripe, PayPal, Lemon Squeezy
- **Social Login**: Google, Facebook, GitHub, etc.
- **Split Storage**: Local + S3/Spaces configuration
- **Additional Auth**: SMS OTP, WhatsApp OTP
- **Docker**: Containerized setup

### 📝 Manual Setup (Documented)

- Advanced API features
- Custom payment flows
- Additional social providers
- Multi-tenancy configuration
- Advanced caching strategies
- Queue workers setup

---

## Installation Comparison

### Laravel Breeze
```bash
composer require laravel/breeze
php artisan breeze:install livewire
```
**Time**: ~2 minutes | **Setup**: Manual

### Laravel Jetstream
```bash
composer require laravel/jetstream
php artisan jetstream:install livewire --teams
```
**Time**: ~5 minutes | **Setup**: Semi-automated

### This Starter Kit
```bash
curl -s https://raw.githubusercontent.com/andreaskviby/laravel-jetstream-tallstack-ai-powered/main/install.sh | bash -s -- my-project
```
**Time**: ~3 minutes | **Setup**: Fully interactive

---

## When to Choose Each Starter Kit

### Quick Decision Matrix

Choose **Laravel Breeze** if:
- ✅ You need simple authentication only
- ✅ You want minimal overhead and full control
- ✅ You're learning Laravel
- ❌ Don't need: Teams, advanced auth, or billing

Choose **Laravel Jetstream** if:
- ✅ You need teams and 2FA
- ✅ You want official Laravel scaffolding
- ✅ You need API tokens (Sanctum)
- ❌ Don't need: Payment integration or admin panel

Choose **Laravel Spark** if:
- ✅ You need enterprise subscription billing
- ✅ You want official Laravel support
- ✅ You can invest $99-$199
- ✅ You need per-seat billing
- ❌ Don't need: Free solution or AI features

Choose **Wave** if:
- ✅ You want a free, comprehensive SaaS kit
- ✅ You need a built-in blog and changelog
- ✅ You prefer open-source and community support
- ✅ You want user impersonation features
- ❌ Don't need: Commercial support or AI integration

Choose **SaaSykit** if:
- ✅ You need advanced metrics (MRR, Churn, ARPU)
- ✅ You need multi-tenancy features
- ✅ You can invest $299-$499
- ✅ You want multiple payment providers
- ❌ Don't need: Free solution or minimal features

Choose **LaraFast/Artiplate/Streamline** if:
- ✅ You need to launch an MVP extremely quickly
- ✅ You prefer pre-built themes and components
- ✅ You can invest in a commercial license
- ❌ Don't need: AI features or extensive customization

Choose **Laravel Fortify** if:
- ✅ You're building a custom UI or mobile app
- ✅ You only need backend authentication
- ✅ You want official Laravel package
- ❌ Don't need: Pre-built frontend

Choose **This Starter Kit** if:
- ✅ You need AI-powered features (Claude integration)
- ✅ You want passwordless OTP authentication
- ✅ You need optional SaaS features (modular approach)
- ✅ You want free and open-source
- ✅ You need Filament 4 admin panel option
- ✅ You want comprehensive integration stubs
- ❌ Don't need: Traditional password auth or commercial support

---

## Feature Comparison Summary

| Priority | Budget | Need | Recommended Kit |
|----------|--------|------|-----------------|
| Speed | Free | Basic auth | **Laravel Breeze** |
| Speed | Free | Teams + 2FA | **Laravel Jetstream** |
| Speed | Paid | MVP launch | **LaraFast/Streamline** |
| SaaS | Free | Complete suite | **Wave** |
| SaaS | Paid | Advanced features | **SaaSykit** |
| SaaS | Paid | Official support | **Laravel Spark** |
| SaaS | Free | AI-powered | **This Starter Kit** |
| Custom | Free | API/Mobile | **Laravel Fortify** |
| Admin | Free | Dashboard focus | **Filament Starter** |

---

## When to Choose This Starter Kit

### ✅ Best Choice When You Need:

- **AI-Powered Applications**: Claude AI integration ready
- **Modern Authentication**: Passwordless OTP system
- **SaaS Features**: Teams, subscriptions, payments (via stubs)
- **Admin Panel**: Filament 4 option included
- **Rapid Development**: Interactive setup saves time
- **Production Ready**: Security and best practices built-in
- **Extensibility**: Easy to add features later
- **Free & Open Source**: No licensing costs
- **Modular Approach**: Use only what you need

### ⚠️ Consider Alternatives When:

- **Traditional Auth Required**: Wave or Spark have password auth
- **Minimal Setup**: Breeze is lighter if you need basics only
- **Commercial Support**: Spark or SaaSykit offer paid support
- **Built-in Metrics**: SaaSykit has advanced analytics
- **Learning Laravel**: Breeze might be simpler to understand
- **Pre-built Blog**: Wave has integrated blog/changelog

---

## Migration Guide

### From Laravel Breeze

1. Already have Breeze installed? This kit adds:
   - OTP authentication (replace password auth)
   - Team management features
   - AI integration options
   - Payment gateway setup

2. Migration steps:
   - Review OTP authentication implementation
   - Install Jetstream for teams: `composer require laravel/jetstream`
   - Copy relevant stubs from this kit
   - Update routes and views

### From Laravel Jetstream

1. Already have Jetstream? This kit enhances:
   - OTP instead of password auth
   - Enhanced team invitation emails
   - AI integration setup
   - Optional Filament admin
   - Payment gateway stubs

2. Migration steps:
   - Copy OTP authentication stubs
   - Update invitation email templates
   - Add payment gateway configuration
   - Configure Claude AI (optional)

---

## Feature Roadmap

### Version 1.0 (Current)
- ✅ OTP authentication
- ✅ Team management
- ✅ Claude AI integration
- ✅ Interactive installer
- ✅ MySQL/SQLite support

### Version 1.1 (Planned)
- 🔄 Filament 4 integration
- 🔄 Payment gateway stubs (Stripe, PayPal, Lemon Squeezy)
- 🔄 Socialite social login
- 🔄 Split filesystem configuration

### Version 1.2 (Future)
- 📅 SMS/WhatsApp OTP
- 📅 Docker support
- 📅 CI/CD workflows
- 📅 Multi-language support
- 📅 PostgreSQL support

### Version 2.0 (Vision)
- 🔮 Advanced AI features
- 🔮 Multi-tenancy support
- 🔮 Mobile app scaffolding
- 🔮 GraphQL API option

---

## Community & Commercial Starter Kits

Beyond official kits, several community and commercial projects offer comprehensive solutions:

| Project | Type | Focus | Notable Features | Price |
|---------|------|-------|------------------|-------|
| **Laravel Spark** | Official | SaaS Billing | Per-seat, teams, billing portal | $99-$199 |
| **Wave** | Open Source | Complete SaaS | Blog, metrics, free | Free |
| **SaaSykit** | Commercial | Feature-Rich | Advanced metrics, multi-tenancy | $299-$499 |
| **LaraFast** | Commercial | Speed | Quick MVP launch | Paid |
| **Artiplate** | Commercial | Startups | MVP-focused | Paid |
| **Streamline** | Commercial | Quick Deploy | Modern stack | Paid |
| **Ship Fast Labs** | Commercial | Quality | Code standards, CI/CD | Paid |
| **Filament Starter** | Open Source | Admin-first | Dashboard-focused | Free |

**This Starter Kit** positions itself as a **free, AI-ready, modular solution** that provides:
- Core features out of the box (OTP auth, teams, AI integration)
- Optional features via comprehensive stubs (payments, admin, social login, storage)
- No licensing costs or restrictions
- Flexibility to use only what you need

---

## Conclusion

The Laravel ecosystem offers numerous starter kits for different needs and budgets:

### Free & Open Source Options
- **Laravel Breeze**: Minimal authentication
- **Laravel Jetstream**: Teams and 2FA
- **Laravel Fortify**: Backend-only auth
- **Wave**: Complete SaaS suite
- **This Starter Kit**: AI-powered with modular features

### Premium Commercial Options
- **Laravel Spark**: Official SaaS billing ($99-$199)
- **SaaSykit**: Enterprise features ($299-$499)
- **LaraFast/Artiplate/Streamline**: Rapid MVP tools (Paid)

### Unique Positioning

This starter kit provides a **modern, production-ready foundation** for Laravel applications that need:

- **Advanced authentication** (OTP/passwordless) - Unique among Laravel kits
- **Team collaboration** features - Based on Jetstream
- **AI integration** capabilities - Claude AI ready
- **Optional SaaS features** - Via comprehensive stubs, not forced dependencies
- **Split storage** for scalable file management
- **Free & Open Source** - No licensing restrictions
- **Modular approach** - Use only what you need

It builds on the solid foundation of **Laravel Jetstream** while adding modern conveniences and optional integrations that most SaaS applications eventually need, without the premium price tag.

### Selection Guide by Use Case

| Use Case | Best Choice | Why |
|----------|-------------|-----|
| Learning Laravel | Breeze | Simplest, most transparent |
| Corporate App | Jetstream | Official, well-documented |
| SaaS Startup (Free) | Wave or This Kit | Comprehensive, no cost |
| SaaS Startup (Paid) | Spark or SaaSykit | Advanced features, support |
| AI-Powered App | This Starter Kit | Only kit with AI integration |
| Quick MVP | LaraFast/Streamline | Pre-built everything |
| Custom UI | Fortify | Backend-only, bring your UI |
| Admin Focus | Filament Starter | Dashboard-first approach |

---

## Resources

### Official Documentation
- [Laravel Official Docs](https://laravel.com/docs)
- [Jetstream Docs](https://jetstream.laravel.com)
- [Livewire Docs](https://livewire.laravel.com)
- [Filament Docs](https://filamentphp.com)
- [Laravel Cashier](https://laravel.com/docs/billing)
- [Laravel Socialite](https://laravel.com/docs/socialite)

### Starter Kit Resources
- [Laravel Spark](https://spark.laravel.com)
- [Wave SaaS Kit](https://devdojo.com/wave)
- [SaaSykit](https://saasykit.com)
- [This Project Repository](https://github.com/andreaskviby/laravel-jetstream-tallstack-ai-powered)

### Comparison Articles
- [10 Best Laravel Starter Kits for 2025](https://saasykit.com/blog/10-best-laravel-starter-kits-for-2025)
- [Best Laravel Starter Kits](https://1v0.net/blog/best-laravel-starter-kits-breeze-jetstream-spark-nova-22-more/)
- [Laravel Boilerplates](https://www.buildkits.dev/categories/laravel)

---

**Last Updated**: January 2026  
**Laravel Version**: 11.x+  
**Filament Version**: 4.x  
**Maintained**: Yes
