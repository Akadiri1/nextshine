<?php
use App\Migrator\Seeder;

class PricingSeeder extends Seeder {
    public function run() {
        $this->insertIfEmpty('settings_home_pricing', [
            'hash_id'          => '10005',
            'input_label'      => 'Transparent Pricing',
            'input_title'      => 'Clear Prices. No Hidden Charges.',
            'text_subtitle'    => 'We use fixed prices for end-of-tenancy cleans — the industry standard that landlords and agents prefer. Hourly rates for regular and commercial work.',
            'input_tab_eot'    => 'End-of-Tenancy (Fixed Price)',
            'input_tab_hourly' => 'Regular & Commercial (Hourly)',
            'text_eot_note'    => 'All fixed prices include all cleaning products and equipment. No VAT. No call-out charges. We clean until the job is done — not until the clock runs out.',
            'input_agent_note' => 'Letting agent? Ask about our portfolio rates for multiple properties.',
        ]);

        $this->insertMissing('panel_pricing_eot', [
            ['hash_id' => '32001', 'input_property' => 'Studio / Bedsit',        'input_detail' => 'Up to 1 room + kitchen + bathroom',                    'input_duration' => '2.5 – 3 hrs', 'input_price' => '£100 – £120', 'input_market' => '£90 – £150',  'input_order' => '1'],
            ['hash_id' => '32002', 'input_property' => '1 Bedroom Flat',         'input_detail' => '1 bed + living room + kitchen + bathroom',              'input_duration' => '3 – 4 hrs',   'input_price' => '£130 – £160', 'input_market' => '£120 – £200', 'input_order' => '2'],
            ['hash_id' => '32003', 'input_property' => '2 Bedroom Flat / House', 'input_detail' => '2 beds + living room + kitchen + bathroom(s)',          'input_duration' => '4 – 5.5 hrs', 'input_price' => '£175 – £220', 'input_market' => '£165 – £250', 'input_order' => '3'],
            ['hash_id' => '32004', 'input_property' => '3 Bedroom House',        'input_detail' => '3 beds + multiple living spaces + kitchen + bathrooms', 'input_duration' => '5.5 – 7 hrs', 'input_price' => '£230 – £285', 'input_market' => '£220 – £320', 'input_order' => '4'],
            ['hash_id' => '32005', 'input_property' => '4 Bedroom House',        'input_detail' => '4+ beds + full family home',                            'input_duration' => '7 – 9 hrs',   'input_price' => '£285 – £345', 'input_market' => '£270 – £380', 'input_order' => '5'],
        ]);

        $this->insertMissing('panel_pricing_hourly', [
            ['hash_id' => '33001', 'input_icon' => 'fa-solid fa-house-chimney',      'input_title' => 'Regular Domestic',    'input_price' => '21',    'input_per' => 'per hour', 'input_minimum' => 'Minimum 2 hours · Weekly or fortnightly', 'input_badge' => '',               'input_cta_text' => 'Book Now',    'input_cta_url' => '#contact', 'input_featured' => 'no',  'input_order' => '1'],
            ['hash_id' => '33002', 'input_icon' => 'fa-solid fa-house-circle-check', 'input_title' => 'Void / Maintenance',  'input_price' => '21',    'input_per' => 'per hour', 'input_minimum' => 'Minimum 2 hours · One-off or recurring',  'input_badge' => 'Most Requested', 'input_cta_text' => 'Book Now',    'input_cta_url' => '#contact', 'input_featured' => 'yes', 'input_order' => '2'],
            ['hash_id' => '33003', 'input_icon' => 'fa-solid fa-briefcase',          'input_title' => 'Commercial / Office', 'input_price' => '18–20', 'input_per' => 'per hour', 'input_minimum' => 'Minimum 2 hours · Flexible scheduling',   'input_badge' => '',               'input_cta_text' => 'Get a Quote', 'input_cta_url' => '#contact', 'input_featured' => 'no',  'input_order' => '3'],
        ]);

        $features = [
            '33001' => ['All rooms, surfaces and floors', 'Kitchen and bathroom included', 'Same team every visit', 'All cleaning products included', 'No VAT · No hidden fees'],
            '33002' => ['Vacant property maintenance', 'Ideal during marketing periods', 'Short-notice availability', 'Key holding available', 'Letting agent accounts welcome'],
            '33003' => ['Offices, studios, clinics', 'Morning, evening & weekend slots', 'Monthly contract rates available', 'All products supplied', 'Invoiced monthly for ease'],
        ];

        $rows = [];
        $hash = 40001;
        foreach ($features as $card => $list) {
            foreach ($list as $i => $feature) {
                $rows[] = [
                    'hash_id'       => (string) $hash++,
                    'input_feature' => $feature,
                    'tb'            => 'panel_pricing_hourly',
                    'tb_link'       => $card,
                    'input_order'   => (string) ($i + 1),
                ];
            }
        }
        $this->insertMissing('addition_pricing_features', $rows);
    }
}
