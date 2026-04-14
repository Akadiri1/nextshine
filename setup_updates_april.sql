-- =====================================================
-- NextShine — Content & Contact Updates (April)
-- Run against your existing database
-- =====================================================

-- ─────────────────────────────────────────────────────
-- 1. Update site contact info (phone & email)
-- ─────────────────────────────────────────────────────
UPDATE settings_website_info
SET input_phone_number = '+44 (0)7344 225808',
    input_email = 'hello@nextshinegroup.co.uk',
    input_email_from = 'hello@nextshinegroup.co.uk',
    input_name = 'NextShine Cleaning'
WHERE visibility = 'show';


-- ─────────────────────────────────────────────────────
-- 2. Add WhatsApp number field (if not exists)
-- ─────────────────────────────────────────────────────
ALTER TABLE settings_website_info
  ADD COLUMN input_whatsapp_number VARCHAR(100) DEFAULT '' AFTER input_phone_number;

UPDATE settings_website_info
SET input_whatsapp_number = '447344225808'
WHERE visibility = 'show';


-- ─────────────────────────────────────────────────────
-- 3. Add company registration number to footer settings
-- ─────────────────────────────────────────────────────
ALTER TABLE settings_home_footer
  ADD COLUMN input_registration_number VARCHAR(255) DEFAULT '' AFTER input_copyright;


-- ─────────────────────────────────────────────────────
-- 4. Hide 'Fully Insured (Public Liability)' trust item
-- ─────────────────────────────────────────────────────
UPDATE panel_trust_items
SET visibility = 'hide'
WHERE hash_id = '20001';


-- ─────────────────────────────────────────────────────
-- 5. Update 'Family-Run. Personal Service.' text
-- ─────────────────────────────────────────────────────
UPDATE panel_why_us
SET text_description = 'NextShine Cleaning is family run. You deal directly with the owners — not a call centre, not a stranger each time. Our reputation is everything to us.'
WHERE hash_id = '34005';


-- ─────────────────────────────────────────────────────
-- 6. Update 'Our Story' (About) — replace Tosin with Samuel
--    and remove spouse reference
-- ─────────────────────────────────────────────────────
UPDATE settings_home_about
SET text_quote = 'We started NextShine Cleaning because we kept hearing the same thing from landlords and letting agents — they couldn''t find a cleaner they could actually rely on. We decided to be the answer to that problem.',
    input_quote_author = '— Samuel, Co-founder',
    text_description = 'NextShine Cleaning is a trading division of NextShine Group Ltd, a Scottish company founded for hospitality purposes, and we have put our reputation behind every job we take on.'
WHERE visibility = 'show';


-- ─────────────────────────────────────────────────────
-- 7. Hide testimonials section header (so the whole section can be
--    conditionally skipped in PHP based on testimonials count)
--    Also hide all seed testimonials so nothing renders until real reviews exist.
-- ─────────────────────────────────────────────────────
UPDATE panel_testimonials SET visibility = 'hide' WHERE hash_id IN ('37001','37002','37003');


-- ─────────────────────────────────────────────────────
-- 8. Update address
-- ─────────────────────────────────────────────────────
UPDATE settings_website_info
SET input_address = 'Edinburgh & Surrounding Areas, Scotland'
WHERE visibility = 'show';


-- ─────────────────────────────────────────────────────
-- 9. Repoint nav menu items to dedicated pages
-- ─────────────────────────────────────────────────────
UPDATE panel_home_nav SET input_link = '/services'     WHERE hash_id = '50001';
UPDATE panel_home_nav SET input_link = '/pricing'      WHERE hash_id = '50002';
UPDATE panel_home_nav SET input_link = '/about'        WHERE hash_id = '50003';
UPDATE panel_home_nav SET input_link = '/coverage'     WHERE hash_id = '50004';
UPDATE panel_home_nav SET input_link = '/reviews'      WHERE hash_id = '50005';

-- Add Contact nav item
INSERT INTO panel_home_nav (hash_id, input_name, input_link, input_order, visibility, date_created, time_created, created_by)
VALUES ('50006', 'Contact', '/contact', '6', 'show', '2026-04-14', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- 10. Repoint footer links to dedicated pages
-- ─────────────────────────────────────────────────────
UPDATE panel_footer_links SET input_link = '/services' WHERE input_group = 'services';
UPDATE panel_footer_links SET input_link = '/about'      WHERE hash_id = '38007';
UPDATE panel_footer_links SET input_link = '/about#why'  WHERE hash_id = '38008';
UPDATE panel_footer_links SET input_link = '/coverage'   WHERE hash_id = '38009';
UPDATE panel_footer_links SET input_link = '/reviews'    WHERE hash_id = '38010';
UPDATE panel_footer_links SET input_link = '/contact'    WHERE hash_id = '38011';
