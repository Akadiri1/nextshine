<?php
use App\Migrator\Seeder;

class HeroSeeder extends Seeder {
    public function run() {
        $this->insertIfEmpty('settings_home_hero', [
            'hash_id'             => '10002',
            // The NextShine Group home page, from the client's sample index.html.
            'input_meta_title'      => 'NextShine Group | Cleaning & Beauty Services in Edinburgh',
            'text_meta_description' => 'NextShine Group offers professional cleaning and African hair styling services in Edinburgh. Fixed-price end-of-tenancy cleans, domestic and commercial cleaning, and expert hair styling. Family-run and fully insured.',
            'input_badge_text'      => 'NextShine Group · Edinburgh, Scotland',
            'input_headline_1'      => 'Edinburgh\'s Cleaning',
            'input_headline_2'      => 'and Beauty',
            'input_headline_3'      => 'Specialists',
            'text_description'      => 'Competitive rates for domestic and commercial cleaning, and professional African hair styling.',
            'input_cta_primary'     => 'Get a Free Quote',
            'input_cta_secondary'   => 'Call Us Now',
            'input_stat_1_value'    => '',
            'input_stat_1_label'    => 'Satisfaction Guarantee',
            'input_stat_2_value'    => '',
            'input_stat_2_label'    => 'EOT from (1-bed)',
            'input_stat_3_value'    => '',
            'input_stat_3_label'    => 'Regular Domestic',
            'input_card_title'      => 'Our Services',
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
