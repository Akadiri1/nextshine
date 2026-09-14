<?php
/**
 * ---------------------------------------------------------------------------
 * CONFIG TEMPLATE
 * ---------------------------------------------------------------------------
 * Copy this file to .env/config.php and fill in the values for the machine you
 * are deploying to. config.php is git-ignored and must never be committed.
 *
 *     cp .env/config.example.php .env/config.php
 *
 * core/autoload.php parses the putenv() lines below into PHP constants, and
 * www/index.php includes this file so getenv() also works. Every value must be
 * a plain single-quoted string on one line, or the parser will skip it.
 * ---------------------------------------------------------------------------
 */

# --- Database ---------------------------------------------------------------
putenv('DB_HOST=localhost');
putenv('DB_USER=your_db_user');
putenv('DB_PASSWORD=your_db_password');
putenv('DB_NAME=your_db_name');

# --- Application ------------------------------------------------------------
putenv('APP_NAME=NextShine Cleaning');

// The domain the site is served from, with no scheme and no trailing slash.
putenv('APP_DOMAIN=nextshinegroup.co.uk');

// NextShine Beauty's subdomain, served from this same codebase. Point its DNS
// and vhost at the same DocumentRoot. Remove the line to switch Beauty off.
putenv('BEAUTY_DOMAIN=beauty.nextshinegroup.co.uk');

/**
 * PRODUCTION_MODE
 *
 * 'true' on any public server. It suppresses detailed error output.
 * Anything other than 'false' is treated as production, so a missing or
 * broken config can never leak connection details to visitors.
 */
putenv('PRODUCTION_MODE=true');

# --- Admin ------------------------------------------------------------------
// Identifier used by the ADMC admin tooling (the "admc" cookie, and the
// dashboard at <ADMC_USERNAME>.admc.dev).
putenv('ADMC_USERNAME=nextshine');
