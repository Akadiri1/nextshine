<?php
/**
 * "Why choose us" section header and its cards.
 */

return new class {
    public function up($migration) {
        $migration->table('settings_home_why', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_label')->default('');
            $table->string('input_title')->default('');
            $table->text('text_subtitle')->nullable();
            $table->admcColumns();
        });

        $migration->table('panel_why_us', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_icon')->default('');
            $table->string('input_title')->default('');
            $table->text('text_description')->nullable();
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });
    }

    public function down($migration) {
        $migration->dropTable('panel_why_us');
        $migration->dropTable('settings_home_why');
    }
};
