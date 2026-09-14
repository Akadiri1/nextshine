<?php
/**
 * ADMC platform tables.
 *
 * The admin panel and the /mck_ext session bridge read these. Their shape is
 * owned by the ADMC service, so they are mirrored here as-is rather than
 * following the site's content-table conventions.
 */

return new class {
    public function up($migration) {
        $migration->table('admin', function ($table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('hash');
            $table->string('hash_id');
            $table->string('position')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->text('image_2')->nullable();
            $table->text('image_1')->nullable();
            $table->time('time_created');
            $table->date('date_created');
            $table->dateTime('last_login')->nullable();
            $table->dateTime('last_logout')->nullable();
            $table->string('login_status')->nullable();
            $table->string('level')->nullable();
            $table->string('verification')->nullable();
            $table->string('profile_status')->nullable();
            $table->string('user_status')->nullable();
            $table->integer('defaulted')->nullable();
            $table->string('created_by')->nullable();
        });

        $migration->table('admin_auth', function ($table) {
            $table->id();
            $table->string('auth', 225)->nullable();
            $table->string('created_by', 225)->nullable();
            $table->string('used_by', 225)->nullable();
            $table->date('date_created')->nullable();
            $table->time('time_created')->nullable();
            $table->string('hash_id', 225)->nullable();
        });

        // Gallery images for image_2 columns, linked by asset_hash_id = parent.hash_id.
        $migration->table('images', function ($table) {
            $table->id();
            $table->string('image_hash_id', 225)->nullable();
            $table->string('asset_hash_id')->nullable();
            $table->text('image_1')->nullable();
            $table->date('date_created')->nullable();
            $table->time('time_created')->nullable();
            $table->string('created_by')->nullable();
            $table->string('hash_id', 225)->nullable();
        });
    }

    public function down($migration) {
        $migration->dropTable('images');
        $migration->dropTable('admin_auth');
        $migration->dropTable('admin');
    }
};
