<?php
/**
 * Pricing: fixed end-of-tenancy table, hourly cards, and the per-card feature
 * list (addition_ rows linked to panel_pricing_hourly via tb_link = hash_id).
 */

return new class {
    public function up($migration) {
        $migration->table('settings_home_pricing', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_label')->default('');
            $table->string('input_title')->default('');
            $table->text('text_subtitle')->nullable();
            $table->string('input_tab_eot')->default('');
            $table->string('input_tab_hourly')->default('');
            $table->text('text_eot_note')->nullable();
            $table->string('input_agent_note')->default('');
            $table->admcColumns();
        });

        $migration->table('panel_pricing_eot', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_property')->default('');
            $table->string('input_detail')->default('');
            $table->string('input_duration')->default('');
            $table->string('input_price')->default('');
            $table->string('input_market')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });

        $migration->table('panel_pricing_hourly', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_icon')->default('');
            $table->string('input_title')->default('');
            $table->string('input_price')->default('');
            $table->string('input_per')->default('');
            $table->string('input_minimum')->default('');
            $table->string('input_badge')->default('');
            $table->string('input_cta_text')->default('');
            $table->string('input_cta_url')->default('');
            $table->string('input_featured')->default('no');
            $table->string('input_order')->default('0');
            $table->text('add_pricing_features')->nullable();
            $table->admcColumns();
        });

        $migration->table('addition_pricing_features', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_feature')->default('');
            $table->string('tb')->default('');
            $table->string('tb_link')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });
    }

    public function down($migration) {
        $migration->dropTable('addition_pricing_features');
        $migration->dropTable('panel_pricing_hourly');
        $migration->dropTable('panel_pricing_eot');
        $migration->dropTable('settings_home_pricing');
    }
};
