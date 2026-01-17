# Security Policy

## Supported Versions

We release patches for security vulnerabilities. Which versions are eligible for receiving such patches depends on the CVSS v3.0 Rating:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## Reporting a Vulnerability

We take the security of our starter kit seriously. If you believe you have found a security vulnerability, please report it to us as described below.

### Please Do Not

- **Do not** open a public GitHub issue for security vulnerabilities
- **Do not** disclose the vulnerability publicly until it has been addressed

### How to Report

**Please report security vulnerabilities by emailing:** security@example.com

Include the following information in your report:

1. **Description of the vulnerability**
   - What kind of vulnerability is it?
   - What is the impact?

2. **Steps to reproduce**
   - Detailed steps to reproduce the vulnerability
   - Include any relevant code snippets or configurations

3. **Affected versions**
   - Which versions are affected?
   - Have you tested multiple versions?

4. **Possible solution**
   - If you have ideas about how to fix it, please share

5. **Your contact information**
   - How can we reach you for follow-up questions?

### What to Expect

- **Acknowledgment**: We will acknowledge receipt of your vulnerability report within 48 hours
- **Updates**: We will send you regular updates about our progress
- **Timeline**: We aim to resolve critical vulnerabilities within 7 days
- **Credit**: We will credit you in the fix (unless you prefer to remain anonymous)

## Security Best Practices

When using this starter kit:

### In Production

1. **Environment Variables**
   - Never commit `.env` files to version control
   - Use strong, unique values for all secrets
   - Rotate API keys regularly

2. **Database**
   - Use strong database passwords
   - Restrict database access to necessary hosts only
   - Keep database software up to date

3. **OTP Configuration**
   - Disable `OTP_PREFILL_LOCAL` in production
   - Use secure email delivery methods
   - Set appropriate OTP expiration times

4. **Dependencies**
   - Keep all dependencies up to date
   - Run `composer audit` regularly
   - Review security advisories for Laravel and related packages

5. **Web Server**
   - Use HTTPS in production
   - Configure proper security headers
   - Keep web server software updated

### In Development

1. **Local Environment**
   - Use `.env.local` for local overrides
   - Never use production credentials in development
   - Test with OTP prefill enabled for convenience

2. **API Keys**
   - Use test/sandbox API keys for development
   - Never share development environment files

## Security Features

This starter kit includes:

- ✅ **OTP Authentication**: More secure than passwords
- ✅ **CSRF Protection**: Built into Laravel
- ✅ **SQL Injection Prevention**: Using Eloquent ORM
- ✅ **XSS Protection**: Blade templating auto-escapes
- ✅ **Password Hashing**: BCrypt by default (when used)
- ✅ **Secure Session Handling**: Laravel session management

## Known Security Considerations

### OTP Prefill (Development Only)

The OTP prefill feature (`OTP_PREFILL_LOCAL=true`) is designed for development convenience and should **NEVER** be enabled in production. The installer automatically detects the environment and configures this appropriately.

### Database Credentials

When using the installer, database credentials are written to the `.env` file. Ensure this file:
- Is listed in `.gitignore` (already done by default)
- Has appropriate file permissions (600 recommended)
- Is never committed to version control

## Security Updates

We will announce security updates through:
- GitHub Security Advisories
- Release notes in CHANGELOG.md
- Email notifications (for critical vulnerabilities)

## Disclosure Policy

We follow a coordinated disclosure policy:

1. Security issue is reported privately
2. We confirm the issue and determine severity
3. We develop and test a fix
4. We release a security patch
5. Public disclosure after patch is available

Thank you for helping keep this project and its users safe!
