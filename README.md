# GoalQuest — Laravel build

This folder is **not** a full Laravel install — it's the app-specific code (models,
migrations, controllers, views, the reminder command) written in Laravel's standard
folder structure. Drop it into a fresh Laravel project and it'll work with your
existing Herd + XAMPP setup.

## 1. Create a fresh Laravel project with auth

```bash
composer create-project laravel/laravel goalquest
cd goalquest
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

Breeze gives you working login/register pages and a `User` model for free — no need
to build auth from scratch.

## 2. Copy these files into your new project

Copy this repo's folders straight over the matching folders in `goalquest/`,
overwriting where prompted:

```
app/Models/Goal.php
app/Models/Checkin.php
app/Models/Mood.php
app/Models/Reflection.php
app/Http/Controllers/DashboardController.php
app/Http/Controllers/GoalController.php
app/Http/Controllers/ReflectionController.php
app/Console/Commands/SendGoalReminders.php
database/migrations/2025_01_01_000001_create_goals_table.php
database/migrations/2025_01_01_000002_create_checkins_table.php
database/migrations/2025_01_01_000003_create_moods_table.php
database/migrations/2025_01_01_000004_create_reflections_table.php
resources/views/dashboard.blade.php
resources/views/goals/_card.blade.php
resources/views/components/layouts/app.blade.php
```

For `routes/web.php` and `routes/console.php`: **don't overwrite** — Breeze already
put routes in there. Instead open the ones in this repo and paste the route
definitions into your existing files (inside the `auth` middleware group Breeze
already created for `web.php`; the scheduler line goes anywhere in `console.php`).

## 3. Add the `goals()` relationship to `app/Models/User.php`

```php
public function goals(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(\App\Models\Goal::class);
}
```

## 4. Database setup (XAMPP / MySQL, matching your earlier setup)

In `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=goalquest
DB_USERNAME=root
DB_PASSWORD=
```

Create the `goalquest` database in phpMyAdmin, then run:

```bash
php artisan migrate
```

## 5. Run it locally

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000`, register an account, and you should land on the
dashboard with the same look and features as the demo you already saw: goals with
progress rings, categories, streaks, mood check-ins with philosopher quotes, a
Finance goal type with dollar targets, rewards, and a reflection journal.

## 6. Reminders (email) — local testing

1. Sign up for a free [Mailtrap](https://mailtrap.io) account (or use any free SMTP
   testing inbox) and put the credentials in `.env`:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_mailtrap_username
   MAIL_PASSWORD=your_mailtrap_password
   MAIL_FROM_ADDRESS=quest@goalquest.test
   ```
2. Test the command directly:
   ```bash
   php artisan goals:remind
   ```
3. For it to run automatically every day, Laravel's scheduler needs *something* to
   call it once a minute. Locally you can run:
   ```bash
   php artisan schedule:work
   ```
   and leave that running in a terminal while you develop.

## 7. Deploying it live (free options, following on from what we discussed)

**Render.com (recommended — supports Laravel + free Postgres/MySQL, easiest SSL)**
1. Push this project to a GitHub repo.
2. In Render, create a new **Web Service** from that repo, using a PHP/Laravel
   buildpack (Render has an official Laravel guide — search "Render deploy Laravel"
   for the current Dockerfile/buildpack steps, since these details change).
3. Add a free Render Postgres or a MySQL add-on, and set the `.env` equivalents as
   Render environment variables (`DB_CONNECTION=pgsql` if you use Postgres).
4. Add a **Cron Job** service in Render running `php artisan schedule:run` once a
   minute — this replaces `schedule:work` in production, and is what actually makes
   `goals:remind` fire on its own.
5. Render issues free SSL automatically, so your live URL is `https://...` out of
   the box.

**InfinityFree (free PHP hosting, more manual)**
1. Push to GitHub, then upload the files via FTP (InfinityFree doesn't run
   `composer install` for you — run `composer install --no-dev` locally first and
   upload the `vendor/` folder too).
2. Create a MySQL database in their control panel and update `.env` with the
   credentials they give you.
3. Run migrations via SSH if your plan includes it, or import an exported SQL file
   through phpMyAdmin.
4. Reminders are the tricky part here: InfinityFree's free tier doesn't reliably
   support cron jobs, so the daily reminder email may not fire — this is the main
   reason Render is the easier option for this particular app.
5. SSL on free InfinityFree plans is limited — check their current docs before
   relying on it for anything beyond a test deploy.

## 8. Security basics (carried over from what we covered earlier)

- Keep `APP_DEBUG=false` and never commit `.env` — it's already in `.gitignore`.
- Breeze/Laravel handle CSRF tokens and password hashing for you automatically.
- Run `composer update` occasionally to keep dependencies patched.
- Once deployed, confirm the live URL loads over `https://`, not `http://`.

---

If anything in this build doesn't line up with the demo (a button, a wording, a
feature), that's expected — this is hand-written source, not machine-generated
from the prototype, so treat it as a strong starting point to adjust rather than a
perfect 1:1 port.
