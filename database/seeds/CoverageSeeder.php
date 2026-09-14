<?php
use App\Migrator\Seeder;

class CoverageSeeder extends Seeder {
    public function run() {
        $this->insertIfEmpty('settings_home_coverage', [
            'hash_id'                 => '10008',
            'input_label'             => 'Where We Work',
            'input_title'             => 'Edinburgh & Surrounding Areas',
            'text_description'        => 'We cover Edinburgh city and the surrounding commuter belt. If you\'re not sure whether we cover your area, just give us a call — we\'d love to help if we can.',
            'input_city_label'        => 'Edinburgh City',
            'input_surrounding_label' => 'Surrounding Areas',
            'input_footer_note'       => 'Don\'t see your area? Contact us — we may still cover you.',
        ]);

        $areas = [
            'city'        => ['Edinburgh City Centre', 'Leith', 'Morningside', 'Marchmont', 'Stockbridge', 'Bruntsfield', 'Newington', 'Portobello'],
            'surrounding' => ['Musselburgh', 'Livingston', 'Midlothian', 'Dalkeith', 'Bonnyrigg', 'Penicuik', 'Linlithgow', 'Bathgate'],
        ];

        $rows = [];
        $hash = 36001;
        foreach ($areas as $group => $names) {
            foreach ($names as $i => $name) {
                $rows[] = [
                    'hash_id'     => (string) $hash++,
                    'input_name'  => $name,
                    'input_group' => $group,
                    'input_order' => (string) ($i + 1),
                ];
            }
        }
        $this->insertMissing('panel_coverage_areas', $rows);
    }
}
