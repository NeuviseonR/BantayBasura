# Resident and Volunteer Fix Log

This update is based on the `my-laravel-app(7).zip` source. It does not redesign or modify the Collection Schedule page or the Purok Leader pages/routes/controllers.

## Resident dashboard and reports

- Connected `/user-dash` to `ResidentDashboardController@index` instead of returning the Blade view directly.
- Kept report statistics scoped to the signed-in account.
- Counted both `pending` and `verified` reports in the dashboard's Pending / Under review total.
- Kept resolved reports in their own total.
- Loaded the three newest reports from the database instead of allowing the dashboard to default to an empty sample collection.
- Loaded upcoming cleanup drives from the database, limited to the signed-in user's barangay.
- Prevented users without a barangay assignment from seeing every barangay's cleanup drives.
- Excluded past and cancelled drives from the dashboard's upcoming-drive list.
- Loaded the organizer relationship and registration counts needed by the drive cards.
- Changed recent-report location text to use the saved reverse-geocoded `location_name`, with a GPS fallback.
- Added the `verified` report state to the dashboard's status mapping and labeled it **Under review**.
- Added the `rejected` report state and a matching restrained badge style instead of showing rejected reports as pending.
- Corrected the Collection Schedule dashboard card's View All destination to the Collection Schedule route.
- Corrected the Upcoming Cleanup Drives dashboard card's View All destination to the Cleanup Drives route.
- Left the Collection Schedule page and its schedule data untouched.

## Resident registration and barangay details

- Removed the Zone field from the resident sign-up form.
- Removed zone validation and zone assignment from account registration.
- Kept Barangay and Purok / street address as the resident's location details.
- Removed zone from the resident profile display and edit form.
- Removed zone wording from the profile's collection-reminder description by removing the nonfunctional notification-preferences panel.
- Stopped copying a resident's zone into new waste reports.
- Removed zone display from the resident dashboard's recent-report entries.
- Preserved the existing database zone columns and shared model compatibility so the untouched Purok Leader pages and excluded Collection Schedule page are not broken by a destructive schema change.
- Existing zone values are not deleted by this patch. The resident and volunteer pages changed here no longer ask for or display them; the Collection Schedule and Purok Leader areas remain unchanged by request.

## Resident profile

- Replaced hardcoded sample profile data with the authenticated user's database record.
- Replaced the sample resident name, email, mobile number, address, avatar, and member-since date with account data.
- Made the role and barangay labels reflect the signed-in account.
- Added database-backed profile updates for first, middle, and last name.
- Added email and mobile-number editing with uniqueness validation.
- Required the current password when changing email or mobile number.
- Normalized supported Philippine mobile-number formats before validation and saving.
- Added date-of-birth and sex updates with validation.
- Added barangay and Purok / street-address updates.
- Added profile-photo upload with JPG, PNG, and WEBP validation and a 5 MB limit.
- Added a `profile_photo_path` database migration and model field.
- Stored profile photos on Laravel's public storage disk and removed the previous photo after a successful replacement.
- Added initials as the account-avatar fallback when no photo is uploaded.
- Added a client-side preview for a newly selected profile photo.
- Implemented password changes with current-password verification, confirmation, and password-strength validation.
- Separated profile and password validation error bags so password errors do not appear in the wrong form.
- Replaced the dead `View my reports` link with the real My Reports route.
- Replaced the dead logout link with a CSRF-protected POST logout form.
- Removed nonfunctional notification-preference switches rather than presenting settings that were not saved or acted upon.
- Removed the nonfunctional notification bell from resident and volunteer page headers.
- Removed the old view-only JavaScript that deliberately prevented profile and password forms from submitting.
- Changed the profile banner from a gradient to a solid project color.
- Restored the profile page's mobile sidebar toggle.

## Volunteer cleanup-drive registration and activities

- Limited the resident/volunteer cleanup-drive listing to future drives in the signed-in user's barangay.
- Excluded cancelled drives from the listing.
- Prevented accounts without a barangay assignment from seeing all cleanup drives.
- Kept the existing volunteer-only check for joining a cleanup drive.
- Fixed rejoining after a volunteer cancels a registration.
- Reactivated the existing unique registration row instead of using `firstOrCreate`, which previously left it cancelled while showing a success message.
- Reset registration/evidence/verification/penalty fields when a cancelled registration is reactivated.
- Removed any old evidence files and records when reactivating a cancelled registration.
- Added a clear error when a volunteer tries to join a drive they already joined.
- Kept capacity checks inside the database transaction and excluded cancelled registrations from the participant count.
- Made the Activities page calculate a separate display status instead of mutating the stored attendance status in memory.
- Corrected the Awaiting verification filter so upcoming `registered` activities no longer appear there.
- Kept Upcoming, Ongoing, Awaiting verification, and Completed filters aligned with their actual display statuses.
- Updated activity-card status styling to use the derived display status.
- Marked activities belonging to cancelled drives as cancelled in the volunteer's activity list.
- Prevented photo evidence and attendance-code verification for cancelled drives.
- Prevented photo evidence submission for cancelled or no-show registrations.
- Added a server-side check that photo evidence has not already been submitted.
- Added a server-side check that attendance-code verification has not already been used.
- Enforced the one-method-only rule between photo evidence and attendance-code verification.
- Put attendance-code verification checks under a registration row lock to prevent simultaneous code/photo submissions from both succeeding.
- Kept photo evidence limited to JPG, JPEG, PNG, or WEBP images, with a maximum of five images and 5 MB per image.
- Kept the existing event window and three-day evidence deadline.
- Excluded cancelled registrations from Participation History.
- Restricted the volunteer gallery route to a volunteer with a non-cancelled registration for that specific drive, rather than any volunteer in the same barangay.
- Changed the Cleanup Drives page's hardcoded Resident role label to the signed-in user's actual role.
- Changed the empty-state text to refer to the barangay team rather than a Purok Leader.
- Changed volunteer-page avatar headers to use the signed-in user's profile photo or initials instead of the sample Maria Lopez / ML identity.
- Changed the volunteer tier explanation to accurately say it is based on the current points balance, matching the controller's calculation.

## Volunteer penalty appeals

- Added a **Volunteer Points / Penalty Appeal** support-ticket category.
- Added a nullable `volunteer_violation_id` foreign key to support tickets.
- Added the corresponding `SupportTicket` model relationship and mass-assignable field.
- Added an Appeal deduction action beside each volunteer violation.
- Preselected the appeal category when a volunteer starts an appeal from a violation.
- Linked the appeal ticket to the exact violation being appealed.
- Validated that the violation belongs to the currently signed-in volunteer.
- Prevented residents or other accounts from submitting an appeal against another user's violation.
- Displayed the linked penalty details on the ticket creation and ticket detail pages.
- Changed the violation status to `appealed` when the linked ticket is created.
- Kept the appeal inside the existing support-ticket workflow and one-active-ticket rule.

## Volunteer identity-data security

- Added an `encrypted` model cast for volunteer application ID numbers.
- Added a migration that expands the ID-number column to text and encrypts existing plaintext ID numbers in batches.
- Made the migration skip values that are already encrypted, avoiding double encryption.
- Kept the Purok Leader application view compatible: the model decrypts the ID number when the existing view reads it.

## Access control and routing

- Added `EnsureResidentOrVolunteer` middleware.
- Registered the `resident.volunteer` middleware alias.
- Applied authenticated resident/volunteer access restrictions to resident and volunteer routes.
- Kept volunteer-only controller checks for My Activities, Participation History, Gallery, Volunteering Log, joining, cancelling, and attendance evidence.
- Kept resident-only controller checks for volunteer applications.
- Connected the resident dashboard route to its controller.
- Added GET and PUT routes for profile updates and password changes.
- Kept the legacy `/user-messages` redirect to Support Tickets.
- Did not modify the Purok Leader route group.

## Support-ticket and Vite configuration

- Added the volunteer-penalty appeal category to the existing ticket categories.
- Loaded the linked violation on the ticket detail page.
- Removed duplicate ticket CSS and JavaScript entries from `vite.config.js`.
- Removed the hardcoded Google Maps API-key fallback from `config/services.php`.
- Removed the hardcoded Google Maps API-key fallback from the resident report page.
- Kept Google Maps configured through `GOOGLE_MAPS_API_KEY` in the local `.env` file.
- Removed the stale `public/hot` file from the working copy so a production build will use the generated manifest.
- A fresh Vite build is still required on the target machine; see `README-FIX-INSTALL.md`.

## Minor interface and behavior cleanup

- Replaced hardcoded profile initials and role labels in resident/volunteer headers with authenticated account values.
- Removed inert notification buttons from resident and volunteer pages.
- Corrected the volunteer application header role capitalization.
- Changed the volunteer application success message so it no longer promises a notification that is not implemented; it now directs the applicant to check the Cleanup Drives page.
- Kept real report and cleanup-drive evidence images as content; decorative account avatars now use account photos or initials.

## Explicitly not changed

- The Collection Schedule page, its sample schedules, zone-based content, and reminder behavior.
- Purok Leader Blade pages, routes, and `CleanupDriveLeaderController`.
- Existing database zone columns and old zone values, to avoid breaking the untouched areas.
- Staff-side ticket handling screens and workflows; this patch adds the resident/volunteer appeal submission path, not a new staff console.

## Boundaries that remain because of the requested scope

- The volunteer gallery route now checks for the viewer's own non-cancelled registration, but the underlying evidence image URLs remain on public storage because the untouched Purok Leader drive-details page directly links to those public URLs. Full private-file enforcement would require changing that leader page and its image route.
- Appeal tickets are linked to the exact violation and the violation is marked appealed, but the Account & Support Manager staff-side response/review console is not part of this resident/volunteer patch.
- The exposed Google Maps key was removed from source defaults, but the replacement key must be created/restricted and configured by the project owner.
- Credentials from the `.env` contained in the uploaded archive must be rotated by the project owner; this patch deliberately does not contain or overwrite `.env`.
