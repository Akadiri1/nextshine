<?php
/**
 * Services: the section header, the service cards, and the detail-page fields
 * served at /services/{hash_id}/{input_slug}.
 */

return new class {
    public function up($migration) {
        $migration->table('settings_home_services', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_label')->default('');
            $table->string('input_title')->default('');
            $table->text('text_subtitle')->nullable();
            $table->admcColumns();
        });

        $migration->table('panel_services', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_icon')->default('');
            $table->string('input_badge')->default('');
            $table->string('input_title')->default('');
            $table->text('text_description')->nullable();
            $table->longText('text_full_description')->nullable();
            $table->longText('text_whats_included')->nullable();
            $table->string('input_duration')->default('');
            $table->string('input_starting_price')->default('');
            $table->string('input_cta_text')->default('Get a Free Quote');
            $table->string('input_cta_url')->default('#contact');
            $table->string('input_meta_title')->default('');
            $table->text('text_meta_description')->nullable();
            $table->string('input_price_tag')->default('');
            $table->string('input_link_text')->default('');
            $table->string('input_link_url')->default('');
            $table->string('input_order')->default('0');
            $table->string('input_slug')->default('');
            $table->string('bgcolor_card_start', 100)->default('');
            $table->string('bgcolor_card_end', 100)->default('');
            $table->text('image_1')->nullable();
            $table->admcColumns();
        });
    }

    public function down($migration) {
        $migration->dropTable('panel_services');
        $migration->dropTable('settings_home_services');
    }
};
