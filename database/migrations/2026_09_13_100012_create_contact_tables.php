<?php
/**
 * Contact section, plus the admin-editable option lists for the quote form's
 * "Service required" and "Property size" dropdowns.
 */

return new class {
    public function up($migration) {
        $migration->table('settings_home_contact', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_label')->default('');
            $table->string('input_title')->default('');
            $table->text('text_subtitle')->nullable();
            $table->string('input_phone_label')->default('');
            $table->string('input_email_label')->default('');
            $table->string('input_response_label')->default('');
            $table->string('input_response_value')->default('');
            $table->string('input_coverage_label')->default('');
            $table->string('input_coverage_value')->default('');
            $table->admcColumns();
        });

        $migration->table('selection_form_services', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_name')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });

        $migration->table('selection_form_property_sizes', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_name')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });
    }

    public function down($migration) {
        $migration->dropTable('selection_form_property_sizes');
        $migration->dropTable('selection_form_services');
        $migration->dropTable('settings_home_contact');
    }
};
