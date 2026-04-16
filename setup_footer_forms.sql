-- =====================================================
-- NextShine — Footer Service Links + Admin-Editable Form Selections
-- Run against your existing database
-- =====================================================


-- ─────────────────────────────────────────────────────
-- 1. FORM SERVICE OPTIONS (selection_ = add/edit/delete from admin)
--    Replaces the hardcoded <option> list in the contact/quote form
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS selection_form_services (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_name VARCHAR(255) DEFAULT '',
  input_order VARCHAR(255) DEFAULT '0',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO selection_form_services (hash_id, input_name, input_order, visibility, date_created, time_created, created_by) VALUES
('70001', 'End-of-Tenancy Clean (Fixed Price)',    '1', 'show', '2026-04-16', '00:00:00', 'system'),
('70002', 'Regular Domestic Cleaning (Hourly)',     '2', 'show', '2026-04-16', '00:00:00', 'system'),
('70003', 'Commercial / Office Cleaning',           '3', 'show', '2026-04-16', '00:00:00', 'system'),
('70004', 'One-Off Deep Clean',                     '4', 'show', '2026-04-16', '00:00:00', 'system'),
('70005', 'Post-Construction / Renovation Clean',   '5', 'show', '2026-04-16', '00:00:00', 'system'),
('70006', 'AirBnB / Short-Let Turnover',            '6', 'show', '2026-04-16', '00:00:00', 'system'),
('70007', 'Void Period Maintenance Clean',          '7', 'show', '2026-04-16', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- 2. FORM PROPERTY SIZE OPTIONS (selection_ = add/edit/delete from admin)
--    Replaces the hardcoded <option> list in the contact/quote form
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS selection_form_property_sizes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_name VARCHAR(255) DEFAULT '',
  input_order VARCHAR(255) DEFAULT '0',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO selection_form_property_sizes (hash_id, input_name, input_order, visibility, date_created, time_created, created_by) VALUES
('71001', 'Studio / Bedsit',      '1', 'show', '2026-04-16', '00:00:00', 'system'),
('71002', '1 Bedroom',            '2', 'show', '2026-04-16', '00:00:00', 'system'),
('71003', '2 Bedrooms',           '3', 'show', '2026-04-16', '00:00:00', 'system'),
('71004', '3 Bedrooms',           '4', 'show', '2026-04-16', '00:00:00', 'system'),
('71005', '4+ Bedrooms',          '5', 'show', '2026-04-16', '00:00:00', 'system'),
('71006', 'Office / Commercial',  '6', 'show', '2026-04-16', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- 3. FOOTER SERVICE LINKS → Direct Detail Pages
--    Update existing footer service links to point to
--    their corresponding service detail pages.
--    (The PHP code no longer uses these for rendering,
--    but we update them for data consistency.)
-- ─────────────────────────────────────────────────────
UPDATE panel_footer_links SET input_link = '/services/30001/end-of-tenancy-clean'       WHERE hash_id = '38001';
UPDATE panel_footer_links SET input_link = '/services/30002/regular-domestic-cleaning'   WHERE hash_id = '38002';
UPDATE panel_footer_links SET input_link = '/services/30003/commercial-office-cleaning'  WHERE hash_id = '38003';
UPDATE panel_footer_links SET input_link = '/services/30004/one-off-deep-clean'          WHERE hash_id = '38004';
UPDATE panel_footer_links SET input_link = '/services/30005/post-construction-clean'     WHERE hash_id = '38005';
UPDATE panel_footer_links SET input_link = '/services/30006/airbnb-short-let-cleaning'   WHERE hash_id = '38006';
