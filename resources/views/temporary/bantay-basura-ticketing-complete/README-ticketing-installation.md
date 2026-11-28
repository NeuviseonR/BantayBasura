# Bantay Basura — Resident and Volunteer Ticketing Patch

This patch implements the resident/volunteer-facing ticketing workflow only. It does **not** implement notifications or the Administrator, Account & Support Manager, or Purok Leader ticket-handling screens yet.

## Included mechanics

- Residents and volunteers can create and track support tickets.
- Only one **active** ticket is allowed per account. `open`, `in_progress`, and `waiting_for_user` are active. A new ticket can be created after staff changes the previous ticket to `resolved` or `closed`.
- Ticket category, title, and description are required.
- A user may attach either one screenshot (JPG, PNG, WEBP, up to 5 MB) **or** link one of their own waste reports. The two attachment options are mutually exclusive.
- Banned-word checking runs in the browser before the form is submitted and again on the server. If the browser check finds a prohibited term, it prevents submission and leaves the form and selected file in place. Server-side validation is still authoritative if browser checks are bypassed; ordinary browser security prevents a file input from being repopulated after a server redirect.
- The original ticket details cannot be edited or deleted by the resident/volunteer. No edit or delete routes are provided.
- Users can add replies while a ticket is active. Replies are also checked for prohibited language.
- Closed/resolved tickets are read-only for the user.
- Ticket history supports keyword search, status/category filters, newest-first/oldest-first sorting, and pagination.
- Screenshot files are stored on Laravel's private `local` disk and are served only through an authenticated, owner-checked route.
- Existing `/user-messages` links redirect to the new Support Tickets page. The Purok Leader's old Messages page is left untouched for the later Purok Leader stage.

## Installation

1. Back up your Laravel project.
2. Extract this ZIP into the Laravel project root, preserving the folder paths. Allow the included existing files to be replaced:
   - `routes/web.php`
   - `app/Models/User.php`
   - `resources/views/partials/sidebar.blade.php`
   - `vite.config.js`
3. Run the migration:

   ```bash
   php artisan migrate
   ```

4. Clear cached routes/config/views:

   ```bash
   php artisan optimize:clear
   ```

5. Start Vite while developing:

   ```bash
   npm run dev
   ```

   For a compiled presentation build, use `npm run build` instead.

6. Sign in using a Resident or Volunteer account and open **Support Tickets** from the sidebar.

## Banned words configuration

Edit `config/ticketing.php` and update the `banned_words` array. Matching is case-insensitive and uses whole-word/phrase boundaries to reduce accidental matches inside unrelated words. The starter list is not exhaustive; adjust it to the language and moderation policy your project needs.

The same file also contains ticket categories, active statuses, response-time wording settings, and input/attachment limits.

## Important scope notes

- This is the first ticketing stage: resident and volunteer pages plus the database foundation.
- The Manager/Admin/Purok Leader interfaces that review, assign, reply to, resolve, and close tickets are not included yet. Until those staff-side mechanics are added, an active ticket will remain active and the user will not be able to open another one.
- No notification tables, triggers, UI, or notification code were added.
- The cleanup script removes the obsolete resident `user-messages.blade.php` and `user-messages.css` files. `purok-messages.blade.php` and the Purok Leader messaging route are intentionally untouched for the later Purok Leader ticketing stage.
- The ticket's initial description and later replies are stored separately. The original title/category/description/attachment are not editable from the resident/volunteer interface.


## Old resident Messages cleanup included

Run `powershell -ExecutionPolicy Bypass -File .\cleanup-old-resident-messages.ps1` from the Laravel project root to remove `resources/views/user-messages.blade.php` and `resources/css/user-messages.css`. The Vite entry for the old resident stylesheet is removed, and the new ticket CSS and JavaScript assets are registered. The old `/user-messages` URL remains as a redirect to `/user-tickets` so bookmarks do not break. The Purok Leader's `purok-messages.blade.php` and its route are intentionally untouched for the later Purok Leader ticketing stage.

No notification functionality is included or modified.
