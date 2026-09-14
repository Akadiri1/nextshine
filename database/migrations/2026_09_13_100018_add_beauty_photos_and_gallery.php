<?php
/**
 * Photos for the beauty site: a hero photo, one per service and hair shop
 * product, and a gallery of past work. All uploadable through live edit.
 *
 * The rows seeded before this migration are pointed at the placeholder stock
 * photos in www/assets/images/beauty/ (see CREDITS.md there). Replace them
 * through live edit once the client supplies real photos.
 */

return new class {
    public function up($migration) {
        foreach (['settings_beauty_hero', 'panel_beauty_services', 'panel_beauty_products'] as $name) {
            $migration->table($name, function ($table) {
                $table->text('image_1')->nullable();
            });
        }

        $migration->table('settings_beauty_gallery', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_label')->default('');
            $table->string('input_title')->default('');
            $table->text('text_subtitle')->nullable();
            $table->admcColumns();
        });

        $migration->table('panel_beauty_gallery', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_caption')->default('');
            $table->text('image_1')->nullable();
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });

        $photos = '/assets/images/beauty/';

        $migration->update('settings_beauty_hero', ['image_1' => $photos . 'hero.jpg'], ['hash_id' => '80002']);

        foreach ([
            '82001' => 'service-braids.jpg',
            '82002' => 'service-ghana-weaving.jpg',
            '82003' => 'service-cornrows.jpg',
            '82004' => 'service-sew-ins.jpg',
            '82005' => 'service-crochet.jpg',
        ] as $hash => $file) {
            $migration->update('panel_beauty_services', ['image_1' => $photos . $file], ['hash_id' => $hash]);
        }

        foreach ([
            '84001' => 'product-bundles.jpg',
            '84002' => 'product-wigs.jpg',
            '84003' => 'product-packages.jpg',
        ] as $hash => $file) {
            $migration->update('panel_beauty_products', ['image_1' => $photos . $file], ['hash_id' => $hash]);
        }
    }

    public function down($migration) {
        $migration->dropTable('panel_beauty_gallery');
        $migration->dropTable('settings_beauty_gallery');

        foreach (['settings_beauty_hero', 'panel_beauty_services', 'panel_beauty_products'] as $name) {
            $migration->table($name, function ($table) {
                $table->dropColumn('image_1');
            });
        }
    }
};
