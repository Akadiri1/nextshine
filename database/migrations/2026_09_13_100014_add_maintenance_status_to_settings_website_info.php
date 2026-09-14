<?php
/**
 * Maintenance switch. Set to 1 from the admin panel and every public page
 * shows views/maintenance.php; logged-in admins still see the site.
 */

return new class {
    public function up($migration) {
        $migration->table('settings_website_info', function ($table) {
            $table->integer('maintenance_status')->default(0);
        });
    }

    public function down($migration) {
        $migration->table('settings_website_info', function ($table) {
            $table->dropColumn('maintenance_status');
        });
    }
};
