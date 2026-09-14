<?php
/**
 * Primary navigation. "Home" is fixed in the header; these are the items after it.
 */

return new class {
    public function up($migration) {
        $migration->table('panel_home_nav', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_name')->default('');
            $table->string('input_link')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });
    }

    public function down($migration) {
        $migration->dropTable('panel_home_nav');
    }
};
