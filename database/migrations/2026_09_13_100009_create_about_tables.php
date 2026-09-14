<?php
/**
 * "Our story" section and its bullet list.
 */

return new class {
    public function up($migration) {
        $migration->table('settings_home_about', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_label')->default('');
            $table->string('input_title')->default('');
            $table->text('text_quote')->nullable();
            $table->string('input_quote_author')->default('');
            $table->text('text_description')->nullable();
            $table->string('input_floatcard_title')->default('');
            $table->string('input_floatcard_label')->default('');
            $table->string('input_cta_primary_text')->default('');
            $table->string('input_cta_primary_url')->default('');
            $table->string('input_cta_secondary_text')->default('');
            $table->string('input_cta_secondary_url')->default('');
            $table->text('image_1')->nullable();
            $table->admcColumns();
        });

        $migration->table('panel_about_bullets', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_text')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });
    }

    public function down($migration) {
        $migration->dropTable('panel_about_bullets');
        $migration->dropTable('settings_home_about');
    }
};
