<?php
/**
 * The home page becomes the NextShine Group page from the client's sample
 * (index.html): Cleaning and Beauty side by side instead of a cleaning-only
 * page, with the menu matching the sample: Home, Cleaning, Beauty, Contact.
 *
 * - Hero (settings_home_hero): group wording, cleaning price stats cleared, and
 *   new page title / description columns.
 * - New "Two Divisions. One Standard." section: settings_home_divisions,
 *   panel_home_divisions and addition_home_division_services.
 * - Quote form: services grouped by a new input_group column, with the Beauty
 *   options added.
 * - Menu: Beauty shown; Reviews and the Beauty button hidden.
 * - Footer: company links Home, Cleaning, Beauty, Contact Us; group wording.
 *
 * Text is only replaced where it still holds its original value, so anything
 * already changed through live edit is kept.
 */

return new class {
    private $heroOld = [
        'input_badge_text'   => 'Now accepting new clients in Edinburgh',
        'input_headline_1'   => "Edinburgh's Most",
        'input_headline_2'   => 'Reliable Cleaning',
        'input_headline_3'   => 'Service',
        'text_description'   => 'Fixed-price end-of-tenancy cleans. Competitive hourly rates for domestic and commercial work. Fully insured, family-run, and always professional.',
        'input_stat_1_value' => '100%',
        'input_stat_2_value' => '£130',
        'input_stat_3_value' => '£21/hr',
        'input_card_title'   => 'Get an Instant Quote',
    ];

    private $heroNew = [
        'input_badge_text'   => 'NextShine Group · Edinburgh, Scotland',
        'input_headline_1'   => "Edinburgh's Cleaning",
        'input_headline_2'   => 'and Beauty',
        'input_headline_3'   => 'Specialists',
        'text_description'   => 'Competitive rates for domestic and commercial cleaning, and professional African hair styling.',
        'input_stat_1_value' => '',
        'input_stat_2_value' => '',
        'input_stat_3_value' => '',
        'input_card_title'   => 'Our Services',
    ];

    private $contactSubtitle = [
        'old' => "Fill in the form and we'll get back to you within 3 hours. Or call us directly for an instant quote.",
        'new' => 'Fill in the form and we will get back to you within 3 hours. Or reach us directly for an instant quote.',
    ];

    private $footerDescription = [
        'old' => 'Professional cleaning services for landlords, letting agents, and businesses across Edinburgh and surrounding areas. Fully insured. Fixed prices. Family-run. Built on trust.',
        'new' => 'NextShine Group offers professional cleaning and African hair styling services in Edinburgh. Fixed-price end-of-tenancy cleans, domestic and commercial cleaning, and expert hair styling. Family-run and fully insured.',
    ];

    public function up($migration) {
        // --- Hero -------------------------------------------------------------
        $migration->table('settings_home_hero', function ($table) {
            $table->string('input_meta_title')->default('');
            $table->text('text_meta_description')->nullable();
        });
        $migration->update('settings_home_hero', $this->heroNew, ['hash_id' => '10002', 'input_headline_1' => $this->heroOld['input_headline_1']]);
        $migration->update('settings_home_hero', [
            'input_meta_title'      => 'NextShine Group | Cleaning & Beauty Services in Edinburgh',
            'text_meta_description' => 'NextShine Group offers professional cleaning and African hair styling services in Edinburgh. Fixed-price end-of-tenancy cleans, domestic and commercial cleaning, and expert hair styling. Family-run and fully insured.',
        ], ['hash_id' => '10002', 'input_meta_title' => '']);

        // --- The two divisions ----------------------------------------------
        $migration->table('settings_home_divisions', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_label')->default('');
            $table->string('input_title')->default('');
            $table->text('text_subtitle')->nullable();
            $table->admcColumns();
        });

        $migration->table('panel_home_divisions', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_icon')->default('');
            $table->string('input_title')->default('');
            $table->text('text_description')->nullable();
            $table->string('input_link_text')->default('');
            $table->string('input_link')->default('');
            $table->string('input_theme')->default('cleaning');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });

        $migration->table('addition_home_division_services', function ($table) {
            $table->id();
            $table->string('hash_id');
            $table->string('input_name')->default('');
            $table->string('tb')->default('');
            $table->string('tb_link')->default('');
            $table->string('input_order')->default('0');
            $table->admcColumns();
        });

        $migration->insert('settings_home_divisions', $this->row([
            'hash_id'       => '91001',
            'input_label'   => 'Our Services',
            'input_title'   => 'Two Divisions. One Standard.',
            'text_subtitle' => 'NextShine Group operates two service divisions across Edinburgh. Choose yours below or get in touch and we will point you in the right direction.',
        ]));

        $divisions = [
            '91101' => [
                'input_icon'       => 'fa-solid fa-broom',
                'input_title'      => 'NextShine Cleaning',
                'text_description' => 'Professional cleaning for landlords, letting agents, businesses, and homeowners. Fixed prices on end-of-tenancy cleans. Competitive hourly rates for everything else.',
                'input_link_text'  => 'View Cleaning Services',
                'input_link'       => '/cleaning',
                'input_theme'      => 'cleaning',
                'input_order'      => '1',
                'services'         => ['End-of-Tenancy Cleans (Fixed Price)', 'Regular Domestic Cleaning (Hourly)', 'Commercial and Office Cleaning', 'One-Off Deep Cleans', 'AirBnB and Short-Let Turnovers', 'Post-Construction and Renovation Cleans'],
            ],
            '91102' => [
                'input_icon'       => 'fa-solid fa-spa',
                'input_title'      => 'NextShine Beauty',
                'text_description' => 'Professional African hair styling including braids, Ghana weaving, cornrows, sew-ins, crochet and more. Hair bundles and wigs also available.',
                'input_link_text'  => 'View Beauty Services',
                'input_link'       => '/beauty',
                'input_theme'      => 'beauty',
                'input_order'      => '2',
                'services'         => ['Braids (Box Braids, Knotless Braids)', 'Ghana Weaving', 'Cornrows', 'Sew-Ins', 'Crochet Hair', 'Hair Bundles and Wigs'],
            ],
        ];

        $serviceHash = 91201;
        foreach ($divisions as $hash => $division) {
            $services = $division['services'];
            unset($division['services']);
            $migration->insert('panel_home_divisions', $this->row(['hash_id' => $hash] + $division));

            foreach ($services as $i => $name) {
                $migration->insert('addition_home_division_services', $this->row([
                    'hash_id'     => (string) $serviceHash++,
                    'input_name'  => $name,
                    'tb'          => 'panel_home_divisions',
                    'tb_link'     => $hash,
                    'input_order' => (string) ($i + 1),
                ]));
            }
        }

        // --- Quote form -------------------------------------------------------
        $migration->table('selection_form_services', function ($table) {
            $table->string('input_group')->default('');
        });
        $migration->update('selection_form_services', ['input_group' => 'Cleaning Services'], ['input_group' => '']);
        foreach (['African Hair Styling (Braids, Cornrows, Ghana Weaving, Sew-Ins, Crochet)', 'Hair Bundles and Wigs Enquiry', 'Bundle and Style Package'] as $i => $name) {
            $migration->insert('selection_form_services', $this->row([
                'hash_id'     => (string) (70008 + $i),
                'input_name'  => $name,
                'input_group' => 'Beauty Services',
                'input_order' => (string) (8 + $i),
            ]));
        }
        $migration->update('settings_home_contact', ['text_subtitle' => $this->contactSubtitle['new']], ['hash_id' => '10010', 'text_subtitle' => $this->contactSubtitle['old']]);

        // --- Menu -------------------------------------------------------------
        $migration->update('panel_home_nav', ['visibility' => 'show'], ['hash_id' => '50007', 'visibility' => 'hide']);
        $migration->update('panel_home_nav', ['visibility' => 'hide'], ['hash_id' => '50005', 'visibility' => 'show']);
        $migration->update('settings_home_nav_button', ['visibility' => 'hide'], ['hash_id' => '51001', 'visibility' => 'show']);

        // --- Footer -----------------------------------------------------------
        $migration->update('panel_footer_links', ['visibility' => 'hide'], ['hash_id' => '38008', 'input_link' => '/#why', 'visibility' => 'show']);
        $migration->update('panel_footer_links', ['visibility' => 'hide'], ['hash_id' => '38010', 'visibility' => 'show']);
        foreach ([['38015', 'Home', '/'], ['38016', 'Cleaning', '/cleaning'], ['38017', 'Beauty', '/beauty']] as $i => [$hash, $name, $link]) {
            $migration->insert('panel_footer_links', $this->row([
                'hash_id'     => $hash,
                'input_name'  => $name,
                'input_link'  => $link,
                'input_group' => 'company',
                'input_order' => (string) ($i + 1),
            ]));
        }
        $migration->update('settings_home_footer', ['text_description' => $this->footerDescription['new']], ['hash_id' => '10011', 'text_description' => $this->footerDescription['old']]);
    }

    public function down($migration) {
        $migration->update('settings_home_footer', ['text_description' => $this->footerDescription['old']], ['hash_id' => '10011', 'text_description' => $this->footerDescription['new']]);
        foreach (['38015', '38016', '38017'] as $hash) {
            $migration->delete('panel_footer_links', ['hash_id' => $hash]);
        }
        $migration->update('panel_footer_links', ['visibility' => 'show'], ['hash_id' => '38010', 'visibility' => 'hide']);
        $migration->update('panel_footer_links', ['visibility' => 'show'], ['hash_id' => '38008', 'input_link' => '/#why', 'visibility' => 'hide']);

        $migration->update('settings_home_nav_button', ['visibility' => 'show'], ['hash_id' => '51001', 'visibility' => 'hide']);
        $migration->update('panel_home_nav', ['visibility' => 'show'], ['hash_id' => '50005', 'visibility' => 'hide']);
        $migration->update('panel_home_nav', ['visibility' => 'hide'], ['hash_id' => '50007', 'visibility' => 'show']);

        $migration->update('settings_home_contact', ['text_subtitle' => $this->contactSubtitle['old']], ['hash_id' => '10010', 'text_subtitle' => $this->contactSubtitle['new']]);
        foreach (['70008', '70009', '70010'] as $hash) {
            $migration->delete('selection_form_services', ['hash_id' => $hash]);
        }
        $migration->table('selection_form_services', function ($table) {
            $table->dropColumn('input_group');
        });

        $migration->dropTable('addition_home_division_services');
        $migration->dropTable('panel_home_divisions');
        $migration->dropTable('settings_home_divisions');

        $migration->update('settings_home_hero', $this->heroOld, ['hash_id' => '10002', 'input_headline_1' => $this->heroNew['input_headline_1']]);
        $migration->table('settings_home_hero', function ($table) {
            $table->dropColumn('input_meta_title');
            $table->dropColumn('text_meta_description');
        });
    }

    private function row(array $data) {
        return $data + [
            'visibility'   => 'show',
            'date_created' => date('Y-m-d'),
            'time_created' => date('H:i:s'),
            'created_by'   => 'system',
        ];
    }
};
