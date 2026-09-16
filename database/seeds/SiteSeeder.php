<?php
use App\Migrator\Seeder;

/**
 * Site identity, favicon, brand colours and allowed hosts.
 *
 * Mail credentials are deliberately left blank: set them in the admin panel,
 * never in version control.
 */
class SiteSeeder extends Seeder {
    public function run() {
        $this->insertIfEmpty('settings_website_info', [
            'hash_id'                      => '10000',
            'input_name'                   => 'NextShine Cleaning',
            'input_email'                  => 'hello@nextshinegroup.co.uk',
            'input_phone_number'           => '+44 (0)7344 225808',
            'input_whatsapp_number'        => '447344225808',
            'input_address'                => 'Edinburgh & Surrounding Areas, Scotland',
            'text_description'             => 'Professional cleaning services for landlords, letting agents, and businesses across Edinburgh and surrounding areas.',
            'input_email_from'             => 'hello@nextshinegroup.co.uk',
            'input_email_password'         => '',
            'input_email_smtp_host'        => '',
            'input_email_smtp_port'        => '',
            'input_email_smtp_secure_type' => '',
        ]);

        $this->insertIfEmpty('read_favicon', [
            'hash_id' => '60001',
            'image_1' => '',
        ]);

        $this->insertIfEmpty('settings_site_colors', [
            'hash_id'                => '10001',
            'bgcolor_primary'        => '#00878A',
            'bgcolor_primary_light'  => '#00B4B7',
            'bgcolor_primary_pale'   => '#E0F5F5',
            'bgcolor_secondary'      => '#1A335C',
            'bgcolor_secondary_dark' => '#111f3a',
            'bgcolor_page'           => '#FFFFFF',
            'bgcolor_surface'        => '#F8FAFC',
            'bgcolor_surface_alt'    => '#F0F4F8',
            'textcolor_body'         => '#1A1A2E',
            'textcolor_muted'        => '#6B7280',
            'textcolor_dark'         => '#374151',
        ]);

        // Add the live domain here (or in the admin) before deploying.
        $this->insertMissing('panel_allowed_headers', [
            ['input_name' => 'localhost'],
            ['input_name' => 'nextshine.local'],
        ], 'input_name');
    }
}
