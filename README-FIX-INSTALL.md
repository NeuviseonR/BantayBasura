# Installing the Resident and Volunteer Fixes

This is an overlay patch for the project in `my-laravel-app(7).zip`. Extract it into the Laravel project root and allow the listed files to replace their existing counterparts. It does not contain your `.env`, database, `vendor`, or `node_modules` folders.

## Before replacing files

1. Make a backup of the whole project and your current database.
2. Extract this patch into the existing Laravel project root (the folder containing `artisan`).
3. Do **not** replace your `.env`, `database/database.sqlite`, `storage`, or your existing user-uploaded files.
4. Delete the existing `public/hot` file if it is present. It is a local Vite development-server marker and must not be left behind for a production build.

## Security steps required because the uploaded source archive included `.env`

The source ZIP included `.env` and a hardcoded Google Maps key. Treat credentials contained in that archive as exposed.

1. Rotate any database, mail, API, or other credentials that were present in that `.env` file.
2. Rotate the exposed Google Maps key in Google Cloud Console. Restrict the replacement key to the required Maps JavaScript API and the correct website/referrer origins.
3. Update your local `.env` with the replacement key:

   ```dotenv
   GOOGLE_MAPS_API_KEY=your_new_restricted_key
   ```

4. Rotate `APP_KEY` **before running the new migrations**, because the new migration encrypts existing volunteer ID numbers using the current application key. In this project, the previously stored volunteer ID numbers are plaintext, so rotating before the encryption migration ensures the new encrypted values use the replacement key. Rotating the key later would make those encrypted ID numbers unreadable.

   ```bash
   php artisan key:generate
   ```

   This invalidates existing encrypted cookies/sessions; users will need to sign in again. Do not rotate the key again after the ID-number encryption migration has run unless you also plan a deliberate data re-encryption procedure.

## Apply database and storage changes

Run these commands from the project root:

```bash
php artisan migrate
php artisan storage:link
php artisan optimize:clear
```

The migrations add the profile-photo path, link penalty appeals to their violation records, and encrypt existing volunteer application ID numbers. **Do not use `php artisan migrate:fresh`**; it deletes existing tables and data.

## Rebuild frontend assets

The patch removes duplicate Vite inputs, but the uploaded archive's existing build manifest does not contain the ticket assets. A fresh build is required.

```bash
npm ci
npm run build
```

`npm ci` reinstalls the platform-specific optional Vite/Rolldown package for your operating system. This is important if the existing `node_modules` folder was copied from another OS.

For local development instead of a production build, use:

```bash
npm run dev
```

Keep that terminal running while using the application. Do not delete `public/hot` while the dev server is active; delete it before switching back to a production build.

## Volunteer no-show scheduler

The no-show penalty and cleanup-drive status schedules already exist in `routes/console.php`. They only run if Laravel's scheduler is active.

- Local development: keep this running in a separate terminal:

  ```bash
  php artisan schedule:work
  ```

- Production: configure the server's cron to run Laravel's scheduler every minute:

  ```cron
  * * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
  ```

## Quick checks after installation

```bash
php artisan route:list
php artisan migrate:status
```

Then test these flows manually with separate resident and volunteer accounts:

- Sign up with Barangay and street address only (no Zone field).
- Open the resident dashboard and confirm report counts, recent reports, and upcoming drives are account/barangay scoped.
- Submit a report and confirm its place name appears in My Reports and the dashboard.
- Edit profile information, upload a profile photo, and change the password using the current password.
- Cancel a future volunteer registration, then join that same drive again.
- Submit photo evidence and then try code verification (the second method must be rejected); repeat in the opposite order.
- Confirm the Awaiting verification filter does not include upcoming registrations.
- Open a violation appeal from the Volunteering Log and confirm the created ticket links to that exact violation.
- Confirm a volunteer who did not register for a drive cannot open that drive's gallery route.

## Scope boundaries

- The Collection Schedule page was deliberately left unchanged.
- Purok Leader Blade pages, routes, and `CleanupDriveLeaderController` were deliberately left unchanged.
- Legacy zone database columns and old values remain for compatibility with those untouched areas. New resident signups and new resident waste reports no longer use the zone field.
- Staff-side ticket handling screens are not implemented by this resident/volunteer patch.

## Remaining boundaries to be aware of

- The volunteer gallery route now checks that the viewer has a non-cancelled registration for that drive. However, evidence files are still stored on the public disk because the untouched Purok Leader drive-details page directly links to public storage URLs. Making the underlying files private would require updating that leader page and its image-serving route, which this patch intentionally does not do.
- The resident/volunteer appeal creates a linked support ticket and marks the violation as appealed. The Account & Support Manager's staff-side ticket response/review console is not part of this patch, so a staff workflow still needs to be connected before staff can process and reply to these tickets through a dedicated interface.
- The Google Maps key is no longer hardcoded. The map will not load until a valid replacement key is configured in `.env`.
