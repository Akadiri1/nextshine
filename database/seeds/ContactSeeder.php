<?php
use App\Migrator\Seeder;

class ContactSeeder extends Seeder {
    public function run() {
        $this->insertIfEmpty('settings_home_contact', [
            'hash_id'              => '10010',
            'input_label'          => 'Get in Touch',
            'input_title'          => 'Ready to Book? Let\'s Talk.',
            'text_subtitle'        => 'Fill in the form and we will get back to you within 3 hours. Or reach us directly for an instant quote.',
            'input_phone_label'    => 'Phone / WhatsApp',
            'input_email_label'    => 'Email',
            'input_response_label' => 'Response Time',
            'input_response_value' => 'Within 3 hours · Mon–Sat 7am–7pm',
            'input_coverage_label' => 'Coverage',
            'input_coverage_value' => 'Edinburgh & Surrounding Areas',
        ]);

        $this->insertOptions('selection_form_services', 70001, [
            'End-of-Tenancy Clean (Fixed Price)',
            'Regular Domestic Cleaning (Hourly)',
            'Commercial / Office Cleaning',
            'One-Off Deep Clean',
            'Post-Construction / Renovation Clean',
            'AirBnB / Short-Let Turnover',
            'Void Period Maintenance Clean',
        ], 'Cleaning Services');

        $this->insertOptions('selection_form_services', 70008, [
            'African Hair Styling (Braids, Cornrows, Ghana Weaving, Sew-Ins, Crochet)',
            'Hair Bundles and Wigs Enquiry',
            'Bundle and Style Package',
        ], 'Beauty Services', 8);

        $this->insertOptions('selection_form_property_sizes', 71001, [
            'Studio / Bedsit',
            '1 Bedroom',
            '2 Bedrooms',
            '3 Bedrooms',
            '4+ Bedrooms',
            'Office / Commercial',
        ]);
    }

    private function insertOptions($table, $firstHash, array $names, $group = null, $firstOrder = 1) {
        $rows = [];
        foreach ($names as $i => $name) {
            $row = [
                'hash_id'     => (string) ($firstHash + $i),
                'input_name'  => $name,
                'input_order' => (string) ($firstOrder + $i),
            ];
            // Shown as a heading in the quote form's service list.
            if ($group !== null) {
                $row['input_group'] = $group;
            }
            $rows[] = $row;
        }
        $this->insertMissing($table, $rows);
    }
}
