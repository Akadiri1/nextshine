<?php
use App\Migrator\Seeder;

class ContactSeeder extends Seeder {
    public function run() {
        $this->insertIfEmpty('settings_home_contact', [
            'hash_id'              => '10010',
            'input_label'          => 'Get in Touch',
            'input_title'          => 'Ready to Book? Let\'s Talk.',
            'text_subtitle'        => 'Fill in the form and we\'ll get back to you within 3 hours. Or call us directly for an instant quote.',
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
        ]);

        $this->insertOptions('selection_form_property_sizes', 71001, [
            'Studio / Bedsit',
            '1 Bedroom',
            '2 Bedrooms',
            '3 Bedrooms',
            '4+ Bedrooms',
            'Office / Commercial',
        ]);
    }

    private function insertOptions($table, $firstHash, array $names) {
        $rows = [];
        foreach ($names as $i => $name) {
            $rows[] = [
                'hash_id'     => (string) ($firstHash + $i),
                'input_name'  => $name,
                'input_order' => (string) ($i + 1),
            ];
        }
        $this->insertMissing($table, $rows);
    }
}
