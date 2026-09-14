<?php
/**
 * Beauty site menus, editable through live edit like the cleaning site's:
 * the links shared by the top bar, navbar and mobile menu, and the footer
 * links. Until now these were fixed in the beauty header and footer.
 *
 * "{main}" in a link stands for the main NextShine site (APP_DOMAIN), so the
 * stored links work in every environment.
 */

return new class {
    private $links = [
        'panel_beauty_nav' => [
            ['89001', 'Home',     '{main}/'],
            ['89002', 'Cleaning', '{main}/cleaning'],
            ['89003', 'Beauty',   '/'],
            ['89004', 'Contact',  '#booking'],
        ],
        'panel_beauty_footer_links' => [
            ['89101', 'Privacy Policy', '#'],
            ['89102', 'Terms',          '#'],
        ],
    ];

    public function up($migration) {
        foreach ($this->links as $name => $rows) {
            $migration->table($name, function ($table) {
                $table->id();
                $table->string('hash_id');
                $table->string('input_name')->default('');
                $table->string('input_link')->default('');
                $table->string('input_order')->default('0');
                $table->admcColumns();
            });

            foreach ($rows as $i => [$hash, $label, $link]) {
                $migration->insert($name, [
                    'hash_id'      => $hash,
                    'input_name'   => $label,
                    'input_link'   => $link,
                    'input_order'  => (string) ($i + 1),
                    'visibility'   => 'show',
                    'date_created' => date('Y-m-d'),
                    'time_created' => date('H:i:s'),
                    'created_by'   => 'system',
                ]);
            }
        }
    }

    public function down($migration) {
        $migration->dropTable('panel_beauty_footer_links');
        $migration->dropTable('panel_beauty_nav');
    }
};
