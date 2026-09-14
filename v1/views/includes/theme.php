<?php
/**
 * Brand colours from settings_site_colors, emitted as the CSS variables the
 * Tailwind palette reads (tailwind.config.js). Admins recolour the site from
 * the panel; no rebuild is needed.
 *
 * Any colour that is missing or not a valid hex value is skipped, so the
 * default from src/input.css stays in force instead of breaking the page.
 */
$themeRow = selectContent($conn, "settings_site_colors", ["visibility" => "show"]);
$themeRow = $themeRow[0] ?? [];

$themeColumns = [
    'navy'       => 'bgcolor_secondary',
    'navy-dark'  => 'bgcolor_secondary_dark',
    'teal'       => 'bgcolor_primary',
    'teal-light' => 'bgcolor_primary_light',
    'teal-pale'  => 'bgcolor_primary_pale',
    'white'      => 'bgcolor_page',
    'off-white'  => 'bgcolor_surface',
    'grey-light' => 'bgcolor_surface_alt',
    'grey'       => 'textcolor_muted',
    'grey-dark'  => 'textcolor_dark',
    'ink'        => 'textcolor_body',
];

$themeCss = '';
foreach ($themeColumns as $variable => $column) {
    $hex = ltrim(trim($themeRow[$column] ?? ''), '#');

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    if (!preg_match('/^[0-9a-f]{6}$/i', $hex)) {
        continue;
    }

    $themeCss .= sprintf(
        '--color-%s:%d %d %d;',
        $variable,
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2))
    );
}
?>
<?php if ($themeCss !== ''): ?>
  <style>:root{<?= $themeCss ?>}</style>
<?php endif; ?>
