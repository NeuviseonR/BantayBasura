# Purok Leader dashboard and login setup

## What was changed
- Login redirects users with `role=leader` (and `admin`) to the Purok Leader dashboard; all other roles go to the resident dashboard.
- Purok Leader routes are protected by both `auth` and the `leader` role middleware. The middleware allows only `leader` and `admin`.
- Role middleware is registered in `bootstrap/app.php`.
- Cleanup Activities CRUD and volunteer application review now consistently recognize `leader` rather than older `official`/`purok_leader` role values.
- Cleanup-drive listing is scoped to the leader's barangay. Creating a drive requires a barangay assignment. Leaders can edit/regenerate codes/verify photos/manage details only for drives they organized in their own barangay; admins can manage all.
- Volunteer application review and private ID document access are barangay-scoped for leaders; leaders without a barangay assignment are denied. Private ID files are downloaded through Laravel's local storage disk.
- Purok sidebar's Volunteers item is labeled Volunteer Applications and links to the actual review page.
- Purok layout and dashboard use the authenticated account name and zone rather than hard-coded sample leader identity.

## Install into an existing project
1. Back up your project first.
2. Extract/merge the changed files into the Laravel project root. Keep your existing `.env`.
3. Make sure these files were replaced/added:
   - `routes/web.php`
   - `app/Http/Controllers/AuthController.php`
   - `app/Http/Controllers/VolunteerController.php`
   - `app/Http/Controllers/CleanupDriveLeaderController.php`
   - `app/Http/Middleware/EnsureLeaderRole.php` (new)
   - `bootstrap/app.php`
   - `resources/views/layouts/purok.blade.php`
   - `resources/views/partials/purok-sidebar.blade.php`
   - `resources/views/purok-dash.blade.php`
4. Run from the project root:
   ```bash
   php artisan optimize:clear
   php artisan route:list
   ```
   Confirm the Purok routes show the `auth` and `leader` middleware (use `php artisan route:list -v` if needed).
5. Ensure migrations are up to date:
   ```bash
   php artisan migrate
   ```
   No new migration was added for the role: the existing role column is a string and `leader` fits.

## Assign a leader account safely
1. Create the account through normal registration so the password is hashed. Do not manually store a plaintext password.
2. In phpMyAdmin, find the account's correct `barangay_id` from the `barangays` table.
3. Assign the role and barangay using a parameterized/admin UI or SQL like:
   ```sql
   UPDATE users
   SET role = 'leader', barangay_id = 1
   WHERE email = 'leader@example.com';
   ```
   Replace `1` with the real barangay ID and the email with the intended account. Verify the query updates exactly one row.
4. Sign in with that account's existing email/password. It should redirect to `/purok-dash`.

## Security notes
- Registration always creates `resident`; the role is never accepted from the registration form.
- Do not create leaders by letting users select the `leader` role in public registration.
- Leaders need a non-null `barangay_id` for cleanup-drive creation and volunteer application review.
- Only `leader` and `admin` may access Purok routes. Residents/volunteers should receive HTTP 403.
- Admin accounts can access leader tools across barangays; leaders are limited to their barangay and their own created drives for management actions.

## Known prototype pages
The Purok Reports, Overview, Messages, and Profile pages are still static prototype views with sample UI/data. They are protected by leader middleware, but are not fully connected to live database data in this patch. The Cleanup Activities and Volunteer Applications pages are the live controller-backed pages.
