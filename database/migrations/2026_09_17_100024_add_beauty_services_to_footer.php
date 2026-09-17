<?php
/**
 * The footer's Services column covers both divisions: "Cleaning Services"
 * (panel_services, as before) and a new "Beauty Services" column listing the
 * Beauty page's styles and hair shop (panel_beauty_services and
 * panel_beauty_products). This adds the new column's editable heading.
 *
 * The existing heading is only renamed while it still reads "Services".
 */

return new class {
    public function up($migration) {
        $migration->table('settings_home_footer', function ($table) {
            $table->string('input_beauty_services_title')->default('');
        });

        $migration->update('settings_home_footer', ['input_services_title' => 'Cleaning Services'], ['hash_id' => '10011', 'input_services_title' => 'Services']);
        $migration->update('settings_home_footer', ['input_beauty_services_title' => 'Beauty Services'], ['hash_id' => '10011', 'input_beauty_services_title' => '']);
    }

    public function down($migration) {
        $migration->update('settings_home_footer', ['input_services_title' => 'Services'], ['hash_id' => '10011', 'input_services_title' => 'Cleaning Services']);

        $migration->table('settings_home_footer', function ($table) {
            $table->dropColumn('input_beauty_services_title');
        });
    }
};
