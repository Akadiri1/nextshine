<?php
use App\Migrator\Seeder;

/**
 * NextShine Beauty content, taken from the client's beauty.html.
 *
 * The WhatsApp/phone numbers and Instagram link are the placeholders from that
 * file; replace them in the admin once the client confirms the real ones. The
 * photos are stock placeholders too (www/assets/images/beauty/CREDITS.md).
 */
class BeautySeeder extends Seeder {
    public function run() {
        $photos = '/assets/images/beauty/';

        $this->insertIfEmpty('settings_beauty_site', [
            'hash_id'                 => '80001',
            'input_meta_title'        => 'NextShine Beauty | African Hair Styling & Hair Sales – Edinburgh',
            'text_meta_description'   => 'Professional African hair styling in Edinburgh. Braids, Ghana weaving, cornrows, crochet and more. Hair bundles and wigs also available. Book your appointment today.',
            'input_topbar_text'       => 'Now accepting new clients in',
            'input_topbar_highlight'  => 'Edinburgh',
            'input_logo_sub'          => 'Edinburgh, Scotland',
            'input_nav_cta'           => 'Book Now',
            'input_email'             => '',
            'input_instagram_url'     => 'https://www.instagram.com/',
            'input_whatsapp_number'   => '447000000000',
            'input_social_text'       => 'Follow us & see our latest work',
            'input_footer_name'       => 'NextShine Beauty',
            'input_footer_tagline'    => 'A Division of NextShine Group Ltd',
            'input_copyright'         => '© 2024 NextShine Group Ltd · Edinburgh, Scotland',
            'input_registration_text' => 'NextShine Group Ltd is registered in Scotland.',
            'input_company_number'    => '',
        ]);

        // Menus. The /beauty link is marked as the current page.
        $this->insertMissing('panel_beauty_nav', [
            ['hash_id' => '89001', 'input_name' => 'Home',     'input_link' => '/',         'input_order' => '1'],
            ['hash_id' => '89002', 'input_name' => 'Cleaning', 'input_link' => '/cleaning', 'input_order' => '2'],
            ['hash_id' => '89003', 'input_name' => 'Beauty',   'input_link' => '/beauty',   'input_order' => '3'],
            ['hash_id' => '89004', 'input_name' => 'Contact',  'input_link' => '#booking',  'input_order' => '4'],
        ]);
        $this->insertMissing('panel_beauty_footer_links', [
            ['hash_id' => '89101', 'input_name' => 'Privacy Policy', 'input_link' => '#', 'input_order' => '1'],
            ['hash_id' => '89102', 'input_name' => 'Terms',          'input_link' => '#', 'input_order' => '2'],
        ]);

        $this->insertIfEmpty('settings_beauty_hero', [
            'hash_id'                  => '80002',
            'input_eyebrow'            => 'NextShine Beauty · Edinburgh, Scotland',
            'input_headline_1'         => 'Your Hair.',
            'input_headline_2'         => 'Perfected.',
            'input_headline_3'         => 'In Edinburgh.',
            'text_description'         => 'Professional African hair styling including braids, Ghana weaving, cornrows, crochet and more. Expert hands, right here in Edinburgh.',
            'input_cta_primary_text'   => 'Book an Appointment',
            'input_cta_secondary_text' => 'View Our Services',
            'input_badge_1_value'      => '100%',
            'text_badge_1_label'       => "Natural Hair\nSpecialist",
            'input_badge_2_value'      => 'Home',
            'text_badge_2_label'       => "Based &\nMobile Service",
            'input_badge_3_value'      => 'First',
            'text_badge_3_label'       => "Timer\nDiscount",
            'image_1'                  => $photos . 'hero.jpg',
        ]);

        $this->insertMissing('panel_beauty_highlights', [
            ['hash_id' => '81001', 'input_icon' => 'fa-solid fa-award', 'input_title' => 'Expert Technique',  'input_order' => '1',
             'text_description' => 'Trained in a wide range of African hair styling methods delivered with care and precision every time.'],
            ['hash_id' => '81002', 'input_icon' => 'fa-solid fa-location-dot', 'input_title' => 'Edinburgh Based',   'input_order' => '2',
             'text_description' => 'Quality African hair styling available right here in Edinburgh. No need to travel far.'],
            ['hash_id' => '81003', 'input_icon' => 'fa-solid fa-bag-shopping', 'input_title' => 'Hair & Products',   'input_order' => '3',
             'text_description' => 'We also sell quality hair bundles and wigs for style and supply all in one place.'],
            ['hash_id' => '81004', 'input_icon' => 'fa-solid fa-gift', 'input_title' => 'Loyalty Discounts', 'input_order' => '4',
             'text_description' => 'First-timer discounts and returning customer rewards because we value every client.'],
        ]);

        $this->insertIfEmpty('settings_beauty_services', [
            'hash_id'            => '80003',
            'input_label'        => 'What We Offer',
            'input_title'        => 'Our Services',
            'text_subtitle'      => 'Each style is completed with care and attention. Enquire to receive a personalised quote based on your hair and chosen style.',
            'input_enquire_text' => 'Enquire for Pricing →',
        ]);

        $this->insertMissing('panel_beauty_services', [
            ['hash_id' => '82001', 'input_title' => 'Braids',        'input_price' => '', 'input_order' => '1', 'image_1' => $photos . 'service-braids.jpg',
             'text_description' => 'Box braids, knotless braids, and more. Natural-looking, long-lasting, and protective. Style and length options discussed at booking.'],
            ['hash_id' => '82002', 'input_title' => 'Ghana Weaving', 'input_price' => '', 'input_order' => '2', 'image_1' => $photos . 'service-ghana-weaving.jpg',
             'text_description' => 'Feed-in cornrows with a defined, sleek finish. Classic, elegant and beautifully structured.'],
            ['hash_id' => '82003', 'input_title' => 'Cornrows',      'input_price' => '', 'input_order' => '3', 'image_1' => $photos . 'service-cornrows.jpg',
             'text_description' => 'Simple to intricate cornrow designs. Perfect as a standalone style or as a base for extensions.'],
            ['hash_id' => '82004', 'input_title' => 'Sew-Ins',       'input_price' => '', 'input_order' => '4', 'image_1' => $photos . 'service-sew-ins.jpg',
             'text_description' => 'Weft extensions sewn onto braided tracks for a natural, seamless look. Great for added length and volume.'],
            ['hash_id' => '82005', 'input_title' => 'Crochet Hair',  'input_price' => '', 'input_order' => '5', 'image_1' => $photos . 'service-crochet.jpg',
             'text_description' => 'Beautiful crochet installs using a variety of hair textures and styles. Quick install, long-lasting wear.'],
        ]);

        $this->insertMissing('panel_beauty_notes', [
            ['hash_id' => '83001', 'input_highlight' => 'First-timer discount',      'input_text' => 'applies on your first appointment with us.', 'input_order' => '1'],
            ['hash_id' => '83002', 'input_highlight' => 'Returning client discount', 'input_text' => 'available. Ask about our loyalty pricing when booking.', 'input_order' => '2'],
            ['hash_id' => '83003', 'input_highlight' => 'Home service charge',       'input_text' => 'may apply depending on your location in Edinburgh and surrounding areas.', 'input_order' => '3'],
            ['hash_id' => '83004', 'input_highlight' => '',                          'input_text' => 'Pricing is shared directly after enquiry and is based on hair length, thickness, and style complexity.', 'input_order' => '4'],
        ]);

        $this->insertIfEmpty('settings_beauty_shop', [
            'hash_id'       => '80004',
            'input_label'   => 'Hair Shop',
            'input_title'   => 'Quality Hair, Delivered',
            'text_subtitle' => 'We stock and supply premium human hair bundles and ready-made wigs. Whether you\'re bringing your own or buying through us, we\'ve got you covered.',
        ]);

        $this->insertMissing('panel_beauty_products', [
            ['hash_id' => '84001', 'input_icon' => 'fa-solid fa-box-open', 'input_title' => 'Hair Bundles',             'input_link_text' => 'Enquire About Bundles →', 'input_order' => '1', 'image_1' => $photos . 'product-bundles.jpg',
             'text_description' => 'Raw and virgin human hair bundles sourced for quality, texture, and longevity.'],
            ['hash_id' => '84002', 'input_icon' => 'fa-solid fa-crown', 'input_title' => 'Wigs',                     'input_link_text' => 'Enquire About Wigs →',    'input_order' => '2', 'image_1' => $photos . 'product-wigs.jpg',
             'text_description' => 'Pre-made and custom wigs ready to wear or styled specifically for you.'],
            ['hash_id' => '84003', 'input_icon' => 'fa-solid fa-wand-magic-sparkles', 'input_title' => 'Bundle & Style Packages', 'input_link_text' => 'Book a Package →',         'input_order' => '3', 'image_1' => $photos . 'product-packages.jpg',
             'text_description' => 'Get your hair and your appointment sorted in one go. We\'ll source the hair and style it for you.'],
        ]);

        $features = [
            '84001' => ['Raw virgin bundles', 'Multiple lengths available', 'Various textures: straight, wavy, curly', 'Coloured and natural options', 'Can be used for your styling appointment'],
            '84002' => ['Lace front wigs', 'Full lace and closure wigs', 'Braided wigs available', 'Custom wig installs & styling', 'Wig repairs and maintenance'],
            '84003' => ['Hair sourced on your behalf', 'Appointment and hair combined', 'Tailored to your budget', 'Colour matching available', 'Best value option'],
        ];
        $rows = [];
        $hash = 85001;
        foreach ($features as $product => $list) {
            foreach ($list as $i => $feature) {
                $rows[] = [
                    'hash_id'       => (string) $hash++,
                    'input_feature' => $feature,
                    'tb'            => 'panel_beauty_products',
                    'tb_link'       => $product,
                    'input_order'   => (string) ($i + 1),
                ];
            }
        }
        $this->insertMissing('addition_beauty_product_features', $rows);

        $this->insertIfEmpty('settings_beauty_gallery', [
            'hash_id'       => '80006',
            'input_label'   => 'Our Work',
            'input_title'   => 'Recent Styles',
            'text_subtitle' => 'A look at the styles we create. Follow us on Instagram to see new work as it happens.',
        ]);

        $rows = [];
        foreach ([
            'Box braids with beads', 'Cornrows with coral beads', 'Long box braids', 'Medium knotless braids',
            'Waist-length braids', 'Stitch cornrows', 'Senegalese twists', 'Braided top bun',
        ] as $i => $caption) {
            $rows[] = [
                'hash_id'       => (string) (88001 + $i),
                'input_caption' => $caption,
                'image_1'       => $photos . 'gallery-' . ($i + 1) . '.jpg',
                'input_order'   => (string) ($i + 1),
            ];
        }
        $this->insertMissing('panel_beauty_gallery', $rows);

        $this->insertIfEmpty('settings_beauty_booking', [
            'hash_id'               => '80005',
            'input_label'           => 'Get in Touch',
            'input_title'           => 'Book Your Appointment',
            'text_subtitle'         => 'Ready to get your hair done? Reach out via any of the channels below or fill in the form and we\'ll get back to you promptly.',
            'text_notes'            => implode("\n", [
                'We respond to all enquiries within 24 hours.',
                'Consultation is required before first appointment.',
                'Deposit may be required to secure your booking.',
            ]),
            'input_form_title'      => 'Request an Appointment',
            'input_submit_text'     => 'Send Request',
            'input_form_note'       => 'We\'ll confirm availability and pricing via WhatsApp or phone.',
            'input_success_message' => 'Thank you! We\'ve received your request and will be in touch within 24 hours.',
        ]);

        $this->insertMissing('panel_beauty_channels', [
            ['hash_id' => '86001', 'input_icon' => 'fa-brands fa-instagram', 'input_title' => 'Instagram', 'input_subtitle' => 'DM us or view our work @nextshinbeauty',      'input_link' => 'https://www.instagram.com/',  'input_order' => '1'],
            ['hash_id' => '86002', 'input_icon' => 'fa-brands fa-whatsapp', 'input_title' => 'WhatsApp',  'input_subtitle' => 'Message us to arrange your appointment',      'input_link' => 'https://wa.me/447000000000', 'input_order' => '2'],
            ['hash_id' => '86003', 'input_icon' => 'fa-solid fa-phone', 'input_title' => 'Phone',     'input_subtitle' => 'Call to discuss your style and availability', 'input_link' => 'tel:+447000000000',          'input_order' => '3'],
        ]);

        $rows = [];
        foreach ([
            'Braids', 'Ghana Weaving', 'Cornrows', 'Sew-Ins', 'Crochet Hair', 'Wig Install / Styling',
            'Hair Bundles Enquiry', 'Bundle & Style Package', 'Not sure, need advice',
        ] as $i => $name) {
            $rows[] = ['hash_id' => (string) (87001 + $i), 'input_name' => $name, 'input_order' => (string) ($i + 1)];
        }
        $this->insertMissing('selection_beauty_booking_services', $rows);
    }
}
