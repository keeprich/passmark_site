# Passmark – Hostinger Shared Hosting Deployment
Domain: https://passmark.site

---

## Step 1 – Set PHP version to 8.2+
hPanel → Websites → passmark.site → Manage → PHP Configuration → select PHP 8.2 or 8.3

---

## Step 2 – Create MySQL database
hPanel → Databases → MySQL Databases
- Create a database (e.g. u123456_passmark)
- Create a user with a strong password
- Assign user to database with ALL PRIVILEGES
- Note: DB_DATABASE, DB_USERNAME, DB_PASSWORD for Step 5

---

## Step 3 – Upload the Laravel app
In hPanel → File Manager (or use FTP/Git in hPanel):

Upload the contents of the `app/` folder to a NEW folder called `passmark_app/`
so your Hostinger home looks like:

  /home/u[id]/
    passmark_app/       ← Laravel app (app/, bootstrap/, config/, routes/, etc.)
    public_html/        ← web root

**Do NOT put project files inside public_html directly.**

If using Git (hPanel → Git):
  - Repository: https://github.com/keeprich/passmark_site.git
  - Deploy path: passmark_app

---

## Step 4 – Set up public_html
Copy everything from `passmark_app/public/` into `public_html/`:
- index.php  ← REPLACE with deploy/public_html_index.php (see below)
- .htaccess
- favicon.ico / css / js / images etc.

Then upload `deploy/public_html_index.php` to `public_html/index.php`
(this replaces the default index.php so it points to passmark_app)

---

## Step 5 – Upload .env
Copy `deploy/.env.production` to `passmark_app/.env`
Fill in your real DB credentials from Step 2.
Generate an app key: php artisan key:generate (or run it via SSH below)

---

## Step 6 – Run artisan commands via SSH
hPanel → SSH Access → Enable SSH, then connect:

  ssh u[id]@passmark.site -p 65002

Then:
  cd ~/passmark_app
  composer install --no-dev --optimize-autoloader
  php artisan key:generate
  php artisan migrate --force
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  chmod -R 775 storage bootstrap/cache

---

## Step 7 – Test
Visit https://passmark.site — you should see the home page.
If you get a 500, check passmark_app/storage/logs/laravel.log
