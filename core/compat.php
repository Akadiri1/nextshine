<?php
/**
 * ---------------------------------------------------------------------------
 * PHP COMPATIBILITY SHIM
 * ---------------------------------------------------------------------------
 * Development runs on PHP 8.x, but deployment targets have been seen on 7.x.
 * These string helpers arrived in PHP 8.0; without them the site fatals on
 * every request. Polyfilled here so the same source runs on both.
 *
 * Loaded by core/autoload.php (web) and framework/Autoloader.php (CLI), before
 * anything that might call them.
 *
 * Language-level features cannot be shimmed, so the codebase deliberately
 * avoids match expressions, the nullsafe operator, constructor promotion and
 * arrow functions on any request path.
 * ---------------------------------------------------------------------------
 */

if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle)
    {
        $haystack = (string) $haystack;
        $needle   = (string) $needle;
        return $needle === '' || strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle)
    {
        $haystack = (string) $haystack;
        $needle   = (string) $needle;
        if ($needle === '') {
            return true;
        }
        $len = strlen($needle);
        return $len <= strlen($haystack) && substr_compare($haystack, $needle, -$len) === 0;
    }
}

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle)
    {
        $needle = (string) $needle;
        return $needle === '' || strpos((string) $haystack, $needle) !== false;
    }
}

if (!function_exists('array_is_list')) {
    function array_is_list(array $array)
    {
        $i = 0;
        foreach ($array as $k => $_) {
            if ($k !== $i++) {
                return false;
            }
        }
        return true;
    }
}
