<?php
use App\Migrator\Seeder;

/**
 * Menu after "Home" (which is fixed in the header), and the Beauty button
 * beside "Get a Quote". /beauty redirects to the beauty subdomain.
 */
class NavigationSeeder extends Seeder {
    public function run() {
        $this->insertMissing('panel_home_nav', [
            ['hash_id' => '50001', 'input_name' => 'Cleaning', 'input_link' => '/cleaning', 'input_order' => '1'],
            ['hash_id' => '50005', 'input_name' => 'Reviews',  'input_link' => '/reviews',  'input_order' => '3'],
            ['hash_id' => '50006', 'input_name' => 'Contact',  'input_link' => '/contact',  'input_order' => '4'],
        ]);

        $this->insertIfEmpty('settings_home_nav_button', [
            'hash_id'    => '51001',
            'input_text' => 'Beauty',
            'input_icon' => 'fa-solid fa-spa',
            'input_link' => '/beauty',
        ]);
    }
}
