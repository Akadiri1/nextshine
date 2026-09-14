<?php
use App\Migrator\Seeder;

class HowItWorksSeeder extends Seeder {
    public function run() {
        $this->insertIfEmpty('settings_home_how', [
            'hash_id'       => '10004',
            'input_label'   => 'Simple Process',
            'input_title'   => 'Getting Started is Easy',
            'text_subtitle' => 'From first contact to spotless property in four simple steps. We handle everything — you just need to let us in.',
        ]);

        $this->insertMissing('panel_how_steps', [
            ['hash_id' => '31001', 'input_step_number' => '1', 'input_title' => 'Get in Touch',       'input_order' => '1',
             'text_description' => 'Call, email, or fill in our quick quote form. We respond within 3 hours during business hours — often much faster.'],
            ['hash_id' => '31002', 'input_step_number' => '2', 'input_title' => 'Receive Your Quote', 'input_order' => '2',
             'text_description' => 'We send a clear, written quote — fixed price for EOT, or hourly rate for regular work. No hidden fees. No surprises.'],
            ['hash_id' => '31003', 'input_step_number' => '3', 'input_title' => 'Confirm & Book',     'input_order' => '3',
             'text_description' => 'Confirm by email or WhatsApp. We agree a date, time, and access. For letting agents, we\'re happy to hold keys for regular bookings.'],
            ['hash_id' => '31004', 'input_step_number' => '4', 'input_title' => 'We Deliver',         'input_order' => '4',
             'text_description' => 'We arrive on time, complete the clean to your spec, and follow up within 24 hours. If anything isn\'t right, we return at no extra charge.'],
        ]);
    }
}
