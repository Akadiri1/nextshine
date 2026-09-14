<?php
/**
 * Site-wide settings: identity, contact, mail, favicon, brand colours and the
 * hosts allowed to call the admin CRUD endpoints.
 */

return new class {
    public function up($migration) {
        $migration->table('settings_website_info', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_name')->default('');
            $table->string('input_email')->nullable();
            $table->string('input_phone_number')->nullable();
            $table->string('input_whatsapp_number', 100)->default('');
            $table->string('input_address')->default('');
            $table->string('input_facebook')->default('');
            $table->string('input_instagram')->default('');
            $table->string('input_linkedin')->default('');
            $table->string('input_twitter')->default('');
            $table->string('input_pinterest')->nullable();
            $table->text('image_1')->nullable();
            $table->string('input_image_width')->nullable();
            $table->text('text_description')->nullable();
            $table->string('input_day')->nullable();
            $table->string('input_time')->nullable();
            $table->text('input_seo_keywords')->nullable();
            $table->string('input_email_from')->nullable();
            $table->string('input_email_password')->nullable();
            $table->string('input_email_smtp_host')->nullable();
            $table->string('input_email_smtp_port')->nullable();
            $table->string('input_email_smtp_secure_type')->nullable();
            $table->admcColumns();
        });

        // read_ = view/edit only in the admin, no add or delete.
        $migration->table('read_favicon', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->text('image_1')->nullable();
            $table->admcColumns();
        });

        // Hosts permitted to call /add, /read, /put, /delete (v1/ajax).
        $migration->table('panel_allowed_headers', function ($table) {
            $table->id();
            $table->string('hash_id', 500)->nullable();
            $table->string('input_name', 225);
            $table->admcColumns();
        });

        // Brand colours. Emitted as CSS variables by views/includes/theme.php,
        // which the Tailwind palette reads, so an admin can recolour the site.
        $migration->table('settings_site_colors', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('bgcolor_primary', 100)->default('#00878A');
            $table->string('bgcolor_primary_light', 100)->default('#00B4B7');
            $table->string('bgcolor_primary_pale', 100)->default('#E0F5F5');
            $table->string('bgcolor_secondary', 100)->default('#1A335C');
            $table->string('bgcolor_secondary_dark', 100)->default('#111f3a');
            $table->string('bgcolor_page', 100)->default('#FFFFFF');
            $table->string('bgcolor_surface', 100)->default('#F8FAFC');
            $table->string('bgcolor_surface_alt', 100)->default('#F0F4F8');
            $table->string('textcolor_body', 100)->default('#1A1A2E');
            $table->string('textcolor_muted', 100)->default('#6B7280');
            $table->string('textcolor_dark', 100)->default('#374151');
            $table->admcColumns();
        });
    }

    public function down($migration) {
        $migration->dropTable('settings_site_colors');
        $migration->dropTable('panel_allowed_headers');
        $migration->dropTable('read_favicon');
        $migration->dropTable('settings_website_info');
    }
};
