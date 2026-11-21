# Purok Cleanup Activities update

## Included behavior
- Compact create-drive form beside the drives list.
- Existing drive rows stay collapsed until clicked; expanded view shows details, participant management, code regeneration, editing (only before start), and cancel action.
- Drive status is calculated from the scheduled timestamps. Only cancellation is manually set.
- Participant list modal displays registered participant names, points, calculated volunteer tiers, registration state, evidence count, and verification timestamp. Registration date/status may be edited only before the drive begins.
- Create/edit location uses the same Google Maps pin-selection pattern as the resident waste-report form. Map click and draggable pin set coordinates; GPS button uses browser geolocation.
- Coordinates are stored in new nullable latitude/longitude columns.
- Page styling is isolated in `resources/css/purok-activities-db.css`.

## Install
1. Back up the current project.
2. Extract this patch into the Laravel project root, merging folders and replacing the included files.
3. Run:
   ```sh
   php artisan migrate
   php artisan optimize:clear
   npm install
   npm run build
   ```
   For Vite development, use `npm run dev` instead of build.
4. Ensure the Google Maps JavaScript API key is configured as `GOOGLE_MAPS_API_KEY` in `.env` (the config has a legacy fallback to the same key used by the report form). Restrict the key by HTTP referrer and API.
5. Open `/purok-activities` as an authenticated leader account with an assigned barangay.

## Files to delete
No files need to be deleted. The older `resources/views/purok-activities.blade.php` prototype is not the route's active view; it can be retained safely.

## Notes
- The attendance code is stored hashed. It cannot be shown again as the original code; use **Regenerate attendance code** to create a new one, which is displayed once after the action.
- A drive becomes Scheduled before start time, Ongoing between start and end, and Completed after end. Cancelled remains cancelled.
- Participant edit controls are disabled once the drive starts. Drive editing and code regeneration are also disabled after start. Cancellation remains available until the event completes.
