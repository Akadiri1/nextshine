<?php
/**
 * Site restructure requested by the client (May 2026):
 *   - the Services and Pricing pages merge into one "Cleaning" page
 *   - the About Us and Coverage pages are removed
 *
 * The home page and its sections are unchanged. Retired menu and footer rows
 * are hidden rather than deleted, so an admin can bring any of them back.
 */

return new class {
    public function up($migration) {
        // Menu: Home · Cleaning · (Beauty goes in at 2 when its page ships) · Reviews · Contact
        $migration->update('panel_home_nav', ['input_name' => 'Cleaning', 'input_link' => '/cleaning', 'input_order' => '1'], ['hash_id' => '50001']);
        $migration->update('panel_home_nav', ['visibility' => 'hide'], ['hash_id' => '50002']); // Pricing
        $migration->update('panel_home_nav', ['visibility' => 'hide'], ['hash_id' => '50003']); // About Us
        $migration->update('panel_home_nav', ['visibility' => 'hide'], ['hash_id' => '50004']); // Coverage
        $migration->update('panel_home_nav', ['input_order' => '3'], ['hash_id' => '50005']);   // Reviews
        $migration->update('panel_home_nav', ['input_order' => '4'], ['hash_id' => '50006']);   // Contact

        // Footer company links that pointed at the removed pages
        $migration->update('panel_footer_links', ['visibility' => 'hide'], ['hash_id' => '38007']); // About Us
        $migration->update('panel_footer_links', ['input_link' => '/#why'], ['hash_id' => '38008']); // Why NextShine
        $migration->update('panel_footer_links', ['visibility' => 'hide'], ['hash_id' => '38009']); // Coverage Area
    }

    public function down($migration) {
        $migration->update('panel_home_nav', ['input_name' => 'Services', 'input_link' => '/services', 'input_order' => '1'], ['hash_id' => '50001']);
        $migration->update('panel_home_nav', ['visibility' => 'show'], ['hash_id' => '50002']);
        $migration->update('panel_home_nav', ['visibility' => 'show'], ['hash_id' => '50003']);
        $migration->update('panel_home_nav', ['visibility' => 'show'], ['hash_id' => '50004']);
        $migration->update('panel_home_nav', ['input_order' => '5'], ['hash_id' => '50005']);
        $migration->update('panel_home_nav', ['input_order' => '6'], ['hash_id' => '50006']);

        $migration->update('panel_footer_links', ['visibility' => 'show'], ['hash_id' => '38007']);
        $migration->update('panel_footer_links', ['input_link' => '/about#why'], ['hash_id' => '38008']);
        $migration->update('panel_footer_links', ['visibility' => 'show'], ['hash_id' => '38009']);
    }
};
