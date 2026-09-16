<?php
/**
 * NextShine Beauty moves from its own subdomain to a page on the main site,
 * /beauty, keeping its design and content. Its menu links become ordinary
 * site paths: Home "/", Cleaning "/cleaning" and Beauty "/beauty", which the
 * beauty header marks as the current page.
 *
 * Only links still holding their original value are changed, so a link the
 * client has already edited is left alone.
 */

return new class {
    // hash_id => [link on the subdomain, link on the /beauty page]
    private $links = [
        '89001' => ['{main}/',         '/'],
        '89002' => ['{main}/cleaning', '/cleaning'],
        '89003' => ['/',               '/beauty'],
    ];

    public function up($migration) {
        $this->swap($migration, 0, 1);
    }

    public function down($migration) {
        $this->swap($migration, 1, 0);
    }

    private function swap($migration, $from, $to) {
        foreach ($this->links as $hash => $link) {
            $migration->update(
                'panel_beauty_nav',
                ['input_link' => $link[$to]],
                ['hash_id' => $hash, 'input_link' => $link[$from]]
            );
        }
    }
};
