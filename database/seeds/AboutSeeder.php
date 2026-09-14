<?php
use App\Migrator\Seeder;

class AboutSeeder extends Seeder {
    public function run() {
        $this->insertIfEmpty('settings_home_about', [
            'hash_id'                  => '10007',
            'input_label'              => 'Our Story',
            'input_title'              => 'A Business Built on Doing Things Right',
            'text_quote'               => 'We started NextShine Cleaning because we kept hearing the same thing from landlords and letting agents — they couldn\'t find a cleaner they could actually rely on. We decided to be the answer to that problem.',
            'input_quote_author'       => '— Samuel, Co-founder',
            'text_description'         => 'NextShine Cleaning is a trading division of NextShine Group Ltd, a Scottish company founded for hospitality purposes, and we have put our reputation behind every job we take on.',
            'input_floatcard_title'    => 'Edinburgh',
            'input_floatcard_label'    => 'Based & Operating Locally',
            'input_cta_primary_text'   => 'Work With Us',
            'input_cta_primary_url'    => '#contact',
            'input_cta_secondary_text' => 'Why We\'re Different',
            'input_cta_secondary_url'  => '#why',
        ]);

        $this->insertMissing('panel_about_bullets', [
            ['hash_id' => '35001', 'input_text' => 'Directly operated by the founders — no subcontractors in Year 1',             'input_order' => '1'],
            ['hash_id' => '35002', 'input_text' => 'Fully insured, COSHH-compliant, and registered with the ICO',                'input_order' => '2'],
            ['hash_id' => '35003', 'input_text' => 'Embedded in a wider property and facilities group — we understand the sector', 'input_order' => '3'],
            ['hash_id' => '35004', 'input_text' => 'Committed to Edinburgh\'s landlord and letting agent community long-term',   'input_order' => '4'],
        ]);
    }
}
