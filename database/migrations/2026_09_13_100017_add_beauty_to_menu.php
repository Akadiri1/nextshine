<?php
/**
 * Adds "Beauty" to the cleaning site's menu, between Cleaning and Reviews.
 * The link is /beauty, which redirects to BEAUTY_DOMAIN, so the stored value
 * works in every environment.
 */

return new class {
    public function up($migration) {
        $migration->insert('panel_home_nav', [
            'hash_id'      => '50007',
            'input_name'   => 'Beauty',
            'input_link'   => '/beauty',
            'input_order'  => '2',
            'visibility'   => 'show',
            'date_created' => date('Y-m-d'),
            'time_created' => date('H:i:s'),
            'created_by'   => 'system',
        ]);
    }

    public function down($migration) {
        $migration->delete('panel_home_nav', ['hash_id' => '50007']);
    }
};
