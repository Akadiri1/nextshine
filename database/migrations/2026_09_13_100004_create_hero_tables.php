<?php
/**
 * Home hero and the trust strip beneath it.
 */

return new class {
    public function up($migration) {
        $migration->table('settings_home_hero', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_badge_text')->default('');
            $table->string('input_headline_1')->default('');
            $table->string('input_headline_2')->default('');
            $table->string('input_headline_3')->default('');
            $table->text('text_description')->nullable();
            $table->string('input_cta_primary')->default('');
            $table->string('input_cta_secondary')->default('');
            $table->string('input_stat_1_value', 100)->default('');
            $table->string('input_stat_1_label')->default('');
            $table->string('input_stat_2_value', 100)->default('');
            $table->string('input_stat_2_label')->default('');
            $table->string('input_stat_3_value', 100)->default('');
            $table->string('input_stat_3_label')->default('');
            $table->string('input_card_title')->default('');
            $table->text('image_1')->nullable();
            $table->admcColumns();
        });

        $migration->table('panel_trust_items', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_icon')->default('');
            $table->string('input_text')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });
    }

    public function down($migration) {
        $migration->dropTable('panel_trust_items');
        $migration->dropTable('settings_home_hero');
    }
};
