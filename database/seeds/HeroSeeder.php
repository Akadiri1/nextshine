<?php
use App\Migrator\Seeder;

class HeroSeeder extends Seeder {
    public function run() {
        $this->insertIfEmpty('settings_home_hero', [
            'hash_id'             => '10002',
            'input_badge_text'    => 'Now accepting new clients in Edinburgh',
            'input_headline_1'    => 'Edinburgh\'s Most',
            'input_headline_2'    => 'Reliable Cleaning',
            'input_headline_3'    => 'Service',
            'text_description'    => 'Fixed-price end-of-tenancy cleans. Competitive hourly rates for domestic and commercial work. Fully insured, family-run, and always professional.',
            'input_cta_primary'   => 'Get a Free Quote',
            'input_cta_secondary' => 'Call Us Now',
            'input_stat_1_value'  => '100%',
            'input_stat_1_label'  => 'Satisfaction Guarantee',
            'input_stat_2_value'  => '£130',
            'input_stat_2_label'  => 'EOT from (1-bed)',
            'input_stat_3_value'  => '£21/hr',
            'input_stat_3_label'  => 'Regular Domestic',
            'input_card_title'    => 'Get an Instant Quote',
        ]);

        $this->insertMissing('panel_trust_items', [
            // Hidden until the insurance policy is in place.
            ['hash_id' => '20001', 'input_icon' => 'fa-solid fa-shield-halved', 'input_text' => 'Fully Insured (Public Liability)', 'input_order' => '1', 'visibility' => 'hide'],
            ['hash_id' => '20002', 'input_icon' => 'fa-solid fa-circle-check',  'input_text' => 'Vetted & Background-Checked',      'input_order' => '2'],
            ['hash_id' => '20003', 'input_icon' => 'fa-solid fa-house',         'input_text' => 'Landlord & Agent Specialists',     'input_order' => '3'],
            ['hash_id' => '20004', 'input_icon' => 'fa-solid fa-sack-dollar',   'input_text' => 'Fixed Prices — No Surprises',      'input_order' => '4'],
            ['hash_id' => '20005', 'input_icon' => 'fa-solid fa-star',          'input_text' => 'Satisfaction Guaranteed',          'input_order' => '5'],
            ['hash_id' => '20006', 'input_icon' => 'fa-solid fa-location-dot',  'input_text' => 'Edinburgh & Surrounding Areas',    'input_order' => '6'],
        ]);
    }
}
