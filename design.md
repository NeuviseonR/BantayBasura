# Design Guidelines — Bantay Basura

## 1. Design direction

Create a simple, professional, approachable interface that helps residents, volunteers, and barangay/purok staff complete tasks quickly.

- Prioritize clarity, consistency, accessibility, and easy navigation.
- Keep layouts calm and uncluttered. Use clear page titles, short descriptions, predictable actions, and consistent spacing.
- Retain the application's existing green, orange, and warm yellow identity rather than introducing an unrelated palette.
- Prefer familiar interface patterns over decorative effects.
- **Use icons instead of decorative images or illustrations.** Use a consistent icon set (the project already uses icon elements in several styles). Icons should clarify meaning, not merely decorate. Use text labels alongside important navigation and action icons.
- **Do not use gradients anywhere.** Use solid colors for backgrounds, buttons, banners, overlays, and accent areas. Replace existing gradient treatments with solid-color surfaces.
- Avoid unnecessary animation, visual clutter, oversized decorative elements, and excessive shadows.

## 2. Existing visual identity

The current stylesheets establish a forest-green and warm-orange palette, with yellow used for contrast and highlights. Preserve these recognizable colors.

### Core colors

| Purpose | Existing value | Guidance |
|---|---|---|
| Primary green | `rgb(30, 58, 23)` / `#1E3A17` | Main brand color, sidebar, primary navigation, important headings and primary buttons |
| Dark green text | `rgb(17, 46, 17)` / `#112E11` | Main text on light backgrounds |
| Secondary orange | `rgb(216, 119, 39)` / `#D87727` | Primary actions, active indicators, links, highlights and selected states |
| Light contrast yellow | `rgb(255, 249, 162)` / `#FFF9A2` | Text or restrained highlight surfaces against dark green; avoid large areas that reduce readability |
| Light yellow | `rgb(243, 202, 67)` / `#F3CA43` | Small accents and icon emphasis |
| White | `#FFFFFF` | Cards, forms, tables and content surfaces |
| Muted text | Existing dashboard token: `rgba(17, 46, 17, 0.7)` | Secondary descriptions, metadata and helper text |
| Subtle border | Existing dashboard token: `rgba(30, 58, 23, 0.15)` | Card, input, divider and table borders |
| Light page surface | Existing dashboard token: `rgba(255, 249, 162, 0.2)` | Very subtle page background tint |

Use the existing CSS custom properties where possible: `--primary-color`, `--secondary-color`, `--light-contrast`, `--light-text`, `--dark-text`, `--bg-main`, `--card-bg`, `--text-muted`, and `--border-color`.

Do not introduce many new accent colors. Status colors may be used when they communicate a real state (for example, success, warning, error, or information), but they should remain restrained and consistent across the application.

## 3. Typography

The project currently uses **Arial, Helvetica, sans-serif** in the public-facing stylesheet and **Inter, sans-serif** in the dashboard stylesheet. The dashboard also uses a range of clear, compact font weights.

- Prefer **Inter** as the consistent interface font where it is already available in the project.
- If Inter is not loaded in a particular page, use the existing Arial/Helvetica sans-serif fallback rather than adding a decorative font.
- Use a clear hierarchy:
  - Page title: 1.5–1.8rem, bold.
  - Section heading: 1.05–1.2rem, semibold or bold.
  - Body text: 0.9–1rem, regular.
  - Labels and metadata: 0.75–0.875rem, medium.
- Keep paragraphs comfortably readable with a line-height around 1.45–1.6.
- Avoid using bold for every line. Reserve heavier weights for titles, labels, important values, and actions.
- Keep text color contrast strong, especially on green and orange surfaces.

## 4. Layout and navigation

### General layout
- Use a consistent content container and alignment across pages.
- Give page content comfortable padding; dashboard pages currently use approximately 28–32px on larger screens.
- Use a consistent spacing scale (for example, 4, 8, 12, 16, 20, 24, and 32px).
- Group related information into clearly titled sections.
- Keep the most important information and primary action near the top of each page.
- Avoid horizontal overflow. Tables and dense content should scroll within their own container on small screens.

### Navigation
- Keep navigation labels clear and familiar.
- Use the existing dark-green sidebar pattern for authenticated dashboard areas.
- Highlight the current page with a clear active state using the existing orange accent.
- Use icons with text labels for primary navigation. Do not make users guess what an icon means.
- Keep navigation order consistent between related user and staff pages.
- On smaller screens, provide a compact, usable navigation pattern without hiding essential destinations.

### Page structure
A typical internal page should follow this order:
1. Page title and a short explanatory sentence.
2. Primary action(s), if applicable.
3. Key summary information, when useful.
4. Main content (cards, lists, tables, forms, or map).
5. Secondary information and supporting actions.

Do not add summary cards or dashboard widgets when they do not help the user's task.

## 5. Cards, boxes, borders, and surfaces

The existing dashboard uses white cards, thin green-tinted borders, modest corner radii, and light shadows. Keep this restrained visual language.

- Main cards: white background, `1px solid var(--border-color)`, and approximately `12px` corner radius (`--radius-md`).
- Smaller controls and compact boxes: approximately `8px` corner radius (`--radius-sm`).
- Larger grouped panels: approximately `16px` corner radius (`--radius-lg`).
- Use a thin border to define card edges. Do not use thick decorative outlines.
- Use subtle shadows only to establish hierarchy (the existing `--shadow-sm` and `--shadow-md` are suitable). Avoid heavy or colored shadows.
- Use consistent internal padding, generally 16–24px depending on the component.
- Separate content within cards with spacing or thin dividers rather than multiple nested boxes.
- Use accent borders sparingly, such as the existing orange left-border treatment for a highlighted notice or item.
- Inputs, selects, tabs, and buttons should have consistent border thickness, radius, height, and focus treatment.
- Avoid placing a border around every individual text element. Box only content that forms a meaningful group.

Existing radius tokens:
- `--radius-sm: 8px`
- `--radius-md: 12px`
- `--radius-lg: 16px`
- `--radius-full: 9999px` (use for pills, tags, and circular controls only)

## 6. Buttons and interactive elements

- Use solid primary green for primary actions when the action is the main page action.
- Use solid secondary orange for selected states, high-visibility calls to action, or important accent actions. Do not make every button orange.
- Use white or transparent backgrounds with a subtle border for secondary actions.
- Use text labels for important actions; an icon-only button is appropriate only for universally recognizable actions and must have an accessible name.
- Keep button sizes and corner radii consistent.
- Provide visible hover, pressed, disabled, and keyboard-focus states.
- Use a clear confirmation step for destructive actions such as deleting or archiving records.
- Do not communicate status through color alone; include a text label or icon where appropriate.

## 7. Forms and data entry

- Place a visible label above every input. Do not rely on placeholder text as the only label.
- Group related fields and use a logical top-to-bottom order.
- Keep field widths appropriate to their content; avoid unnecessarily wide forms.
- Use consistent input height, padding, border, and corner radius.
- Provide concise helper text and clear validation messages near the relevant field.
- Make required fields identifiable.
- Keep submit and cancel actions easy to distinguish.
- Ensure focus states are visible and keyboard navigation works.

## 8. Tables, lists, and status indicators

- Use tables for genuinely tabular information and lists/cards for content that is easier to scan as individual records.
- Use clear column headings, adequate cell padding, and thin horizontal dividers.
- Keep row actions aligned and visually consistent.
- Use a subtle row hover state without making the table visually busy.
- On narrow screens, place tables in a horizontally scrollable wrapper or use a readable stacked layout.
- Use compact status badges with consistent padding and rounded pill styling.
- Keep status colors consistent throughout the app and pair them with readable status text.

## 9. Icons and imagery

- **Prefer icons instead of images** for interface decoration, feature tiles, dashboard summaries, empty states, and navigation.
- Use one consistent icon library and consistent stroke/weight/size throughout the app. The existing styles already use icon elements, so continue that approach rather than mixing unrelated icon styles.
- Use icons that are immediately understandable (for example: home, user, calendar, map pin, message, report, check, alert, settings).
- Pair icons with text when the meaning or action is not obvious.
- Do not use emoji as a substitute for interface icons.
- Avoid decorative stock photos, illustrations, and image thumbnails in the interface. If a real image is essential to the underlying feature or user-submitted content, display it only where it provides necessary information; the interface itself should remain icon-led.
- Keep icons aligned to text and use the brand colors or neutral text colors.

## 10. Maps and location-related content

- Maps are functional content, not decoration. Give them a clear title, useful controls, and a readable legend where needed.
- Keep map containers within the same border and radius system as other cards.
- Use simple, distinct markers and status colors with labels or a legend.
- Do not add decorative map imagery or gradient overlays.

## 11. Feedback, empty states, and accessibility

- Make success, error, warning, and informational feedback concise and easy to notice.
- Empty states should explain what is missing and, when appropriate, provide one clear next action. Use a relevant icon rather than an illustration.
- Maintain sufficient color contrast for text, controls, and status indicators.
- Ensure all interactive elements can be reached and used with a keyboard.
- Use visible focus indicators; do not remove outlines without providing an accessible replacement.
- Use semantic headings, labels, buttons, and links.
- Give icon-only controls accessible names and provide alternative text for any meaningful content image that remains necessary.

## 12. Responsive behavior

- Design for desktop first only where the existing workflow requires it; all pages must remain usable on tablet and mobile.
- Collapse multi-column layouts into one column when space is limited.
- Keep buttons and form controls comfortably tappable.
- Prevent sidebars, tables, cards, and dialogs from extending beyond the viewport.
- Keep page titles, actions, and important information visible without excessive scrolling.
- Use consistent responsive breakpoints across related pages.

## 13. Do and don't

### Do
- Reuse the existing green, orange, and yellow palette.
- Use solid-color backgrounds and surfaces.
- Use Inter where already used, with the existing sans-serif fallback.
- Use thin borders, modest radii, and subtle shadows.
- Use clear labels, consistent spacing, and predictable navigation.
- Use icons instead of decorative images.
- Keep pages clean, professional, accessible, and task-focused.

### Don't
- **Do not use gradients.**
- **Do not use decorative images or illustrations where an icon can communicate the same idea.**
- Do not introduce unrelated colors or multiple competing font families.
- Do not use heavy shadows, thick borders, or excessive rounded containers.
- Do not overfill dashboards with unnecessary cards and charts.
- Do not use icon-only navigation for important destinations without labels.
- Do not sacrifice readability or accessibility for visual styling.

## 14. Implementation note

Treat the existing CSS variables and component patterns as the starting point. Reuse and consolidate them instead of creating a different visual system for every page. Where current pages conflict, prefer the shared rules in this document: a consistent sans-serif typeface, the established green/orange palette, white or softly tinted surfaces, thin borders, modest corner radii, accessible controls, icon-led UI, and **no gradients**.
