<?php
use App\Migrator\Seeder;

/**
 * The three sample reviews are seeded hidden. While no review is visible the
 * site shows a "Real reviews coming soon" block instead of the section.
 */
class TestimonialsSeeder extends Seeder {
    public function run() {
        $this->insertIfEmpty('settings_home_testimonials', [
            'hash_id'            => '10009',
            'input_label'        => 'Client Reviews',
            'input_title'        => 'What Our Clients Say',
            'text_subtitle'      => 'We let our work speak for itself. Here\'s what landlords, letting agents, and businesses in Edinburgh say about NextShine Cleaning.',
            'input_google_score' => '5.0',
            'input_google_text'  => '[X] reviews on Google · [Add your real rating]',
        ]);

        $this->insertMissing('panel_testimonials', [
            ['hash_id' => '37001', 'input_author_name' => 'S. Laing', 'input_author_role' => 'Private Landlord, Edinburgh', 'input_author_initials' => 'SL',
             'input_source' => 'Google Review — [Replace with real review]', 'bgcolor_avatar' => '#00878A', 'input_order' => '1', 'visibility' => 'hide',
             'text_review' => 'Absolutely outstanding end-of-tenancy clean. The property was immaculate — better than when my tenants moved in. I\'ve already booked them for two more properties.'],
            ['hash_id' => '37002', 'input_author_name' => 'J. Robertson', 'input_author_role' => 'Letting Agent, Edinburgh', 'input_author_initials' => 'JR',
             'input_source' => 'Google Review — [Replace with real review]', 'bgcolor_avatar' => '#1A335C', 'input_order' => '2', 'visibility' => 'hide',
             'text_review' => 'We use NextShine Cleaning for all our end-of-tenancy work. Reliable, professional, and they understand exactly what letting agents need. Communication is excellent.'],
            ['hash_id' => '37003', 'input_author_name' => 'A. Murray', 'input_author_role' => 'Business Owner, Edinburgh', 'input_author_initials' => 'AM',
             'input_source' => 'Google Review — [Replace with real review]', 'bgcolor_avatar' => '#7c3aed', 'input_order' => '3', 'visibility' => 'hide',
             'text_review' => 'Our office has never looked this good. The team are punctual, thorough, and genuinely great people to work with. Highly recommended for any business.'],
        ]);
    }
}
