<?php
use App\Migrator\Seeder;

class FooterSeeder extends Seeder {
    public function run() {
        $this->insertIfEmpty('settings_home_footer', [
            'hash_id'                     => '10011',
            'text_description'            => 'NextShine Group offers professional cleaning and African hair styling services in Edinburgh. Fixed-price end-of-tenancy cleans, domestic and commercial cleaning, and expert hair styling. Family-run and fully insured.',
            'input_copyright'             => '© 2026 NextShine Group Ltd. All rights reserved. Company registered in Scotland.',
            'input_registration_number'   => '',
            'input_services_title'        => 'Cleaning Services',
            'input_beauty_services_title' => 'Beauty Services',
            'input_company_title'         => 'Company',
            'input_contact_title'         => 'Contact',
            'input_whatsapp_text'         => 'WhatsApp Available',
            'input_hours_text'            => 'Mon–Sat · 7am–7pm',
        ]);

        // The Services column renders from panel_services; these 'services'
        // rows are kept pointing at the matching detail pages for consistency.
        $this->insertMissing('panel_footer_links', [
            ['hash_id' => '38001', 'input_name' => 'End-of-Tenancy Clean', 'input_link' => '/services/30001/end-of-tenancy-clean',      'input_group' => 'services', 'input_order' => '1'],
            ['hash_id' => '38002', 'input_name' => 'Regular Domestic',     'input_link' => '/services/30002/regular-domestic-cleaning',  'input_group' => 'services', 'input_order' => '2'],
            ['hash_id' => '38003', 'input_name' => 'Office & Commercial',  'input_link' => '/services/30003/commercial-office-cleaning', 'input_group' => 'services', 'input_order' => '3'],
            ['hash_id' => '38004', 'input_name' => 'One-Off Deep Clean',   'input_link' => '/services/30004/one-off-deep-clean',         'input_group' => 'services', 'input_order' => '4'],
            ['hash_id' => '38005', 'input_name' => 'Post-Construction',    'input_link' => '/services/30005/post-construction-clean',    'input_group' => 'services', 'input_order' => '5'],
            ['hash_id' => '38006', 'input_name' => 'AirBnB Turnovers',     'input_link' => '/services/30006/airbnb-short-let-cleaning',  'input_group' => 'services', 'input_order' => '6'],
            // Company links follow the client's sample: Home, Cleaning, Beauty, Contact.
            ['hash_id' => '38015', 'input_name' => 'Home',                 'input_link' => '/',          'input_group' => 'company', 'input_order' => '1'],
            ['hash_id' => '38016', 'input_name' => 'Cleaning',             'input_link' => '/cleaning',  'input_group' => 'company', 'input_order' => '2'],
            ['hash_id' => '38017', 'input_name' => 'Beauty',               'input_link' => '/beauty',    'input_group' => 'company', 'input_order' => '3'],
            ['hash_id' => '38008', 'input_name' => 'Why NextShine',        'input_link' => '/#why',      'input_group' => 'company', 'input_order' => '2', 'visibility' => 'hide'],
            ['hash_id' => '38010', 'input_name' => 'Reviews',              'input_link' => '/reviews',   'input_group' => 'company', 'input_order' => '4', 'visibility' => 'hide'],
            ['hash_id' => '38011', 'input_name' => 'Contact Us',           'input_link' => '/contact',   'input_group' => 'company', 'input_order' => '5'],
            ['hash_id' => '38012', 'input_name' => 'Privacy Policy',       'input_link' => '#',          'input_group' => 'legal',   'input_order' => '1'],
            ['hash_id' => '38013', 'input_name' => 'Terms of Service',     'input_link' => '#',          'input_group' => 'legal',   'input_order' => '2'],
            ['hash_id' => '38014', 'input_name' => 'Cookie Policy',        'input_link' => '#',          'input_group' => 'legal',   'input_order' => '3'],
        ]);

        $this->insertMissing('panel_footer_socials', [
            ['hash_id' => '39001', 'input_icon' => 'fa-brands fa-facebook-f', 'input_label' => 'Facebook',  'input_link' => '#', 'input_order' => '1'],
            ['hash_id' => '39002', 'input_icon' => 'fa-brands fa-instagram',  'input_label' => 'Instagram', 'input_link' => '#', 'input_order' => '2'],
            ['hash_id' => '39003', 'input_icon' => 'fa-brands fa-whatsapp',   'input_label' => 'WhatsApp',  'input_link' => '#', 'input_order' => '3'],
            ['hash_id' => '39004', 'input_icon' => 'fa-brands fa-google',     'input_label' => 'Google',    'input_link' => '#', 'input_order' => '4'],
        ]);
    }
}
