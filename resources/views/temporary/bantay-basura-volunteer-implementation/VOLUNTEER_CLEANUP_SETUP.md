# Volunteer cleanup-drive feature setup

This feature adds resident volunteer applications, approval, volunteer-only cleanup-drive registration, activity calendar/list, photo or QR attendance verification, event galleries, points/tier ledger, and automatic missed-verification penalties.

## Install/update

1. Back up your current project and database first.
2. Copy the files from this package into your Laravel project, preserving the folder paths.
3. Run:
   ```bash
   composer install
   npm install
   npm run build
   php artisan migrate
   php artisan storage:link
   ```
4. Make sure the `storage` and `bootstrap/cache` directories are writable by the PHP process.
5. Add a scheduler cron job (on the server) so Laravel can run its scheduled tasks every minute:
   ```cron
   * * * * * cd /path/to/my-laravel-app && php artisan schedule:run >> /dev/null 2>&1
   ```
   On Windows development, keep `php artisan schedule:work` running in a terminal while testing.

## Workflow

- Residents submit the volunteer application with ID photo(s), emergency contact, and consent.
- A Purok Leader/official or admin reviews applications in **Purok Leader → Volunteers**. Approval changes the account role to `volunteer` and starts their points at 0.
- Only approved volunteers can join cleanup drives. Drive capacity is enforced when registering.
- Purok Leaders create drives at **Cleanup Activities**, which creates a random 8-character attendance code. The QR displays the same code. Regenerating it invalidates the old one.
- Volunteers can verify attendance with the code (including QR scanning where the browser supports `BarcodeDetector`) or submit photos. Photo evidence requires leader approval. One successful verification awards 10 points.
- Three days after an event ends, scheduled processing records a no-show violation and deducts up to 10 points if no evidence was submitted and attendance was not verified. The task is idempotent. If the balance reaches 0, role becomes `resident`.
- Current tiers: New Volunteer (0–29), Active Volunteer (30–59), Dedicated Volunteer (60–99), Community Champion (100+).

## New database tables/columns

- `users.volunteer_points`
- `volunteer_applications`
- `cleanup_drives`
- `cleanup_drive_registrations`
- `cleanup_drive_evidence`
- `volunteer_point_transactions`
- `volunteer_violations`

ID images use Laravel's private local storage and are served only through the authenticated Purok Leader review route. Photo evidence is stored on the public storage disk because the event gallery is intended for approved volunteers.

## Notes

- QR scanning requires HTTPS or localhost and camera permission. If `BarcodeDetector` is unavailable, volunteers can type the attendance code.
- The QR display uses QRCode.js from a CDN. If your deployment has no internet, vendor that JS library locally.
- Keep `APP_TIMEZONE` set to Asia/Manila (the app config was updated accordingly).
- The project's current archive's local PHP runtime did not have the SQLite PDO driver, so migration execution could not be verified in this environment. Blade templates compiled successfully and PHP syntax checks passed. Run migrations against your configured DB on your machine before deployment.

## Validation performed on the provided project copy

- All 31 Blade templates compiled through Laravel's Blade compiler.
- PHP syntax checks passed for the feature controllers, model, migration, route files, config, and Vite config.
- Laravel route list resolved 42 routes.
- Migration execution could not run here because the provided PHP environment lacks the PDO SQLite driver. Vite build also could not run in this container because its installed Rolldown native optional binding is missing; perform the install/build steps locally after copying the patch.
