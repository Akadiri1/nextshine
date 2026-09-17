# NextShine Cleaning

Website for NextShine Cleaning, a trading division of NextShine Group Ltd,
serving landlords, letting agents and businesses in Edinburgh.

Built on the McKodev PHP framework (the runtime, `mck` migration CLI and
Tailwind front end shared with `hcfoundation`). All page content lives in the
database and is edited live through ADMC.

## Requirements

- PHP 7.4 or newer (runs on 8.x). `core/compat.php` polyfills the PHP 8 string
  helpers; keep request paths free of `match`, the nullsafe operator and arrow
  functions so both versions keep working.
- MySQL / MariaDB
- Apache with `mod_rewrite`
- Node.js, only to rebuild the CSS

## Setup

1. Create the environment file, then edit it:

   ```
   cp .env/config.example.php .env/config.php
   ```

2. Create the database, set `DB_*` in `.env/config.php`, and build the schema:

   ```
   php mck migrate
   php mck seed
   ```

   Seeders only insert rows that are missing, so `php mck seed` is safe to run
   against a database that already has live content.

3. Point the vhost DocumentRoot at `www/` (or at the project root; there is an
   `.htaccess` for both).

4. Add the site's domain to **Allowed Headers** (`panel_allowed_headers`) in the
   admin. The admin CRUD endpoints and the quote form reject other hosts.

5. Set the SMTP details in **Website Info** (`settings_website_info`) so quote
   requests can be emailed.

### PRODUCTION_MODE

Detailed database errors are shown only when `PRODUCTION_MODE=false` **and** the
request comes from a local address. Anything else gets a generic 503.

## Layout

```
.env/config.php     Environment. Git-ignored. Parsed into constants by core/autoload.php.
.htaccess           Rewrites all traffic into www/ (when DocumentRoot is the project root).
App.php             Path + URL helper.
mck                 CLI: migrate, migrate:rollback, migrate:fresh, seed, migration:create, seeder:create.
core/               Runtime: autoloader, PHP 7 compatibility, Controllers/ class library.
framework/          Migrator, Blueprint, Seeder. Namespace App\.
database/           migrations/ and seeds/.
src/input.css       Tailwind source: design tokens in use, component classes.
v1/                 The application.
www/                Document root.
```

| Path                            | Purpose |
|---------------------------------|---------|
| `v1/models/model.php`           | PDO connection (`$conn`) |
| `v1/controllers/controller.php` | Query helpers (`selectContent`, `selectContentAsc`, ...) |
| `v1/routes/`                    | `router.php` (public), `admin_router.php`, `ajax_router.php` |
| `v1/views/`                     | Pages |
| `v1/views/includes/`            | `header.php`, `footer.php`, `theme.php` |
| `v1/views/includes/sections/`   | Page sections shared across pages (hero, pricing, contact, ...) |
| `v1/views/includes/partials/`   | `logo.php`, `page-hero.php`, `service-card.php` |
| `v1/admin/`, `v1/ajax/`         | Table-driven admin panel and its CRUD endpoints |
| `v1/admc_ext/`                  | ADMC admin-session bridge (`/mck_ext`) |
| `v1/auth/`                      | Login, signup, password reset |
| `www/assets/`                   | `css/app.css` (built), `js/app.js`, `images/` |

## Routing

`www/index.php` loads the site settings, then includes the routers in order.
The first match wins and stops the request:

1. `v1/routes/admin_router.php` — `/add/<table>`, `/create/<table>`, `/manage/<table>`
2. `v1/ajax/ajax_router/router.php` — generic CRUD (`/add`, `/read`, `/put`, `/delete`, ...)
3. `v1/admc_ext/ext_route/router.php` — `/mck_ext` admin session bridge
4. `v1/auth/auth_router/router.php` — `/login`, `/signup`, ...
5. `v1/routes/ajax_router.php` — `/quote-request`
6. `v1/routes/router.php` — public pages, falling through to `views/404.php`.
   `/beauty` and every path under it go to `v1/routes/beauty_router.php`.

## Pages

| Route | View |
|---|---|
| `/`, `/home` | `home.php` (NextShine Group: hero, the two divisions, quote form) |
| `/cleaning` | `cleaning.php` (services, how it works, pricing, contact) |
| `/services/<hash_id>/<slug>` | `service-details.php` |
| `/reviews` | `reviews.php` (shows "Real reviews coming soon" until a review is visible) |
| `/contact` | `contact.php` |
| `/services`, `/pricing`, `/about`, `/coverage` | Retired: 301 to `/cleaning`, `/cleaning#pricing`, `/`, `/contact#coverage` |
| `/beauty` | `beauty/home.php` (NextShine Beauty, with its own design) |
| `/beauty/booking-request` (POST) | `beauty/booking-request-mail-backend.php` |
| `/beauty/<anything else>` | `beauty/404.php` |
| `/quote-request` (POST) | `quote-request-mail-backend.php` |

## Database

Schema changes are migrations in `database/migrations/`, never hand-run SQL.

```
php mck migration:create add_something_to_panel_services
php mck migrate
php mck migrate:rollback
```

Once a migration has run anywhere, do not edit it; add a new one.

Tables and columns follow the ADMC naming conventions in `ADMC_framework.md`
(`panel_`, `settings_`, `selection_`, `addition_` prefixes; `input_`, `text_`,
`image_1` columns). Blueprint has ADMC-specific helpers:

```php
$table->id();
$table->string('hash_id');
$table->string('input_title')->default('');
$table->text('text_description')->nullable();
$table->admcColumns();   // visibility, date_created (DATE), time_created (TIME), created_by
```

## Content and live editing

- Every block of copy is a database row. When an admin session is open
  (`/mck_ext` sets `$_SESSION['admin_id']`), the footer loads `admc.min.js`,
  which makes anything carrying `data-admc-*` attributes editable in place.
  Keep those attributes, and the `data-cbsection` / `cbcode` markers, on
  anything editable.
- Icons on both sites are Font Awesome 6 class names stored in `input_icon`
  columns, e.g. `fa-solid fa-phone` or `fa-brands fa-whatsapp`.
- The home page is the NextShine Group page from the client's sample: the hero
  (`settings_home_hero`, which also holds the page title and description), the
  two divisions (`settings_home_divisions`, `panel_home_divisions`, each
  division's services in `addition_home_division_services`; `input_theme`
  `beauty` gives a card NextShine Beauty's colours) and the quote form. The
  form's services are grouped under headings by `input_group` in
  `selection_form_services`.
- The footer covers both divisions: **Cleaning Services** lists `panel_services`
  (each linking to its detail page) and **Beauty Services** lists the Beauty
  page's styles and hair shop (`panel_beauty_services`, `panel_beauty_products`,
  linking to `/beauty#services` and `/beauty#hair`). Both headings are in
  `settings_home_footer`.
- The menu follows the sample: Home, Cleaning, Beauty, Contact. Reviews
  (`panel_home_nav`) and the Beauty button (`settings_home_nav_button`) are
  hidden, not deleted.
- Brand colours come from **Site Colors** (`settings_site_colors`).
  `includes/theme.php` turns them into CSS variables that the Tailwind palette
  reads, so recolouring needs no rebuild.
- **Maintenance mode**: set `maintenance_status` to 1 in Website Info. Visitors
  see `views/maintenance.php`; signed-in admins still see the site.

## NextShine Beauty (/beauty)

NextShine Beauty is a separate business with its own look, served as a page
of this site at `/beauty`, from the same codebase and database.

- `v1/routes/router.php` hands `/beauty` and every path under it to
  `v1/routes/beauty_router.php`, so nothing there falls through to the
  cleaning pages. The menu links to it (Home, Cleaning, Beauty, Contact), as
  does its card on the home page. A gold button beside "Get a Quote" (**Home
  Nav Button**, `settings_home_nav_button`: text, Font Awesome icon and link)
  is available but hidden.
- Views are in `v1/views/beauty/`, with their own header, footer and 404.
  Styles: `src/beauty.css` + `tailwind.beauty.config.js`, built to
  `www/assets/css/beauty.css`. Script: `www/assets/js/beauty.js`.
- Content lives in the `*_beauty_*` tables and is live-editable like the
  cleaning site, with the same admin login. Maintenance mode covers it too.
- Menus: **Beauty Nav** (`panel_beauty_nav`) feeds the navbar and mobile
  menu (the black top bar only carries its message); **Beauty Footer Links**
  (`panel_beauty_footer_links`) the footer. Links are site paths (`/`,
  `/cleaning`, `/beauty`) or anchors on the Beauty page (`#booking`); the
  `/beauty` link is highlighted as the current page.
- The booking form posts to `/beauty/booking-request` and emails the address
  in **Beauty Site** (`settings_beauty_site`), or the main site email when
  that is blank.
- Photos: the hero, each service and hair shop product (`image_1` columns),
  and the **Our Work** gallery (`panel_beauty_gallery`). Admins click a photo
  to replace it; empty slots show an "Add a photo" box only to admins, and
  gallery items without a photo are hidden from visitors. The current photos
  are Pexels stock placeholders listed in `www/assets/images/beauty/CREDITS.md`;
  swap them for the client's own work before launch.

## Front end

```
npm install
npm run dev          # cleaning site CSS: watch and rebuild while working
npm run dev:beauty   # beauty site CSS: the same
npm run build        # both, minified, for deploy
```

- `tailwind.config.js` holds the brand palette and breakpoints. The breakpoints
  (`xs` 481px, `md` 769px, `lg` 1025px) match the widths the design was built
  around.
- `src/input.css` holds the component classes (buttons, cards, form fields,
  section chrome). Layout is written with utilities in the views.
- `www/assets/css/app.css` is committed, so the site runs without a build step.
  Rebuild it after changing classes in any view.
- `www/assets/js/app.js` has no dependencies: navigation state, mobile menu,
  pricing tabs, quote form submission, anchor scrolling and card reveals.
