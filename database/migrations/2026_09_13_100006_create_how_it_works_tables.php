<?php
/**
 * "How it works" section header and its numbered steps.
 */

return new class {
    public function up($migration) {
        $migration->table('settings_home_how', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_label')->default('');
            $table->string('input_title')->default('');
            $table->text('text_subtitle')->nullable();
            $table->admcColumns();
        });

        $migration->table('panel_how_steps', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_step_number')->default('');
            $table->string('input_title')->default('');
            $table->text('text_description')->nullable();
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });
    }

    public function down($migration) {
        $migration->dropTable('panel_how_steps');
        $migration->dropTable('settings_home_how');
    }
};
