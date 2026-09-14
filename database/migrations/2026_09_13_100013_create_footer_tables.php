<?php
/**
 * Footer copy, grouped links (input_group: company | legal) and social buttons.
 * The Services column is built from panel_services, not from these links.
 */

return new class {
    public function up($migration) {
        $migration->table('settings_home_footer', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->text('text_description')->nullable();
            $table->string('input_copyright')->default('');
            $table->string('input_registration_number')->default('');
            $table->string('input_services_title')->default('');
            $table->string('input_company_title')->default('');
            $table->string('input_contact_title')->default('');
            $table->string('input_whatsapp_text')->default('');
            $table->string('input_hours_text')->default('');
            $table->admcColumns();
        });

        $migration->table('panel_footer_links', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_name')->default('');
            $table->string('input_link')->default('');
            $table->string('input_group')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });

        $migration->table('panel_footer_socials', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_icon')->default('');
            $table->string('input_label')->default('');
            $table->string('input_link')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });
    }

    public function down($migration) {
        $migration->dropTable('panel_footer_socials');
        $migration->dropTable('panel_footer_links');
        $migration->dropTable('settings_home_footer');
    }
};
