# Shri Kamadgiri Peetham Sewa Trust — Digital Platform

A production-oriented Laravel website and digital service platform for **Shri Kamadgiri Peetham Sewa Trust, Chitrakoot Dham**.

---

## 🚀 Running Locally for Testing & Development

The local server is currently **active and running** at:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

### Quick Start (1-Click)
- **Windows / Batch**: Double-click `serve.bat` or run `.\serve.bat` in command prompt.
- **PowerShell**: Run `.\serve.ps1` in PowerShell.

Both scripts automatically detect Laragon's PHP (`C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe`) or system PHP and start the Artisan server immediately.

---

### Manual Terminal Commands (PowerShell)

If `php` is not yet recognized globally in your current PowerShell window, run:

```powershell
# 1. Add Laragon PHP & Composer to your current session PATH:
$env:PATH += ";C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64;C:\laragon\bin\composer"

# 2. Run the server:
php artisan serve --host=127.0.0.1 --port=8000
```

Or execute artisan directly using Laragon's PHP path:
```powershell
& "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" artisan serve --host=127.0.0.1 --port=8000
```

---

## 🔐 Admin Credentials (Default Seeder)

- **Admin Login URL**: `http://127.0.0.1:8000/login`
- **Email**: `admin@kamadgiripeetham.org`
- **Password**: `password123`
- **Role**: `super_admin` (Full CMS access: Blogs, Causes, Gallery, Settings, SMTP, Users, Receipts)

---

## 🧪 Running Automated Tests

Run the test suite at any time:
```powershell
& "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" artisan test
```

---

## 🌐 Main Website Modules & URLs

- **Homepage**: `http://127.0.0.1:8000/`
- **Seva Projects**: `http://127.0.0.1:8000/seva`
- **Chitrakoot Guide**: `http://127.0.0.1:8000/chitrakoot`
- **Events**: `http://127.0.0.1:8000/events`
- **Blog & News**: `http://127.0.0.1:8000/blog`
- **Gallery Magazine**: `http://127.0.0.1:8000/gallery`
- **Online Donations**: `http://127.0.0.1:8000/donate`
- **Member Registration (Multi-step OTP)**: `http://127.0.0.1:8000/register`
- **Admin Dashboard**: `http://127.0.0.1:8000/admin`


Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
