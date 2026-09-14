<?php
use App\Migrator\Seeder;

class WhyUsSeeder extends Seeder {
    public function run() {
        $this->insertIfEmpty('settings_home_why', [
            'hash_id'       => '10006',
            'input_label'   => 'Why NextShine',
            'input_title'   => 'Built on Reliability. Driven by Standards.',
            'text_subtitle' => 'We know what landlords and businesses need. We have built NextShine Cleaning to deliver exactly that — every single time.',
        ]);

        $this->insertMissing('panel_why_us', [
            ['hash_id' => '34001', 'input_icon' => 'fa-solid fa-clipboard-list', 'input_title' => 'Fixed Prices on EOT Cleans', 'input_order' => '1',
             'text_description' => 'No hourly uncertainty for end-of-tenancy work. You know the full cost before we start. We quote per property, not per hour — just like the market expects.'],
            ['hash_id' => '34002', 'input_icon' => 'fa-solid fa-clock', 'input_title' => 'We Turn Up On Time', 'input_order' => '2',
             'text_description' => 'Every booking confirmed in advance. We communicate proactively if anything changes. The number one complaint about cleaning companies is no-shows — we treat your time with the same respect we\'d expect.'],
            ['hash_id' => '34003', 'input_icon' => 'fa-solid fa-house-chimney-window', 'input_title' => 'Letting-Standard Results', 'input_order' => '3',
             'text_description' => 'We understand inventory checks, deposit disputes, and what letting agents look for. Our EOT cleans are done to a professional checklist that satisfies agents across Edinburgh.'],
            ['hash_id' => '34004', 'input_icon' => 'fa-solid fa-shield-halved', 'input_title' => 'Fully Insured', 'input_order' => '4',
             'text_description' => 'Public Liability Insurance as standard. You are protected from day one. Many Edinburgh letting agents require proof of insurance before accepting a cleaning contractor — we have you covered.'],
            ['hash_id' => '34005', 'input_icon' => 'fa-solid fa-people-roof', 'input_title' => 'Family-Run. Personal Service.', 'input_order' => '5',
             'text_description' => 'NextShine Cleaning is family run. You deal directly with the owners — not a call centre, not a stranger each time. Our reputation is everything to us.'],
            ['hash_id' => '34006', 'input_icon' => 'fa-solid fa-comments', 'input_title' => 'Satisfaction Guaranteed', 'input_order' => '6',
             'text_description' => 'If you are not satisfied with any aspect of our work, we return to put it right at no extra charge. No argument, no delay. That is our promise to every client.'],
        ]);
    }
}
