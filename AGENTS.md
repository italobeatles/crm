# AGENTS.md

This file is the handoff document for future AI agents working on this CRM.
Read it before changing code. It records what was built, why it was built that
way, and the maintenance assumptions that matter.

## Project Summary

This is a Laravel CRM MVP for a portfolio/demo application. The original
specification came from `crm_simples_requisitos_historias.pdf`, which described
a CRM with Laravel, PHP 8.4, Bootstrap 5, AdminLTE, MySQL, authentication,
dashboard, leads, customers, opportunities, activities, reports, and admin
screens.

The implementation is intentionally conventional Laravel:

- Laravel 13 app with Breeze authentication scaffolding adapted to Bootstrap 5.
- MySQL/MariaDB database, expected locally through XAMPP.
- Blade views with AdminLTE layout.
- Vite for frontend assets.
- JavaScript written in Object Literal Module Pattern, per user request.
- Demo data seed representing about two years of CRM activity.
- GitHub Actions CI for tests and frontend build.

Repository:

- Remote: `https://github.com/italobeatles/crm.git`
- Branch: `main`
- Initial release tag: `v1.0.0`

## Local Environment

The project was developed on Windows, under:

```text
D:\repository\crm
```

The user has XAMPP installed, with PHP and MySQL expected at:

```text
C:\xampp\php\php.exe
C:\xampp\mysql\bin\mysql.exe
```

Default local URL when using `php artisan serve`:

```text
http://127.0.0.1:8000
```

The `.env` used locally points to a MySQL database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_simples
DB_USERNAME=root
DB_PASSWORD=
```

Do not commit `.env`. It is ignored.

## Demo Users

The demo seeder creates these key users:

```text
admin@crm.local / admin123
gestor@crm.local / admin123
vendedor@crm.local / admin123
```

It also creates additional sales/support users for realistic demo distribution.

## Build And Test Commands

Use these commands from the project root:

```powershell
composer install
npm install
php artisan migrate:fresh --seed
npm run build
php artisan test
php artisan serve
```

On Windows, prefer the explicit XAMPP PHP if the PATH is uncertain:

```powershell
& 'C:\xampp\php\php.exe' artisan test
```

Validated commands during development:

```powershell
npm run build
& 'C:\xampp\php\php.exe' artisan test
```

Both passed after the last known changes.

## Git And Release History

The local repo was initialized after development work. The remote already had a
minimal initial commit, so the project commit was rebased on top of it.

Important commits:

- `6f4a4ae` - initial CRM application pushed to GitHub.
- `4d6407e` - README and CI workflow added.

Tag/release:

- `v1.0.0`
- Release URL: `https://github.com/italobeatles/crm/releases/tag/v1.0.0`

When pushing, HTTPS credentials worked locally through Git Credential Manager.
There was no private SSH key available in `~/.ssh` at the time. The user had
provided an SSH passphrase, but no matching key file was present, so pushing was
done over HTTPS.

## Architecture

Routes are in:

```text
routes/web.php
routes/auth.php
```

The root route redirects authenticated users to `dashboard` and guests to
`login`.

Main modules:

- `DashboardController`
- `LeadController`
- `CustomerController`
- `OpportunityController`
- `ActivityController`
- `ReportController`
- `Admin\UserController`
- `Admin\SettingController`

Auth controllers are from Laravel Breeze, adjusted for this app.

Validation lives in `app/Http/Requests`.

Business logic that is not just CRUD belongs in services. Currently:

```text
app/Services/LeadConversionService.php
```

This service converts a lead into a customer and optionally creates an initial
opportunity inside a DB transaction.

## Database Decisions

The user requested that the database follow the style from
`D:\repository\masterpdv\other\dbefood.sql`.

The resulting CRM schema follows these conventions:

- Table names use `tb...` prefixes where practical.
- Primary keys are `id`.
- Timestamps are Portuguese-style columns:
  - `criado_em`
  - `atualizado_em`
  - `deletado_em`
- Main records use soft deletes.
- Status columns are explicit strings or booleans depending on the entity.
- Foreign keys use `id_*` names, e.g. `id_usuario_responsavel`.
- Charset/collation is inherited from Laravel/MySQL config and local database,
  expected as UTF-8/utf8mb4.

Laravel model timestamp mapping is centralized through:

```text
app/Models/CrmModel.php
```

`CrmModel` defines:

```php
public const CREATED_AT = 'criado_em';
public const UPDATED_AT = 'atualizado_em';
public const DELETED_AT = 'deletado_em';
```

`User` cannot extend `CrmModel`, so it repeats those constants directly.

Important model table names:

- `User` -> `tbusers`
- `Lead` -> `tbleads`
- `Customer` -> `tbcustomers`
- `Contact` -> `tbcontacts`
- `Opportunity` -> `tboportunidades`
- `Activity` -> `tbatividades`
- `Note` -> `tbnotes`
- `Setting` -> `tbsettings`

### Migration Ordering Issue

`tbleads` references converted customers, but customers are created in a later
timestamped migration. To avoid migration-order failures, `id_cliente_convertido`
was first created as a nullable indexed integer and the FK was added later in:

```text
database/migrations/2026_04_30_040000_add_missing_foreign_keys_to_crm_tables.php
```

The same pattern was used for `tbcontacts.id_cliente` because the contact
migration timestamp came before the customer migration.

Do not remove that follow-up migration unless you also reorder and regenerate
the earlier migrations.

## Authorization And Roles

Roles are constants on `App\Models\User`:

- `admin`
- `manager`
- `sales`
- `support`

Access rules:

- Admin has admin screens and full management access.
- Manager can see reports and team data.
- Sales/support are restricted mostly to assigned records.

Route role checks use:

```text
app/Http/Middleware/EnsureUserRole.php
```

The middleware alias is registered in:

```text
bootstrap/app.php
```

Visibility filtering is implemented with model scopes named `visibleTo()` on
Lead, Customer, Opportunity, and Activity. Keep using those scopes for new
queries that show user-owned data.

## Frontend Decisions

The app uses Blade + Bootstrap 5 + AdminLTE. The layout is:

```text
resources/views/layouts/app.blade.php
resources/views/layouts/guest.blade.php
```

Main CSS:

```text
resources/css/app.css
```

Main JS:

```text
resources/js/app.js
resources/js/bootstrap.js
```

The user explicitly requested Object Literal Module Pattern for JavaScript.
`resources/js/app.js` follows that with `const CrmApp = { ... }`.

Do not replace this with many standalone functions or framework-specific JS
unless the user asks.

### AlertifyJS

The user requested AlertifyJS instead of browser alerts.

AlertifyJS is installed through npm and imported in:

```text
resources/js/app.js
resources/css/app.css
```

Current usages:

- delete confirmation
- pipeline success/error notifications

Avoid `window.alert()` and `window.confirm()` in new code. Use AlertifyJS.

### Pipeline Drag And Drop

The pipeline view is:

```text
resources/views/opportunities/pipeline.blade.php
```

Cards are draggable and stages are drop targets. The JS posts to:

```text
PATCH /opportunities/{opportunity}/stage
```

implemented by:

```text
OpportunityController::updateStage()
```

The request is sent with Axios and CSRF/XSRF handling. A previous version used
`fetch` and failed with `419 CSRF token mismatch`. The fix was:

- configure Axios in `resources/js/bootstrap.js`
- send `withCredentials`
- send `X-XSRF-TOKEN` using the `XSRF-TOKEN` cookie

If pipeline movement fails again, first inspect browser devtools network for
401/403/419. The original recurring alert was caused by 419, not by the
business logic.

### Action Buttons And Legends

The user asked for icon-only action buttons and legends. The reusable legend is:

```text
resources/views/partials/action-legend.blade.php
```

Action buttons use Font Awesome classes from `@fortawesome/fontawesome-free`.

Keep action buttons compact and icon-based in index/list screens.

## Reports

Reports are intentionally simple. `ReportController` supports:

- opportunities report
- activities report
- CSV export

Routes are protected by `role:admin,manager`.

CSV is streamed with `response()->streamDownload()`.

## Seed Data

The main seeder is:

```text
database/seeders/DemoCrmSeeder.php
```

It does the following:

- truncates CRM tables with `FOREIGN_KEY_CHECKS=0`
- creates users
- creates 260 leads
- converts a subset into 205 customers total
- creates contacts
- creates opportunities
- creates activities, including overdue pending tasks
- creates notes
- creates settings

Important: the seeder uses `TRUNCATE`, which causes implicit commits in MySQL.
A previous implementation wrapped the reset in `DB::transaction()` and failed
with "There is no active transaction". Do not wrap `TRUNCATE` logic in a DB
transaction.

## CI

CI workflow:

```text
.github/workflows/ci.yml
```

It runs on push and pull request to `main` and performs:

- checkout
- PHP 8.4 setup
- Node 24 setup
- Composer install
- `.env` creation
- app key generation
- `php artisan test`
- `npm ci`
- `npm run build`

Tests use the default Laravel test setup, which uses SQLite memory in CI.
The migrations must remain compatible with SQLite enough for the test suite.

## Tests

Most tests are Breeze/Laravel default tests plus small adjustments:

- `tests/Feature/ExampleTest.php` expects `/` to redirect to `/login`.
- User factory maps Laravel's email verification concept to
  `email_verificado_em`.
- `User` implements `MustVerifyEmail` and exposes an `emailVerifiedAt`
  attribute alias so Breeze verification tests keep working with the custom
  column name.

When changing auth/user columns, run the full test suite.

## Known Encoding Issue

Some Portuguese text in existing files appears mojibake-style, for example
`AplicaÃ§Ã£o` instead of `Aplicacao` or `Aplicação`. This likely happened because
PowerShell displayed or wrote UTF-8 content with an incompatible code page.

For future edits, prefer ASCII in new files unless there is a good reason to
use accents. If correcting encoding, do it deliberately in a separate pass and
verify rendered Blade pages and README output. Do not mix broad encoding cleanup
with unrelated feature work.

This `AGENTS.md` is intentionally ASCII.

## Files And Directories To Avoid Committing

Already ignored:

- `.env`
- `vendor/`
- `node_modules/`
- `public/build/`
- `storage` runtime files
- `nbproject/private/`

`nbproject/project.properties` and `nbproject/project.xml` are currently tracked.
They are IDE project metadata. If this becomes undesirable, remove them in a
small explicit commit.

## Current Verification Baseline

At the last development checkpoint, these passed:

```powershell
& 'C:\xampp\php\php.exe' artisan test
npm run build
```

If you modify frontend assets, run `npm run build`.
If you modify controllers, requests, auth, models, or migrations, run the PHP
test suite.

## Practical Maintenance Rules

- Any future change made by an AI agent must be recorded in this file.
- Record code changes, dependency changes, database changes, migrations,
  seed/data changes, CI changes, release/tag changes, operational fixes,
  known issues, and important commands that were run.
- Add the record in the "Change Log For Agents" section at the end of this
  file before finishing the task.
- Keep entries concise but specific enough for the next agent to understand
  what changed, why it changed, and how it was verified.
- Keep Laravel conventions unless the current CRM naming pattern requires
  otherwise.
- Use Form Request classes for validation.
- Keep ownership filtering through `visibleTo()` scopes.
- Keep role checks through `role` middleware for route groups.
- Keep business operations that touch multiple tables in services.
- Preserve the `tb...` table naming and Portuguese timestamp columns unless the
  user explicitly approves a schema redesign.
- Use AlertifyJS for browser feedback.
- Use icon-only action buttons in tables and include an action legend where
  actions are not obvious.
- Avoid broad refactors. This is a small MVP; clear code is more valuable than
  heavy abstraction.

## Deployment Notes

This project has not been configured for production deployment beyond standard
Laravel practices. For deployment, verify:

- `APP_ENV=production`
- `APP_DEBUG=false`
- production database credentials
- web server points to `public/`
- `php artisan migrate --force`
- `npm run build`
- queue/session/cache drivers are appropriate

No queue-dependent business logic exists currently.

## What Was Intentionally Not Built

The original PDF mentioned future possibilities such as advanced BI, external
calendar sync, AI scoring, ERP integration, and multi-company billing. These are
not implemented. The app is an MVP/demo focused on CRUDs, pipeline, dashboard,
reports, and auth.

## Change Log For Agents

Use this section as the ongoing handoff log. Every agent change must add a new
entry here with date, files/areas touched, reason, and verification.

### 2026-06-02 - Handoff Policy Added

- Created `AGENTS.md` as the primary handoff document for future AI agents.
- Added a standing rule that every future change or operational action by an
  agent must be recorded in this file.
- Verification: manually reviewed the file after creation/update.

### 2026-06-02 - Theme Swap: AdminLTE to SpicaAdmin

**Reason:** Replaced AdminLTE 4 with SpicaAdmin Free Bootstrap Admin Template
as requested by the user. The theme was extracted from
`D:\Users\Ítalo Lopes\Downloads\master.zip`.

**Files/areas touched:**

- `public/spica/` (new) - All SpicaAdmin runtime assets copied from the
  template: `css/style.css`, `vendors/css/vendor.bundle.base.css`,
  `vendors/mdi/` (MDI icons), `vendor/mdi/fonts/`, `js/` (off-canvas.js,
  hoverable-collapse.js, template.js, jquery.cookie.js, chart.js),
  `fonts/Roboto/`, `images/` (logo, favicon, auth backgrounds, faces).
  Unused template files (index.html, gulpfile.js, package.json, pages/,
  docs/, scss/, partials/) were not copied or removed after copy.

- `public/spica/js/template.js` - Patched proBanner/bannerClose null guard
  per THEME_AI_NOTES.txt recommendation. The original code assumed
  `#proBanner` and `#bannerClose` always exist; wrapped in a null check.

- `package.json` - Removed `admin-lte` dependency. Bootstrap 5 remains
  installed (SpicaAdmin is built on Bootstrap 5).

- `resources/css/app.css` - Removed `admin-lte/dist/css/adminlte.min.css`
  import. Removed AdminLTE-specific selectors (`.brand-link`,
  `.content-header h1`). Added SpicaAdmin-compatible body background,
  Roboto font, and Spica-style card/metrics classes. Kept Bootstrap 5,
  Font Awesome, and AlertifyJS imports.

- `resources/js/app.js` - Removed `import 'admin-lte'`. Kept Bootstrap 5
  JS import (loaded through Vite).

- `resources/views/layouts/app.blade.php` - Complete rewrite from AdminLTE
  structure (`app-wrapper`, `app-header`, `app-sidebar`, `app-main`,
  `app-content-header`, `app-content`) to SpicaAdmin structure
  (`container-scroller d-flex`, `sidebar sidebar-offcanvas`,
  `container-fluid page-body-wrapper`, `navbar`, `main-panel`,
  `content-wrapper`). Sidebar uses MDI icons instead of Font Awesome.
  Navbar shows user profile dropdown with MDI icons. Footer simplified.
  SpicaAdmin CSS/JS loaded as static assets from `public/spica/`.

- `resources/views/layouts/guest.blade.php` - Rewritten to use SpicaAdmin
  auth layout pattern (`auth-form-light` card centered on page). Removed
  AdminLTE `login-box` / `card-outline` markup.

- `resources/views/dashboard.blade.php` - Updated metric cards from
  AdminLTE `small-box` to SpicaAdmin styled cards with MDI icons and
  grid-margin stretch-card layout. Pipeline table and activities list use
  Spica card-title pattern.

- `resources/views/partials/action-legend.blade.php` - Removed
  `card-outline card-light` classes (AdminLTE-specific); replaced with
  plain `card`.

- `resources/views/layouts/navigation.blade.php` (deleted) - Unused Breeze
  default navigation component, no longer referenced.

**Verification:**

- `npm run build` passes (Vite compiles 113 modules, 314KB CSS, 158KB JS)
- `php artisan test` passes (25 tests, 62 assertions)
- AdminLTE CSS/JS fully removed from bundle; no AdminLTE classes remain in
  any Blade view
- SpicaAdmin sidebar toggle, navbar minimize, off-canvas mobile menu, and
  collapse submenus work via the patched template.js and helper scripts

**Notes for future agents:**

- SpicaAdmin CSS (`public/spica/css/style.css`) references images and fonts
  using relative paths (`../images/`, `../fonts/`) which resolve correctly
  from `public/spica/css/`.
- MDI icons use `mdi mdi-*` classes (e.g. `mdi mdi-view-dashboard`). Keep
  using these in layout/navigation; existing views use Font Awesome (`fas
  fa-*`) which still works via Vite bundle.
- `vendor.bundle.base.js` was intentionally NOT linked in the layout to
  avoid double-loading Bootstrap JS (already loaded through Vite).
- If adding Chart.js, use v2 syntax (yAxes/xAxes arrays). The template's
  `vendors/chart.js/Chart.min.js` is available at
  `public/spica/vendors/chart.js/Chart.min.js`.
- The template.js promo banner is null-guarded and won't run since our
  layout omits the `#proBanner` element. That's intentional.
- Roboto font files are in `public/spica/fonts/Roboto/` if needed for
  local font loading; currently the browser uses Google Fonts fallback
  through the SpicaAdmin CSS.

### 2026-06-02 - Login Background Image

**Reason:** Added a corporate/business environment background image to the
login page as requested by the user.

**Files/areas touched:**

- `public/spica/images/auth/login-bg.jpg` - Downloaded from Unsplash
  (photo by... modern office conference room, free license).
- `resources/views/layouts/guest.blade.php` - Added full-screen background
  image via `.auth-bg` CSS class with `background: url(...) center
  center/cover`. Login card now has translucent backdrop with
  `rgba(255,255,255,0.92)` background and `backdrop-filter: blur(8px)`.

**Verification:**

- `npm run build` passes
- Login page displays full-width background image with centered login card
  and readable text over the translucent overlay
