-- =====================================================
-- NextShine — Service Detail Fields + Favicon
-- Run against your existing database
-- =====================================================

-- ─────────────────────────────────────────────────────
-- Add detail columns to panel_services
-- ─────────────────────────────────────────────────────
ALTER TABLE panel_services
  ADD COLUMN input_slug VARCHAR(255) DEFAULT '' AFTER input_order,
  ADD COLUMN text_full_description LONGTEXT AFTER text_description,
  ADD COLUMN text_whats_included LONGTEXT AFTER text_full_description,
  ADD COLUMN input_duration VARCHAR(255) DEFAULT '' AFTER text_whats_included,
  ADD COLUMN input_starting_price VARCHAR(255) DEFAULT '' AFTER input_duration,
  ADD COLUMN input_cta_text VARCHAR(255) DEFAULT 'Get a Free Quote' AFTER input_starting_price,
  ADD COLUMN input_cta_url VARCHAR(255) DEFAULT '#contact' AFTER input_cta_text,
  ADD COLUMN input_meta_title VARCHAR(255) DEFAULT '' AFTER input_cta_url,
  ADD COLUMN text_meta_description TEXT AFTER input_meta_title;

-- Seed slugs and detail content for existing services
UPDATE panel_services SET
  input_slug = 'end-of-tenancy-clean',
  text_full_description = 'Our end-of-tenancy deep clean is designed to meet the strict standards required by letting agents and landlords across Edinburgh. We clean every room from top to bottom — including inside ovens, behind appliances, limescale removal, window sills, skirting boards, and more.\n\nEvery clean follows our professional checklist that covers all the areas inventory clerks inspect. We stay until the job is done — not until the clock runs out.',
  text_whats_included = 'Full kitchen deep clean (oven, hob, extractor, fridge/freezer inside & out)\nBathroom descale and sanitisation\nAll rooms dusted, vacuumed, and mopped\nSkirting boards, light switches, and door frames\nInside windows and window sills\nCupboards and wardrobes wiped inside\nWall spot-cleaning for marks and scuffs',
  input_duration = '3 – 9 hours depending on property size',
  input_starting_price = 'From £100 (studio) to £345 (4-bed)',
  input_meta_title = 'End-of-Tenancy Cleaning Edinburgh | NextShine Cleaning',
  text_meta_description = 'Professional end-of-tenancy deep cleaning in Edinburgh. Fixed prices, fully insured, letting-agent approved. Book your EOT clean today.'
WHERE hash_id = '30001';

UPDATE panel_services SET
  input_slug = 'regular-domestic-cleaning',
  text_full_description = 'Our regular domestic cleaning service gives you a consistently clean home without the hassle. We assign the same team to your property every visit so they learn your space, your preferences, and your standards.\n\nWhether you need weekly or fortnightly visits, we work around your schedule and always bring our own professional-grade products and equipment.',
  text_whats_included = 'All rooms vacuumed and mopped\nKitchen surfaces, hob, and sink cleaned\nBathroom cleaned and sanitised\nDusting of all accessible surfaces\nBed making (on request)\nBins emptied\nAll cleaning products supplied',
  input_duration = 'Minimum 2 hours per visit',
  input_starting_price = '£21 per hour',
  input_meta_title = 'Regular Domestic Cleaning Edinburgh | NextShine Cleaning',
  text_meta_description = 'Affordable weekly and fortnightly domestic cleaning in Edinburgh. Same team every visit. £21/hr. All products included.'
WHERE hash_id = '30002';

UPDATE panel_services SET
  input_slug = 'commercial-office-cleaning',
  text_full_description = 'We provide reliable commercial cleaning for offices, studios, clinics, and retail spaces across Edinburgh. Our flexible contracts let you choose morning, evening, or weekend slots to minimise disruption to your business.\n\nWhether you need daily, weekly, or fortnightly cleans, we deliver consistent results and can invoice monthly for ease of accounting.',
  text_whats_included = 'Desk and workstation cleaning\nKitchen and break room cleaning\nBathroom and toilet sanitisation\nFloor vacuuming and mopping\nBin emptying and liner replacement\nGlass and surface wiping\nAll products and equipment supplied',
  input_duration = 'Minimum 2 hours per session',
  input_starting_price = 'From £18 – £20 per hour',
  input_meta_title = 'Commercial & Office Cleaning Edinburgh | NextShine Cleaning',
  text_meta_description = 'Professional office and commercial cleaning in Edinburgh. Flexible contracts, competitive rates, fully insured.'
WHERE hash_id = '30003';

UPDATE panel_services SET
  input_slug = 'one-off-deep-clean',
  text_full_description = 'Our one-off deep clean is perfect for properties that need a thorough reset. Whether it is a spring clean, preparing a property for sale, or catching up after a long tenancy — we deliver a comprehensive clean from top to bottom.\n\nWe focus on the areas that regular cleaning often misses: behind appliances, inside cupboards, deep carpet cleaning, and full bathroom descaling.',
  text_whats_included = 'Every room deep cleaned floor to ceiling\nInside oven, fridge, and all appliances\nBathroom deep descale and grout cleaning\nAll surfaces wiped and polished\nSkirting boards, light fittings, and vents\nInside windows cleaned\nCarpets vacuumed (steam cleaning available as add-on)',
  input_duration = 'Quoted per property',
  input_starting_price = 'Fixed price quoted on enquiry',
  input_meta_title = 'One-Off Deep Cleaning Edinburgh | NextShine Cleaning',
  text_meta_description = 'Comprehensive one-off deep cleaning for Edinburgh homes. Perfect for spring cleans, void periods, or property resets.'
WHERE hash_id = '30004';

UPDATE panel_services SET
  input_slug = 'post-construction-clean',
  text_full_description = 'After renovations or building works, your property needs specialist cleaning to remove construction dust, debris, paint marks, adhesive residue, and plaster dust that normal cleaning cannot handle.\n\nOur post-construction cleaning gets your property market-ready or move-in ready. We work with builders, developers, and homeowners across Edinburgh.',
  text_whats_included = 'Removal of all construction dust and debris\nPaint and adhesive residue removal\nWindow and glass cleaning\nAll surfaces wiped and polished\nFloor scrubbing and mopping\nBathroom and kitchen deep clean\nFinal inspection walkthrough',
  input_duration = 'Quoted per project',
  input_starting_price = 'Fixed price quoted on enquiry',
  input_meta_title = 'Post-Construction Cleaning Edinburgh | NextShine Cleaning',
  text_meta_description = 'Specialist post-construction and after-builders cleaning in Edinburgh. Dust, debris, and residue removal. Market-ready results.'
WHERE hash_id = '30005';

UPDATE panel_services SET
  input_slug = 'airbnb-short-let-cleaning',
  text_full_description = 'We provide fast, reliable turnaround cleans between short-let guests. We work to your check-out and check-in schedule — keeping your property in top condition, your ratings high, and your guests happy.\n\nWe can also provide linen changes, restocking essentials, and property checks as part of a managed package for Airbnb hosts and short-let operators.',
  text_whats_included = 'Full property clean between guests\nBathroom and kitchen deep clean\nBed linen change (linen supply available)\nToiletries and essentials restock check\nBins emptied and fresh liners\nAll surfaces wiped and floors cleaned\nDamage and maintenance issue reporting',
  input_duration = '1.5 – 3 hours per turnover',
  input_starting_price = 'From £21 per hour',
  input_meta_title = 'Airbnb & Short-Let Cleaning Edinburgh | NextShine Cleaning',
  text_meta_description = 'Reliable Airbnb and short-let turnover cleaning in Edinburgh. Fast turnarounds, linen changes, and guest-ready results.'
WHERE hash_id = '30006';


-- ─────────────────────────────────────────────────────
-- FAVICON (read_ table = view/edit only, no add/delete)
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS read_favicon (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  image_1 TEXT,
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO read_favicon (hash_id, image_1, visibility, date_created, time_created, created_by)
VALUES ('60001', '', 'show', '2026-04-13', '00:00:00', 'system');
