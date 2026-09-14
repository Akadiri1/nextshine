<?php
/**
 * Beauty site icons: swap the emoji and ✦ glyphs from the supplied HTML for
 * Font Awesome classes, the icon set the cleaning site already uses.
 *
 * Only rows still holding the original value are changed, so an icon the
 * client has already picked through live edit is left alone.
 */

return new class {
    // hash_id => [Font Awesome class, original emoji]
    private $icons = [
        'panel_beauty_highlights' => [
            '81001' => ['fa-solid fa-award', '✦'],
            '81002' => ['fa-solid fa-location-dot', '✦'],
            '81003' => ['fa-solid fa-bag-shopping', '✦'],
            '81004' => ['fa-solid fa-gift', '✦'],
        ],
        'panel_beauty_products' => [
            '84001' => ['fa-solid fa-box-open', '🪢'],
            '84002' => ['fa-solid fa-crown', '👑'],
            '84003' => ['fa-solid fa-wand-magic-sparkles', '✨'],
        ],
        'panel_beauty_channels' => [
            '86001' => ['fa-brands fa-instagram', '📸'],
            '86002' => ['fa-brands fa-whatsapp', '💬'],
            '86003' => ['fa-solid fa-phone', '📞'],
        ],
    ];

    public function up($migration) {
        $this->swap($migration, 1, 0);
    }

    public function down($migration) {
        $this->swap($migration, 0, 1);
    }

    private function swap($migration, $from, $to) {
        foreach ($this->icons as $table => $rows) {
            foreach ($rows as $hash => $icon) {
                $migration->update(
                    $table,
                    ['input_icon' => $icon[$to]],
                    ['hash_id' => $hash, 'input_icon' => $icon[$from]]
                );
            }
        }
    }
};
