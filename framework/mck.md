# McKodev PHP Framework

This system provides a robust, developer-friendly way to manage your database schema and data using PHP. It is built to support the high scalability required for projects like **SchoolAfrik** and your **Web3/Blockchain** applications.

---

## 1. The CLI Tool (`mck`)

The `mck` script is your command center. Run it from the project root using `php mck [command]`.

### Migration Commands

- **`migrate`**: Checks the `migrations` table and runs any new files found in `database/migrations/`.
- **`migrate:rollback`**: Reverts the last "batch" of migrations, calling their `down()` methods.
- **`migrate:fresh`**: Wipes all tables and re-runs every migration. **Warning: This deletes all data**.
- **`migration:create [name]`**: Generates a timestamped migration file from a stub.

### Seeding Commands

- **`seed`**: Executes all seeder files in `database/seeds/`.
- **`seed [ClassName]`**: Runs only one specific seeder (e.g., `php mck seed UserSeeder`).
- **`seeder:create [Name]`**: Generates a new seeder class in the seeds directory.

### System Commands

- **`init`**: A specialized "factory reset" that wipes the DB, runs migrations, and executes custom logic defined in `ProjectInitializer.php`.

---

## 2. Schema Management (`Blueprint`)

The `Blueprint` class allows you to define tables using fluent PHP methods instead of raw SQL.

### Available Column Types

- **`id()`**: `INT AUTO_INCREMENT PRIMARY KEY`.
- **`string('name', 255)`**: `VARCHAR` column.
- **`text('bio')`** / **`longText('content')`**: Standard text types.
- **`integer('age')`** / **`bigInt('balance')`**: Numeric types.
- **`boolean('is_active')`**: `TINYINT(1)`.
- **`timestamps(type)`**:
  - `type 1`: Default `created_at` and `updated_at` (NULLable).
  - `type 2`: `time_created` and `date_created`.

### Modifiers & Indexes

- **`nullable()`**: Allows the column to store NULL values.
- **`default(value)`**: Sets a default value (e.g., `->default('active')`).
- **`unique()`**: Creates a unique index to prevent duplicate entries.
- **`after('column')`**: Positions the new column after an existing one.

### Schema Updates (Alter)

- **`renameColumn('old', 'new')`**: Renames an existing column.
- **`change()`**: Modifies an existing column's type or attributes.
- **`dropColumn('name')`**: Removes a column from the table.

---

## 3. Data Seeding (`Seeder`)

Seeders are used to populate your database with test data or mandatory system configurations.

### Using `upsert` (Industry Standard)

The `upsert` method is the preferred way to seed data because it is **idempotent**. It prevents duplicate rows if the seeder is run multiple times by updating existing records based on a unique key (like `id` or `email`).

```php
// Inside a Seeder class
foreach ($users as $user) {
    $this->upsert('users', $user, 'email'); // Uses 'email' to check for duplicates
}
```
