# Hostinger SSH Deployment Guide — Shri Kamadgiri Peetham Sewa Trust

This guide provides step-by-step instructions for deploying this Laravel 13 platform onto **Hostinger Shared / Cloud Hosting** using SSH.

---

## 1. Prerequisites in Hostinger hPanel

Before logging into SSH, configure these 4 settings in your **Hostinger hPanel**:

### 1.1 PHP Configuration (PHP 8.3+)
1. Go to **Advanced** &rarr; **PHP Configuration**.
2. Select **PHP 8.3** (or **PHP 8.4**).
3. Ensure standard extensions are enabled: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `curl`, `fileinfo`.
4. Click **Update**.

### 1.2 Create MySQL Database
1. Go to **Databases** &rarr; **MySQL Databases**.
2. Create a new database:
   - **Database Name**: e.g., `u123456789_kamadgiri`
   - **Username**: e.g., `u123456789_dbuser`
   - **Password**: *Create a strong password*
3. Note down the **Database Name**, **Username**, **Password**, and **Host** (usually `127.0.0.1` or `localhost`).

### 1.3 Enable SSH Access
1. Go to **Advanced** &rarr; **SSH Access**.
2. Ensure SSH Status is **Enabled**.
3. Note your **SSH IP**, **Port** (usually `65002`), and **SSH Username**.

### 1.4 Web Root Directory Configuration
Laravel requires incoming web traffic to be served from the `public/` directory.
- **Recommended (hPanel Custom Web Root):**
  If your plan supports changing the public web root in hPanel, set it to:
  `/home/u123456789/domains/yourdomain.com/public_html/public`
- **Alternative (Symlink / Structure):**
  Keep the Laravel application root at `/home/u123456789/domains/yourdomain.com/laravel` and create a symbolic link from `public_html` to `laravel/public`.

---

## 2. Local Preparation (Before Upload / Push)

Shared hosting environments typically do not have `node` or `npm`. Therefore, always compile your production frontend assets locally before deploying:

```powershell
# 1. Build production CSS/JS bundle locally
npm run build

# 2. Commit all changes to your Git repository
git add .
git commit -m "Prepare production build and Hostinger deployment scripts"
git push origin main
```

---

## 3. SSH Deployment Steps

### Step 3.1: Connect to Hostinger via SSH
Open your terminal (PowerShell, Command Prompt, or Bash) and run:

```bash
ssh -p 65002 u123456789@YOUR_SERVER_IP
```
*(Enter your Hostinger account password when prompted).*

---

### Step 3.2: Clone or Pull Repository
Navigate to your domain directory:

```bash
cd domains/yourdomain.com/

# Option A: If first-time deployment via Git
git clone https://github.com/your-username/your-repo.git public_html
cd public_html

# Option B: If updating existing deployment
cd public_html
git pull origin main
```

---

### Step 3.3: Configure Production `.env`
Create your production `.env` file from the provided template:

```bash
cp .env.production.example .env
nano .env
```

Update the following keys in `.env`:
```ini
APP_NAME="Shri Kamadgiri Peetham Sewa Trust"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u123456789_kamadgiri
DB_USERNAME=u123456789_dbuser
DB_PASSWORD="your-strong-database-password"

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD="your-email-password"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
```
*(Press `Ctrl + O` to save, then `Ctrl + X` to exit nano).*

Generate a unique production encryption key:
```bash
/usr/bin/php8.3 artisan key:generate
```

---

### Step 3.4: Run the Automated Deployment Script
Make the deployment script executable and run it:

```bash
chmod +x deploy-hostinger.sh
./deploy-hostinger.sh
```

What `deploy-hostinger.sh` does automatically:
1. Puts the site in brief maintenance mode.
2. Installs production Composer dependencies (`--no-dev --optimize-autoloader`).
3. Runs database migrations (`php artisan migrate --force`).
4. Generates optimized production caches for config, routes, views, and events.
5. Verifies the public storage symlink (`php artisan storage:link`).
6. Enforces correct permissions (`chmod -R 775 storage bootstrap/cache`).
7. Exits maintenance mode.

---

### Step 3.5: Seed Baseline Data (First Deployment Only)
If this is the initial database setup on Hostinger, run the seeder:

```bash
/usr/bin/php8.3 artisan db:seed --force
```

> [!IMPORTANT]
> Immediately log into `https://yourdomain.com/login` using the default credentials (`admin@kamadgiripeetham.org` / `password123`) and update your administrator email and password in `/admin/users` or `/admin/settings`.

---

## 4. Setup Laravel Scheduled Cron Job (hPanel)

The platform includes background tasks such as auto-archiving past events and processing queued notifications.

1. Go to **Advanced** &rarr; **Cron Jobs** in hPanel.
2. Select **Custom** type.
3. Schedule: `* * * * *` (Every Minute).
4. Command:
   ```bash
   cd /home/u123456789/domains/yourdomain.com/public_html && /usr/bin/php8.3 artisan schedule:run >> /dev/null 2>&1
   ```
5. Click **Save**.

---

## 5. Post-Deployment Verification Checklist

Verify the following URLs on your live domain:

| Verification Target | Expected Result |
| :--- | :--- |
| `https://yourdomain.com/` | Homepage renders with hero video and header |
| `https://yourdomain.com/events/ram-navami-mahotsav-2027` | Event detail renders (HTTP 200) |
| `https://yourdomain.com/feed.xml` | RSS 2.0 XML feed returns |
| `https://yourdomain.com/sitemap.xml` | XML sitemap returns index of all pages |
| `https://yourdomain.com/pooja` & `/pooja/book` | Pooja catalog and Gotra booking form load |
| `https://yourdomain.com/donate` | Donation amount tiers and Razorpay checkout initiate |
| `https://yourdomain.com/login` | Secure login interface with CSRF token |
| `https://yourdomain.com/admin` | Admin dashboard renders with "Export 80G CSV" button |

---

## 6. Zero-Downtime Routine Updates

For future code updates, you only need to run:

```bash
ssh -p 65002 u123456789@YOUR_SERVER_IP "cd domains/yourdomain.com/public_html && ./deploy-hostinger.sh"
```
