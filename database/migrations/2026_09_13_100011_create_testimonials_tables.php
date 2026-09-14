<?php
/**
 * Reviews section header and the reviews themselves.
 */

return new class {
    public function up($migration) {
        $migration->table('settings_home_testimonials', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_label')->default('');
            $table->string('input_title')->default('');
            $table->text('text_subtitle')->nullable();
            $table->string('input_google_score', 100)->default('');
            $table->string('input_google_text')->default('');
            $table->admcColumns();
        });

        $migration->table('panel_testimonials', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->text('text_review')->nullable();
            $table->string('input_author_name')->default('');
            $table->string('input_author_role')->default('');
            $table->string('input_author_initials')->default('');
            $table->string('input_source')->default('');
            $table->string('bgcolor_avatar', 100)->default('#00878A');
            $table->string('input_order')->default('0');
            $table->text('image_1')->nullable();
            $table->admcColumns();
        });
    }

    public function down($migration) {
        $migration->dropTable('panel_testimonials');
        $migration->dropTable('settings_home_testimonials');
    }
};
