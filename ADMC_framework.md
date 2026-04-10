# ADMC Framework — AI Reference Guide
**Admin Data Management Console | Authoritative Source of Truth for Code Generation**

---

## 1. What Is ADMC?

The **Admin Data Management Console (ADMC)** is a PHP/MySQL framework for building and managing content-driven websites. It uses a strict set of naming conventions, whitelisted functions, and HTML attributes to keep all projects consistent, secure, and manageable from a live admin panel.

Your role when working within this framework: **Expert System Developer**. Every piece of code you generate must be native to this system — not generic PHP. You produce complete **Scaffolding Packages** (PHP/HTML + SQL) that are ready for review and deployment without further modification.

---

## 2. Database Naming Conventions

### 2.1 Table Prefixes

The prefix of a table defines what administrators can do with it:

| Prefix | Purpose | Admin Permissions |
|---|---|---|
| `panel_` | Dynamic, user-managed content (e.g. blog posts, services) | Add, Edit, Delete |
| `settings_` | Fixed page elements (e.g. hero banners, contact blocks) — usually one row | Edit only (no delete) |
| `read_` | View/edit only, no add or delete (e.g. `read_newsletter`) | View, Edit |
| `addition_` | One-to-many child data linked to a parent record | Add, Edit, Delete |
| `selection_` | Category lists for use in `select_` columns | Add, Edit, Delete |

**Examples:**
```
panel_blog          → blog posts
panel_our_services  → service items
settings_home_banner → homepage hero content (single row)
read_newsletter     → newsletter subscribers
addition_apartment_features → features linked to an apartment record
selection_blog_category → categories for blog posts
```

---

### 2.2 Mandatory Columns (Every Table, No Exceptions)

Every table in the ADMC system **must** contain these six columns:

```sql
id          INT PRIMARY KEY AUTO_INCREMENT
hash_id     VARCHAR(255) NOT NULL
visibility  VARCHAR(50) DEFAULT 'show'
date_created DATE NOT NULL
time_created TIME NOT NULL
created_by  VARCHAR(255) NOT NULL
```

- `hash_id` — system-generated alphanumeric ID used for cross-table linking and public URLs (never expose the raw `id`)
- `visibility` — only accepted values: `"show"` or `"hide"`
- `created_by` — stores the `hash_id` of the admin who created the record

---

### 2.3 Column Naming Prefixes

The column prefix tells the ADMC panel what input type to render for that field.

| Prefix | Input Type | SQL Data Type | Example |
|---|---|---|---|
| `input_` | Single-line text field | `VARCHAR(255)` | `input_title`, `input_name` |
| `text_` | Multi-line textarea | `TEXT` or `LONGTEXT` | `text_description` |
| `icon_` | Icon picker (Font Awesome, MDI) | `VARCHAR(100)` | `icon_service_icon` |
| `dated_` | Date picker | `DATE` | `dated_event_start` |
| `timed_` | Time picker | `TIME` | `timed_event_time` |
| `bgcolor_` | Background color picker | `VARCHAR(100)` | `bgcolor_section` |
| `textcolor_` | Text color picker | `VARCHAR(100)` | `textcolor_heading` |
| `decrement_` / `increment_` | Increment/decrement buttons (no stored data) | — | `increment_quantity` |

---

### 2.4 Special Column Keywords

#### `image_1` — Single Image
Use when a record has exactly **one** image. The ADMC renders a single file uploader.
```sql
image_1 TEXT
```

#### `image_2` — Multiple Images (Gallery)
Use when a record has a **primary image + a gallery**. The primary path is stored in `image_2` on the parent table. All additional images are stored in the system table `images` (linked by `asset_hash_id = parent.hash_id`).
```sql
image_2 TEXT  -- on panel_ table
```
> ⚠️ A table may contain `image_1` **or** `image_2` — never both.

#### `select_` — Category Reference
Signals that this column links to a `selection_` table. The ADMC renders a dropdown.
```
select_blog_category  →  links to  selection_blog_category
```

#### `add_` — Additional Data Reference
Signals that this record has related rows in an `addition_` table. The ADMC renders an "add more" interface.
```
add_apartment_features  →  links to  addition_apartment_features
```

---

### 2.5 Linking Records Across Tables

The `addition_` tables use two special columns to create the parent-child relationship:

| Column | Purpose |
|---|---|
| `tb` | The name of the parent table |
| `tb_link` | The `hash_id` of the specific parent record |

---

## 3. PHP Functions — Whitelist (Use Only These)

You are **strictly prohibited** from writing raw SQL, using `mysqli_query()`, or defining new functions. Use only this approved list:

### `selectContent($conn, $table, $where)`
Standard SELECT query. Returns an array of rows.
```php
// Fetch all visible services
$services = selectContent($conn, "panel_services", ["visibility" => "show"]);

// Fetch with multiple conditions
$books = selectContent($conn, "panel_books", ["visibility" => "show", "status" => 1]);

// Fetch all records (no WHERE clause)
$all = selectContent($conn, "panel_books", []);
```

**For `settings_` tables (single row):** append `[0]` to get the record directly:
```php
$banner = selectContent($conn, "settings_home_banner", ["visibility" => "show"])[0];
```

---

### `selectContentDesc($conn, $table, $where, $orderColumn, $limit)`
Fetches records sorted in **descending** order, limited to a count.
```php
// Fetch 5 most recent blog posts
$posts = selectContentDesc($conn, "panel_blog", ["visibility" => "show"], "id", 5);
```

---

### `selectContentAsc($conn, $table, $where, $orderColumn, $limit)`
Fetches records sorted in **ascending** order, limited to a count.
```php
// Fetch nav items sorted by order
$navItems = selectContentAsc($conn, "panel_pages", ["visibility" => "show"], "input_order", 10);
```

---

### `insertContent($conn, $table, $dataArray)`
Inserts a new record.
```php
$new['hash_id']      = rand(00000, 99999); // must be unique
$new['input_name']   = "Demo Service";
$new['text_description'] = "Lorem ipsum";
$new['visibility']   = "show";
$new['date_created'] = date('Y-m-d');
$new['time_created'] = date('H:i:s');
insertContent($conn, "panel_services", $new);
```

---

### `updateContent($conn, $table, $setArray, $whereArray)`
Updates an existing record. The `$where` array **must not** be empty.
```php
$set['visibility'] = "hide";
$where['id'] = 4;
updateContent($conn, "panel_books", $set, $where);
```

---

### `insertSafe($conn, $table, $dataArray)`
A safe variant of insert used for trusted/validated data (e.g. newsletter sign-ups).

---

### `shortContent($string)`
Truncates a string to **50 words**.

### `previewBody($string, $wordLimit)`
Truncates a string to a **specified word count**.

### `decodeDate($date)`
Converts a stored date (e.g. `2025-01-15`) to a readable format (e.g. `January 15, 2025`).

---

### Strictly Prohibited Functions
> Never use: `eval()`, `shell_exec()`, `system()`, `passthru()`, `exec()`, `file_put_contents()`, `unlink()`, `mysqli_query()`, or any function not listed above. Never define new functions with the `function` keyword.

---

## 4. Relational Data — Pre-Indexing Pattern (Mandatory)

When displaying `addition_` (child) records alongside their parent records, **never run a query inside a loop**. Use the pre-indexing pattern:

```php
// STEP 1: Fetch all child records in ONE query
$socials_raw = selectContent($conn, "addition_team_socials", ["visibility" => "show"]);

// STEP 2: Pre-index by parent's hash_id (tb_link is the parent's hash_id)
$indexed_socials = [];
foreach ($socials_raw as $link) {
    $indexed_socials[$link['tb_link']][] = $link;
}

// STEP 3: In your render loop, look up children with no extra DB call
$members = selectContent($conn, "panel_team_members", ["visibility" => "show"]);
foreach ($members as $member) {
    if (isset($indexed_socials[$member['hash_id']])) {
        foreach ($indexed_socials[$member['hash_id']] as $social) {
            echo '<a href="' . $social['input_url'] . '"><i class="' . $social['icon_social_icon'] . '"></i></a>';
        }
    }
}
```

---

## 5. Dynamic Navigation (panel_pages + addition_pages)

The navigation bar is **database-driven**, not hard-coded.

- **Parent items** → `panel_pages`
- **Dropdown/child items** → `addition_pages` (linked via `tb_link = parent.hash_id`)

### panel_pages Key Columns
| Column | Purpose |
|---|---|
| `hash_id` | Used to link dropdown children |
| `input_name` | Label shown in the menu |
| `input_link` | URL slug (e.g. `/about`) |
| `input_order` | Sort order in the menu |
| `visibility` | Must be `"show"` to appear |

### addition_pages Key Columns
| Column | Purpose |
|---|---|
| `tb_link` | `hash_id` of the parent `panel_pages` record |
| `input_name` | Dropdown item label |
| `input_link` | Dropdown item URL |
| `input_order` | Sort order in the dropdown |
| `visibility` | Must be `"show"` to render |

### Navigation Render Pattern
```php
// Fetch parents sorted by order
$fetchHeaders = selectContentAsc($conn, 'panel_pages', ['visibility' => 'show'], "input_order", 10);

// Fetch and pre-index children
$dropdownHeaders = selectContentAsc($conn, 'addition_pages', ['visibility' => 'show'], "input_order", 10);
$dropdownHeadersbyHash = [];
foreach ($dropdownHeaders as $key => $values) {
    $dropdownHeadersbyHash[$values['tb_link']][] = $values;
}
```

```php
<ul class="main-menu__list">
<?php foreach($fetchHeaders as $key => $values) { ?>
    <li class="<?php if(isset($dropdownHeadersbyHash[$values['hash_id']])) { echo "dropdown"; } ?>">
        <a href="<?= $values['input_link'] ?>"><?= $values['input_name'] ?></a>
        <?php if(isset($dropdownHeadersbyHash[$values['hash_id']])) { ?>
            <ul>
            <?php foreach($dropdownHeadersbyHash[$values['hash_id']] as $childKey => $childValue) { ?>
                <li><a href="<?= $childValue['input_link'] ?>"><?= $childValue['input_name'] ?></a></li>
            <?php } ?>
            </ul>
        <?php } ?>
    </li>
<?php } ?>
</ul>
```

---

## 6. Live Edit System — data-admc- Attributes

The Live Edit system allows admins to edit content directly on the live website. You **must** implement these attributes on every editable element. Their absence means the content cannot be managed from the frontend.

### 6.1 `data-admc-manage` — Make Text Editable
Place on the HTML tag that renders the text (e.g. `<h2>`, `<p>`). Always paired with `data-admc-id`.

```php
<h3 class="ts-title"
    data-admc-manage="panel_speakers"
    data-admc-id="<?= $value['id'] ?>">
    <?= $value['input_name'] ?>
</h3>
```

---

### 6.2 `data-admc-image` — Make an Image Editable
Place on the **wrapper `<div>`** around the `<img>` tag — NOT on the `<img>` itself. Always paired with `data-admc-id`.

```php
<div class="speaker-img"
     data-admc-image="panel_speakers"
     data-admc-id="<?= $value['id'] ?>">
    <img class="img-fluid" src="<?= $value['image_1'] ?>" alt="">
</div>
```

---

### 6.3 `data-admc-tb` — Enable "Add New" for a List
Place on the **container element that wraps the entire `foreach` loop** for a `panel_` table.

```php
<div class="row" data-admc-tb="panel_speakers">
    <?php foreach ($speakers as $speaker): ?>
        <!-- speaker card -->
    <?php endforeach; ?>
</div>
```

---

### 6.4 `data-admc-tb` + `data-admc-tblink` — Enable "Add New" for Child Records
Used for managing `addition_` table records. Place on the container wrapping the child loop. Requires three attributes together.

```php
<div class="member-socials"
     data-admc-tb="addition_team_socials"
     data-admc-tbadd="panel_team_members"
     data-admc-tblink="<?= $member['hash_id'] ?>">
    <!-- social links loop here -->
</div>
```

| Attribute | Value |
|---|---|
| `data-admc-tb` | The `addition_` table name |
| `data-admc-tbadd` | The parent `panel_` table name |
| `data-admc-tblink` | The `hash_id` of the parent record |

---

## 7. AI / Code Block Edit System — CB Structure

This is how the AI reads and edits `.php` view files. Every editable section of a page **must** use this exact structure. Without it, the AI cannot locate, read, or modify the content.

### 7.1 Structure Anatomy

```php
<div data-cbsection="cb1">
    <?php/*##cb1o##*/>

    /* Section Start */
    <?php/*##cbcode_56761o##*/>
    <div data-cbcodesection="cbcode_56761">
        <section>
            <h1>Editable Content Goes Here</h1>
        </section>
    </div>
    <?php/*##cbcode_56761c##*/>
    /* Section End */

    /* Second Section Start */
    <?php/*##cbcode_56762o##*/>
    <div data-cbcodesection="cbcode_56762">
        <section>
            <h1>More Editable Content</h1>
        </section>
    </div>
    <?php/*##cbcode_56762c##*/>
    /* Second Section End */

    <?php/*##cb1c##*/>
</div>
```

### 7.2 What Each Part Does

| Element | Purpose |
|---|---|
| `<div data-cbsection="cb1">` | Declares the main editable zone |
| `<?php/*##cb1o##*/>` | GPS start marker — AI begins reading here |
| `<?php/*##cbcode_56761o##*/>` | Opens a unique editable block (ID must be unique per file) |
| `<div data-cbcodesection="cbcode_56761">` | The container holding the actual HTML content |
| `<?php/*##cbcode_56761c##*/>` | Closes the editable block |
| `<?php/*##cb1c##*/>` | GPS end marker — AI stops reading here |

> ⚠️ **IDs must be unique.** Never reuse a block ID like `56761` twice in the same file — the AI will overwrite the wrong block.
> ⚠️ **Never delete the PHP comments** (`<?php/*...*/>`). They are invisible in the browser but are required navigation markers for the AI.

### 7.3 Full Page Example

```php
<?php
$page_title = "Home";
include("include/header.php");
?>

<div class="template-home-wrapper">
    <div class="page-content-home-page">
        <div data-elementor-type="wp-page" class="elementor">

            <div data-cbsection="cb1">
                <?php/*##cb1o##*/>

                <?php/*##cbcode_56761o##*/>
                <div data-cbcodesection="cbcode_56761">
                    <section class="elementor-section">
                        <h1>Welcome to the Home Page</h1>
                    </section>
                </div>
                <?php/*##cbcode_56761c##*/>

                <?php/*##cbcode_56762o##*/>
                <div data-cbcodesection="cbcode_56762">
                    <section class="elementor-section">
                        <h1>Second Section</h1>
                    </section>
                </div>
                <?php/*##cbcode_56762c##*/>

                <?php/*##cb1c##*/>
            </div>

        </div>
    </div>
</div>

<?php include("include/footer.php") ?>
```

---

## 8. Theming System

All code must follow the site's theme. The `$theme` variable is globally available (injected by `index.php`) and sourced from `website_status` in the database.

```php
// Default theme values (if not overridden by DB)
$theme = [
    "primary"    => "#3B82F6",
    "secondary"  => "#1e293b",   // Footer background
    "accent"     => "#F2A900",
    "background" => "#FDFCF8",
    "surface"    => "#ffffff"
];
```

Use these variables for all inline styles or theme-dependent values. Do not hard-code hex colors.

---

## 9. Quick Reference — Common Gotchas

| Mistake | Correct Approach |
|---|---|
| Raw SQL (`SELECT * FROM...`) | Use `selectContent()` only |
| Query inside a `foreach` loop | Pre-index with a single query before the loop |
| `data-admc-image` on `<img>` tag | Place on the **wrapper div**, not the img |
| Duplicate `cbcode_` IDs in one file | Every block needs a unique numeric ID |
| Deleting `<?php/*##...*/?>` comments | These are required AI markers — never remove them |
| Both `image_1` and `image_2` in same table | Use one or the other, never both |
| Hardcoding navigation links | Navigation is DB-driven via `panel_pages` |
| Using `settings_` table without `[0]` | Always append `[0]` for single-row settings tables |
