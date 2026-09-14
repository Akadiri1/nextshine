<?php
/**
 * Beauty is a separate business, not a cleaning page, so it moves out of the
 * page links and becomes a button beside "Get a Quote". The button's text,
 * icon and link are editable through live edit.
 *
 * The old menu item is hidden rather than deleted, so an admin can show it
 * again from the menu table if the client changes their mind.
 */

return new class {
    public function up($migration) {
        $migration->update('panel_home_nav', ['visibility' => 'hide'], ['hash_id' => '50007', 'visibility' => 'show']);

        $migration->table('settings_home_nav_button', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_text')->default('');
            $table->string('input_icon')->default('');
            $table->string('input_link')->default('');
            $table->admcColumns();
        });

        $migration->insert('settings_home_nav_button', [
            'hash_id'      => '51001',
            'input_text'   => 'Beauty',
            'input_icon'   => 'fa-solid fa-spa',
            'input_link'   => '/beauty',
            'visibility'   => 'show',
            'date_created' => date('Y-m-d'),
            'time_created' => date('H:i:s'),
            'created_by'   => 'system',
        ]);
    }

    public function down($migration) {
        $migration->dropTable('settings_home_nav_button');
        $migration->update('panel_home_nav', ['visibility' => 'show'], ['hash_id' => '50007', 'visibility' => 'hide']);
    }
};
