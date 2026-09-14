<?php
/**
 * NextShine Beauty, served on its own subdomain (BEAUTY_DOMAIN) from this
 * codebase. A separate business with its own look; see v1/views/beauty/.
 */

return new class {
    public function up($migration) {
        // Identity, top bar, social links and footer copy.
        $migration->table('settings_beauty_site', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_meta_title')->default('');
            $table->text('text_meta_description')->nullable();
            $table->string('input_topbar_text')->default('');
            $table->string('input_topbar_highlight')->default('');
            $table->string('input_logo_sub')->default('');
            $table->string('input_nav_cta')->default('');
            // Booking requests are emailed here; blank falls back to the main site email.
            $table->string('input_email')->default('');
            $table->string('input_instagram_url')->default('');
            $table->string('input_whatsapp_number', 100)->default('');
            $table->string('input_social_text')->default('');
            $table->string('input_footer_name')->default('');
            $table->string('input_footer_tagline')->default('');
            $table->string('input_copyright')->default('');
            $table->string('input_registration_text')->default('');
            $table->string('input_company_number')->default('');
            $table->admcColumns();
        });

        $migration->table('settings_beauty_hero', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_eyebrow')->default('');
            $table->string('input_headline_1')->default('');
            $table->string('input_headline_2')->default('');
            $table->string('input_headline_3')->default('');
            $table->text('text_description')->nullable();
            $table->string('input_cta_primary_text')->default('');
            $table->string('input_cta_secondary_text')->default('');
            $table->string('input_badge_1_value', 100)->default('');
            $table->text('text_badge_1_label')->nullable();
            $table->string('input_badge_2_value', 100)->default('');
            $table->text('text_badge_2_label')->nullable();
            $table->string('input_badge_3_value', 100)->default('');
            $table->text('text_badge_3_label')->nullable();
            $table->admcColumns();
        });

        // The strip of four selling points under the hero.
        $migration->table('panel_beauty_highlights', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_icon')->default('');
            $table->string('input_title')->default('');
            $table->text('text_description')->nullable();
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });

        $migration->table('settings_beauty_services', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_label')->default('');
            $table->string('input_title')->default('');
            $table->text('text_subtitle')->nullable();
            $table->string('input_enquire_text')->default('');
            $table->admcColumns();
        });

        // A blank input_price shows the "Enquire for Pricing" link instead.
        $migration->table('panel_beauty_services', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_title')->default('');
            $table->string('input_price')->default('');
            $table->text('text_description')->nullable();
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });

        // Discount and pricing notes under the services grid.
        $migration->table('panel_beauty_notes', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_highlight')->default('');
            $table->string('input_text')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });

        $migration->table('settings_beauty_shop', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_label')->default('');
            $table->string('input_title')->default('');
            $table->text('text_subtitle')->nullable();
            $table->admcColumns();
        });

        $migration->table('panel_beauty_products', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_icon')->default('');
            $table->string('input_title')->default('');
            $table->text('text_description')->nullable();
            $table->string('input_link_text')->default('');
            $table->string('input_order')->default('0');
            $table->text('add_beauty_product_features')->nullable();
            $table->admcColumns();
        });

        $migration->table('addition_beauty_product_features', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_feature')->default('');
            $table->string('tb')->default('');
            $table->string('tb_link')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });

        $migration->table('settings_beauty_booking', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_label')->default('');
            $table->string('input_title')->default('');
            $table->text('text_subtitle')->nullable();
            $table->text('text_notes')->nullable(); // one note per line
            $table->string('input_form_title')->default('');
            $table->string('input_submit_text')->default('');
            $table->string('input_form_note')->default('');
            $table->string('input_success_message')->default('');
            $table->admcColumns();
        });

        // Instagram / WhatsApp / phone buttons beside the booking form.
        $migration->table('panel_beauty_channels', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_icon')->default('');
            $table->string('input_title')->default('');
            $table->string('input_subtitle')->default('');
            $table->string('input_link')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });

        $migration->table('selection_beauty_booking_services', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_name')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });
    }

    public function down($migration) {
        foreach ([
            'selection_beauty_booking_services', 'panel_beauty_channels', 'settings_beauty_booking',
            'addition_beauty_product_features', 'panel_beauty_products', 'settings_beauty_shop',
            'panel_beauty_notes', 'panel_beauty_services', 'settings_beauty_services',
            'panel_beauty_highlights', 'settings_beauty_hero', 'settings_beauty_site',
        ] as $table) {
            $migration->dropTable($table);
        }
    }
};
