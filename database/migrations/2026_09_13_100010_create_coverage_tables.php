<?php
/**
 * Coverage section header and the area tags (input_group: city | surrounding).
 */

return new class {
    public function up($migration) {
        $migration->table('settings_home_coverage', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_label')->default('');
            $table->string('input_title')->default('');
            $table->text('text_description')->nullable();
            $table->string('input_city_label')->default('');
            $table->string('input_surrounding_label')->default('');
            $table->string('input_footer_note')->default('');
            $table->admcColumns();
        });

        $migration->table('panel_coverage_areas', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_name')->default('');
            $table->string('input_group')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });
    }

    public function down($migration) {
        $migration->dropTable('panel_coverage_areas');
        $migration->dropTable('settings_home_coverage');
    }
};
