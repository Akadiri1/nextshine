-- =====================================================
-- NextShine Home Page — ADMC Framework Database Setup
-- Run this file against your database to create all
-- tables and seed data for the home page.
-- =====================================================

-- ─────────────────────────────────────────────────────
-- SITE COLORS
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS settings_site_colors (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  bgcolor_primary VARCHAR(100) DEFAULT '#00878A',
  bgcolor_primary_light VARCHAR(100) DEFAULT '#00B4B7',
  bgcolor_primary_pale VARCHAR(100) DEFAULT '#E0F5F5',
  bgcolor_secondary VARCHAR(100) DEFAULT '#1A335C',
  bgcolor_secondary_dark VARCHAR(100) DEFAULT '#111f3a',
  bgcolor_page VARCHAR(100) DEFAULT '#FFFFFF',
  bgcolor_surface VARCHAR(100) DEFAULT '#F8FAFC',
  bgcolor_surface_alt VARCHAR(100) DEFAULT '#F0F4F8',
  textcolor_body VARCHAR(100) DEFAULT '#1A1A2E',
  textcolor_muted VARCHAR(100) DEFAULT '#6B7280',
  textcolor_dark VARCHAR(100) DEFAULT '#374151',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings_site_colors (hash_id, bgcolor_primary, bgcolor_primary_light, bgcolor_primary_pale, bgcolor_secondary, bgcolor_secondary_dark, bgcolor_page, bgcolor_surface, bgcolor_surface_alt, textcolor_body, textcolor_muted, textcolor_dark, visibility, date_created, time_created, created_by)
VALUES ('10001', '#00878A', '#00B4B7', '#E0F5F5', '#1A335C', '#111f3a', '#FFFFFF', '#F8FAFC', '#F0F4F8', '#1A1A2E', '#6B7280', '#374151', 'show', '2026-04-09', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- HOME HERO
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS settings_home_hero (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_badge_text VARCHAR(255) DEFAULT '',
  input_headline_1 VARCHAR(255) DEFAULT '',
  input_headline_2 VARCHAR(255) DEFAULT '',
  input_headline_3 VARCHAR(255) DEFAULT '',
  text_description TEXT,
  input_cta_primary VARCHAR(255) DEFAULT '',
  input_cta_secondary VARCHAR(255) DEFAULT '',
  input_stat_1_value VARCHAR(100) DEFAULT '',
  input_stat_1_label VARCHAR(255) DEFAULT '',
  input_stat_2_value VARCHAR(100) DEFAULT '',
  input_stat_2_label VARCHAR(255) DEFAULT '',
  input_stat_3_value VARCHAR(100) DEFAULT '',
  input_stat_3_label VARCHAR(255) DEFAULT '',
  input_card_title VARCHAR(255) DEFAULT '',
  image_1 TEXT,
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings_home_hero (hash_id, input_badge_text, input_headline_1, input_headline_2, input_headline_3, text_description, input_cta_primary, input_cta_secondary, input_stat_1_value, input_stat_1_label, input_stat_2_value, input_stat_2_label, input_stat_3_value, input_stat_3_label, input_card_title, visibility, date_created, time_created, created_by)
VALUES ('10002', 'Now accepting new clients in Edinburgh', 'Edinburgh''s Most', 'Reliable Cleaning', 'Service', 'Fixed-price end-of-tenancy cleans. Competitive hourly rates for domestic and commercial work. Fully insured, family-run, and always professional.', 'Get a Free Quote', 'Call Us Now', '100%', 'Satisfaction Guarantee', '£130', 'EOT from (1-bed)', '£21/hr', 'Regular Domestic', 'Get an Instant Quote', 'show', '2026-04-09', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- TRUST STRIP
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS panel_trust_items (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_icon VARCHAR(255) DEFAULT '',
  input_text VARCHAR(255) DEFAULT '',
  input_order VARCHAR(255) DEFAULT '0',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO panel_trust_items (hash_id, input_icon, input_text, input_order, visibility, date_created, time_created, created_by) VALUES
('20001', 'fa-solid fa-shield-halved', 'Fully Insured (Public Liability)', '1', 'show', '2026-04-10', '00:00:00', 'system'),
('20002', 'fa-solid fa-circle-check', 'Vetted & Background-Checked', '2', 'show', '2026-04-10', '00:00:00', 'system'),
('20003', 'fa-solid fa-house', 'Landlord & Agent Specialists', '3', 'show', '2026-04-10', '00:00:00', 'system'),
('20004', 'fa-solid fa-sack-dollar', 'Fixed Prices — No Surprises', '4', 'show', '2026-04-10', '00:00:00', 'system'),
('20005', 'fa-solid fa-star', 'Satisfaction Guaranteed', '5', 'show', '2026-04-10', '00:00:00', 'system'),
('20006', 'fa-solid fa-location-dot', 'Edinburgh & Surrounding Areas', '6', 'show', '2026-04-10', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- SERVICES
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS settings_home_services (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_label VARCHAR(255) DEFAULT '',
  input_title VARCHAR(255) DEFAULT '',
  text_subtitle TEXT,
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings_home_services (hash_id, input_label, input_title, text_subtitle, visibility, date_created, time_created, created_by)
VALUES ('10003', 'What We Do', 'Cleaning Services Tailored to Your Needs', 'From full end-of-tenancy deep cleans to regular domestic and commercial contracts — we cover everything Edinburgh landlords and businesses need.', 'show', '2026-04-09', '00:00:00', 'system');

CREATE TABLE IF NOT EXISTS panel_services (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_icon VARCHAR(255) DEFAULT '',
  input_badge VARCHAR(255) DEFAULT '',
  input_title VARCHAR(255) DEFAULT '',
  text_description TEXT,
  input_price_tag VARCHAR(255) DEFAULT '',
  input_link_text VARCHAR(255) DEFAULT '',
  input_link_url VARCHAR(255) DEFAULT '',
  input_order VARCHAR(255) DEFAULT '0',
  bgcolor_card_start VARCHAR(100) DEFAULT '',
  bgcolor_card_end VARCHAR(100) DEFAULT '',
  image_1 TEXT,
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO panel_services (hash_id, input_icon, input_badge, input_title, text_description, input_price_tag, input_link_text, input_link_url, input_order, bgcolor_card_start, bgcolor_card_end, visibility, date_created, time_created, created_by) VALUES
('30001', 'fa-solid fa-broom', 'MOST POPULAR', 'End-of-Tenancy Deep Clean', 'Full letting-standard deep clean between tenancies. Every room, every appliance, every surface — to inspection standard. Fixed price, no hidden extras.', 'From £130 (1-bed)', 'View prices →', '#pricing', '1', '#1A335C', '#00878A', 'show', '2026-04-10', '00:00:00', 'system'),
('30002', 'fa-solid fa-house-chimney', 'REGULAR', 'Regular Domestic Cleaning', 'Weekly or fortnightly cleaning for residential properties. Same team every visit, consistent results, and flexible scheduling around your life.', '£21/hr (min. 2hrs)', 'View prices →', '#pricing', '2', '#1A335C', '#2d5a9e', 'show', '2026-04-10', '00:00:00', 'system'),
('30003', 'fa-solid fa-building', 'COMMERCIAL', 'Commercial & Office Cleaning', 'Regular morning, evening, or weekend cleans for offices, studios, clinics, and retail spaces. Flexible contracts to suit your business hours.', 'From £18–£20/hr', 'View prices →', '#pricing', '3', '#0f2440', '#1A335C', 'show', '2026-04-10', '00:00:00', 'system'),
('30004', 'fa-solid fa-wand-magic-sparkles', '', 'One-Off Deep Clean', 'Comprehensive deep clean for properties that need a reset. Perfect for void periods, spring cleans, or properties with long-term tenants.', 'Fixed price quoted', 'Get a quote →', '#contact', '4', '#00878A', '#00B4B7', 'show', '2026-04-10', '00:00:00', 'system'),
('30005', 'fa-solid fa-hammer', '', 'Post-Construction Clean', 'Deep clean following renovation or building works. Removes dust, debris, paint marks, and adhesive residue — market-ready or move-in ready.', 'Fixed price quoted', 'Get a quote →', '#contact', '5', '#00878A', '#005e61', 'show', '2026-04-10', '00:00:00', 'system'),
('30006', 'fa-solid fa-key', 'SHORT-LET', 'AirBnB & Short-Let Turnover', 'Fast, reliable turnaround cleans between guests. We work to your check-out and check-in schedule — keeping your ratings high and guests happy.', 'From £21/hr', 'Get a quote →', '#contact', '6', '#ff5a5f', '#c81d25', 'show', '2026-04-10', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- HOW IT WORKS
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS settings_home_how (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_label VARCHAR(255) DEFAULT '',
  input_title VARCHAR(255) DEFAULT '',
  text_subtitle TEXT,
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings_home_how (hash_id, input_label, input_title, text_subtitle, visibility, date_created, time_created, created_by)
VALUES ('10004', 'Simple Process', 'Getting Started is Easy', 'From first contact to spotless property in four simple steps. We handle everything — you just need to let us in.', 'show', '2026-04-09', '00:00:00', 'system');

CREATE TABLE IF NOT EXISTS panel_how_steps (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_step_number VARCHAR(255) DEFAULT '',
  input_title VARCHAR(255) DEFAULT '',
  text_description TEXT,
  input_order VARCHAR(255) DEFAULT '0',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO panel_how_steps (hash_id, input_step_number, input_title, text_description, input_order, visibility, date_created, time_created, created_by) VALUES
('31001', '1', 'Get in Touch', 'Call, email, or fill in our quick quote form. We respond within 3 hours during business hours — often much faster.', '1', 'show', '2026-04-09', '00:00:00', 'system'),
('31002', '2', 'Receive Your Quote', 'We send a clear, written quote — fixed price for EOT, or hourly rate for regular work. No hidden fees. No surprises.', '2', 'show', '2026-04-09', '00:00:00', 'system'),
('31003', '3', 'Confirm & Book', 'Confirm by email or WhatsApp. We agree a date, time, and access. For letting agents, we''re happy to hold keys for regular bookings.', '3', 'show', '2026-04-09', '00:00:00', 'system'),
('31004', '4', 'We Deliver', 'We arrive on time, complete the clean to your spec, and follow up within 24 hours. If anything isn''t right, we return at no extra charge.', '4', 'show', '2026-04-09', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- PRICING
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS settings_home_pricing (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_label VARCHAR(255) DEFAULT '',
  input_title VARCHAR(255) DEFAULT '',
  text_subtitle TEXT,
  input_tab_eot VARCHAR(255) DEFAULT '',
  input_tab_hourly VARCHAR(255) DEFAULT '',
  text_eot_note TEXT,
  input_agent_note VARCHAR(255) DEFAULT '',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings_home_pricing (hash_id, input_label, input_title, text_subtitle, input_tab_eot, input_tab_hourly, text_eot_note, input_agent_note, visibility, date_created, time_created, created_by)
VALUES ('10005', 'Transparent Pricing', 'Clear Prices. No Hidden Charges.', 'We use fixed prices for end-of-tenancy cleans — the industry standard that landlords and agents prefer. Hourly rates for regular and commercial work.', 'End-of-Tenancy (Fixed Price)', 'Regular & Commercial (Hourly)', 'All fixed prices include all cleaning products and equipment. No VAT. No call-out charges. We clean until the job is done — not until the clock runs out.', 'Letting agent? Ask about our portfolio rates for multiple properties.', 'show', '2026-04-09', '00:00:00', 'system');

CREATE TABLE IF NOT EXISTS panel_pricing_eot (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_property VARCHAR(255) DEFAULT '',
  input_detail VARCHAR(255) DEFAULT '',
  input_duration VARCHAR(255) DEFAULT '',
  input_price VARCHAR(255) DEFAULT '',
  input_market VARCHAR(255) DEFAULT '',
  input_order VARCHAR(255) DEFAULT '0',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO panel_pricing_eot (hash_id, input_property, input_detail, input_duration, input_price, input_market, input_order, visibility, date_created, time_created, created_by) VALUES
('32001', 'Studio / Bedsit', 'Up to 1 room + kitchen + bathroom', '2.5 – 3 hrs', '£100 – £120', '£90 – £150', '1', 'show', '2026-04-09', '00:00:00', 'system'),
('32002', '1 Bedroom Flat', '1 bed + living room + kitchen + bathroom', '3 – 4 hrs', '£130 – £160', '£120 – £200', '2', 'show', '2026-04-09', '00:00:00', 'system'),
('32003', '2 Bedroom Flat / House', '2 beds + living room + kitchen + bathroom(s)', '4 – 5.5 hrs', '£175 – £220', '£165 – £250', '3', 'show', '2026-04-09', '00:00:00', 'system'),
('32004', '3 Bedroom House', '3 beds + multiple living spaces + kitchen + bathrooms', '5.5 – 7 hrs', '£230 – £285', '£220 – £320', '4', 'show', '2026-04-09', '00:00:00', 'system'),
('32005', '4 Bedroom House', '4+ beds + full family home', '7 – 9 hrs', '£285 – £345', '£270 – £380', '5', 'show', '2026-04-09', '00:00:00', 'system');

CREATE TABLE IF NOT EXISTS panel_pricing_hourly (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_icon VARCHAR(255) DEFAULT '',
  input_title VARCHAR(255) DEFAULT '',
  input_price VARCHAR(255) DEFAULT '',
  input_per VARCHAR(255) DEFAULT '',
  input_minimum VARCHAR(255) DEFAULT '',
  input_badge VARCHAR(255) DEFAULT '',
  input_cta_text VARCHAR(255) DEFAULT '',
  input_cta_url VARCHAR(255) DEFAULT '',
  input_featured VARCHAR(255) DEFAULT 'no',
  input_order VARCHAR(255) DEFAULT '0',
  add_pricing_features TEXT,
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO panel_pricing_hourly (hash_id, input_icon, input_title, input_price, input_per, input_minimum, input_badge, input_cta_text, input_cta_url, input_featured, input_order, visibility, date_created, time_created, created_by) VALUES
('33001', 'fa-solid fa-house-chimney', 'Regular Domestic', '21', 'per hour', 'Minimum 2 hours · Weekly or fortnightly', '', 'Book Now', '#contact', 'no', '1', 'show', '2026-04-10', '00:00:00', 'system'),
('33002', 'fa-solid fa-house-circle-check', 'Void / Maintenance', '21', 'per hour', 'Minimum 2 hours · One-off or recurring', 'Most Requested', 'Book Now', '#contact', 'yes', '2', 'show', '2026-04-10', '00:00:00', 'system'),
('33003', 'fa-solid fa-briefcase', 'Commercial / Office', '18–20', 'per hour', 'Minimum 2 hours · Flexible scheduling', '', 'Get a Quote', '#contact', 'no', '3', 'show', '2026-04-10', '00:00:00', 'system');

CREATE TABLE IF NOT EXISTS addition_pricing_features (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_feature VARCHAR(255) DEFAULT '',
  tb VARCHAR(255) DEFAULT '',
  tb_link VARCHAR(255) DEFAULT '',
  input_order VARCHAR(255) DEFAULT '0',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO addition_pricing_features (hash_id, input_feature, tb, tb_link, input_order, visibility, date_created, time_created, created_by) VALUES
('40001', 'All rooms, surfaces and floors', 'panel_pricing_hourly', '33001', '1', 'show', '2026-04-09', '00:00:00', 'system'),
('40002', 'Kitchen and bathroom included', 'panel_pricing_hourly', '33001', '2', 'show', '2026-04-09', '00:00:00', 'system'),
('40003', 'Same team every visit', 'panel_pricing_hourly', '33001', '3', 'show', '2026-04-09', '00:00:00', 'system'),
('40004', 'All cleaning products included', 'panel_pricing_hourly', '33001', '4', 'show', '2026-04-09', '00:00:00', 'system'),
('40005', 'No VAT · No hidden fees', 'panel_pricing_hourly', '33001', '5', 'show', '2026-04-09', '00:00:00', 'system'),
('40006', 'Vacant property maintenance', 'panel_pricing_hourly', '33002', '1', 'show', '2026-04-09', '00:00:00', 'system'),
('40007', 'Ideal during marketing periods', 'panel_pricing_hourly', '33002', '2', 'show', '2026-04-09', '00:00:00', 'system'),
('40008', 'Short-notice availability', 'panel_pricing_hourly', '33002', '3', 'show', '2026-04-09', '00:00:00', 'system'),
('40009', 'Key holding available', 'panel_pricing_hourly', '33002', '4', 'show', '2026-04-09', '00:00:00', 'system'),
('40010', 'Letting agent accounts welcome', 'panel_pricing_hourly', '33002', '5', 'show', '2026-04-09', '00:00:00', 'system'),
('40011', 'Offices, studios, clinics', 'panel_pricing_hourly', '33003', '1', 'show', '2026-04-09', '00:00:00', 'system'),
('40012', 'Morning, evening & weekend slots', 'panel_pricing_hourly', '33003', '2', 'show', '2026-04-09', '00:00:00', 'system'),
('40013', 'Monthly contract rates available', 'panel_pricing_hourly', '33003', '3', 'show', '2026-04-09', '00:00:00', 'system'),
('40014', 'All products supplied', 'panel_pricing_hourly', '33003', '4', 'show', '2026-04-09', '00:00:00', 'system'),
('40015', 'Invoiced monthly for ease', 'panel_pricing_hourly', '33003', '5', 'show', '2026-04-09', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- WHY CHOOSE US
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS settings_home_why (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_label VARCHAR(255) DEFAULT '',
  input_title VARCHAR(255) DEFAULT '',
  text_subtitle TEXT,
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings_home_why (hash_id, input_label, input_title, text_subtitle, visibility, date_created, time_created, created_by)
VALUES ('10006', 'Why NextShine', 'Built on Reliability. Driven by Standards.', 'We know what landlords and businesses need. We have built NextShine Cleaning to deliver exactly that — every single time.', 'show', '2026-04-09', '00:00:00', 'system');

CREATE TABLE IF NOT EXISTS panel_why_us (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_icon VARCHAR(255) DEFAULT '',
  input_title VARCHAR(255) DEFAULT '',
  text_description TEXT,
  input_order VARCHAR(255) DEFAULT '0',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO panel_why_us (hash_id, input_icon, input_title, text_description, input_order, visibility, date_created, time_created, created_by) VALUES
('34001', 'fa-solid fa-clipboard-list', 'Fixed Prices on EOT Cleans', 'No hourly uncertainty for end-of-tenancy work. You know the full cost before we start. We quote per property, not per hour — just like the market expects.', '1', 'show', '2026-04-10', '00:00:00', 'system'),
('34002', 'fa-solid fa-clock', 'We Turn Up On Time', 'Every booking confirmed in advance. We communicate proactively if anything changes. The number one complaint about cleaning companies is no-shows — we treat your time with the same respect we''d expect.', '2', 'show', '2026-04-10', '00:00:00', 'system'),
('34003', 'fa-solid fa-house-chimney-window', 'Letting-Standard Results', 'We understand inventory checks, deposit disputes, and what letting agents look for. Our EOT cleans are done to a professional checklist that satisfies agents across Edinburgh.', '3', 'show', '2026-04-10', '00:00:00', 'system'),
('34004', 'fa-solid fa-shield-halved', 'Fully Insured', 'Public Liability Insurance as standard. You are protected from day one. Many Edinburgh letting agents require proof of insurance before accepting a cleaning contractor — we have you covered.', '4', 'show', '2026-04-10', '00:00:00', 'system'),
('34005', 'fa-solid fa-people-roof', 'Family-Run. Personal Service.', 'NextShine Cleaning is run by Tosin and [Wife''s Name]. You deal directly with the owners — not a call centre, not a stranger each time. Our reputation is everything to us.', '5', 'show', '2026-04-10', '00:00:00', 'system'),
('34006', 'fa-solid fa-comments', 'Satisfaction Guaranteed', 'If you are not satisfied with any aspect of our work, we return to put it right at no extra charge. No argument, no delay. That is our promise to every client.', '6', 'show', '2026-04-10', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- ABOUT
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS settings_home_about (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_label VARCHAR(255) DEFAULT '',
  input_title VARCHAR(255) DEFAULT '',
  text_quote TEXT,
  input_quote_author VARCHAR(255) DEFAULT '',
  text_description TEXT,
  input_floatcard_title VARCHAR(255) DEFAULT '',
  input_floatcard_label VARCHAR(255) DEFAULT '',
  input_cta_primary_text VARCHAR(255) DEFAULT '',
  input_cta_primary_url VARCHAR(255) DEFAULT '',
  input_cta_secondary_text VARCHAR(255) DEFAULT '',
  input_cta_secondary_url VARCHAR(255) DEFAULT '',
  image_1 TEXT,
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings_home_about (hash_id, input_label, input_title, text_quote, input_quote_author, text_description, input_floatcard_title, input_floatcard_label, input_cta_primary_text, input_cta_primary_url, input_cta_secondary_text, input_cta_secondary_url, visibility, date_created, time_created, created_by)
VALUES ('10007', 'Our Story', 'A Business Built on Doing Things Right', 'We started NextShine Cleaning because we kept hearing the same thing from landlords and letting agents — they couldn''t find a cleaner they could actually rely on. We decided to be the answer to that problem.', '— Tosin, Co-founder', 'NextShine Cleaning is a trading division of NextShine Group Ltd, a Scottish company founded by Tosin and [Wife''s Name]. We are husband and wife, and we have put our reputation behind every job we take on.', 'Edinburgh', 'Based & Operating Locally', 'Work With Us', '#contact', 'Why We''re Different', '#why', 'show', '2026-04-09', '00:00:00', 'system');

CREATE TABLE IF NOT EXISTS panel_about_bullets (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_text VARCHAR(255) DEFAULT '',
  input_order VARCHAR(255) DEFAULT '0',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO panel_about_bullets (hash_id, input_text, input_order, visibility, date_created, time_created, created_by) VALUES
('35001', 'Directly operated by the founders — no subcontractors in Year 1', '1', 'show', '2026-04-09', '00:00:00', 'system'),
('35002', 'Fully insured, COSHH-compliant, and registered with the ICO', '2', 'show', '2026-04-09', '00:00:00', 'system'),
('35003', 'Embedded in a wider property and facilities group — we understand the sector', '3', 'show', '2026-04-09', '00:00:00', 'system'),
('35004', 'Committed to Edinburgh''s landlord and letting agent community long-term', '4', 'show', '2026-04-09', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- COVERAGE
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS settings_home_coverage (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_label VARCHAR(255) DEFAULT '',
  input_title VARCHAR(255) DEFAULT '',
  text_description TEXT,
  input_city_label VARCHAR(255) DEFAULT '',
  input_surrounding_label VARCHAR(255) DEFAULT '',
  input_footer_note VARCHAR(255) DEFAULT '',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings_home_coverage (hash_id, input_label, input_title, text_description, input_city_label, input_surrounding_label, input_footer_note, visibility, date_created, time_created, created_by)
VALUES ('10008', 'Where We Work', 'Edinburgh & Surrounding Areas', 'We cover Edinburgh city and the surrounding commuter belt. If you''re not sure whether we cover your area, just give us a call — we''d love to help if we can.', 'Edinburgh City', 'Surrounding Areas', 'Don''t see your area? Contact us — we may still cover you.', 'show', '2026-04-09', '00:00:00', 'system');

CREATE TABLE IF NOT EXISTS panel_coverage_areas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_name VARCHAR(255) DEFAULT '',
  input_group VARCHAR(255) DEFAULT '',
  input_order VARCHAR(255) DEFAULT '0',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO panel_coverage_areas (hash_id, input_name, input_group, input_order, visibility, date_created, time_created, created_by) VALUES
('36001', 'Edinburgh City Centre', 'city', '1', 'show', '2026-04-09', '00:00:00', 'system'),
('36002', 'Leith', 'city', '2', 'show', '2026-04-09', '00:00:00', 'system'),
('36003', 'Morningside', 'city', '3', 'show', '2026-04-09', '00:00:00', 'system'),
('36004', 'Marchmont', 'city', '4', 'show', '2026-04-09', '00:00:00', 'system'),
('36005', 'Stockbridge', 'city', '5', 'show', '2026-04-09', '00:00:00', 'system'),
('36006', 'Bruntsfield', 'city', '6', 'show', '2026-04-09', '00:00:00', 'system'),
('36007', 'Newington', 'city', '7', 'show', '2026-04-09', '00:00:00', 'system'),
('36008', 'Portobello', 'city', '8', 'show', '2026-04-09', '00:00:00', 'system'),
('36009', 'Musselburgh', 'surrounding', '1', 'show', '2026-04-09', '00:00:00', 'system'),
('36010', 'Livingston', 'surrounding', '2', 'show', '2026-04-09', '00:00:00', 'system'),
('36011', 'Midlothian', 'surrounding', '3', 'show', '2026-04-09', '00:00:00', 'system'),
('36012', 'Dalkeith', 'surrounding', '4', 'show', '2026-04-09', '00:00:00', 'system'),
('36013', 'Bonnyrigg', 'surrounding', '5', 'show', '2026-04-09', '00:00:00', 'system'),
('36014', 'Penicuik', 'surrounding', '6', 'show', '2026-04-09', '00:00:00', 'system'),
('36015', 'Linlithgow', 'surrounding', '7', 'show', '2026-04-09', '00:00:00', 'system'),
('36016', 'Bathgate', 'surrounding', '8', 'show', '2026-04-09', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- TESTIMONIALS
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS settings_home_testimonials (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_label VARCHAR(255) DEFAULT '',
  input_title VARCHAR(255) DEFAULT '',
  text_subtitle TEXT,
  input_google_score VARCHAR(100) DEFAULT '',
  input_google_text VARCHAR(255) DEFAULT '',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings_home_testimonials (hash_id, input_label, input_title, text_subtitle, input_google_score, input_google_text, visibility, date_created, time_created, created_by)
VALUES ('10009', 'Client Reviews', 'What Our Clients Say', 'We let our work speak for itself. Here''s what landlords, letting agents, and businesses in Edinburgh say about NextShine Cleaning.', '5.0', '[X] reviews on Google · [Add your real rating]', 'show', '2026-04-09', '00:00:00', 'system');

CREATE TABLE IF NOT EXISTS panel_testimonials (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  text_review TEXT,
  input_author_name VARCHAR(255) DEFAULT '',
  input_author_role VARCHAR(255) DEFAULT '',
  input_author_initials VARCHAR(255) DEFAULT '',
  input_source VARCHAR(255) DEFAULT '',
  bgcolor_avatar VARCHAR(100) DEFAULT '#00878A',
  input_order VARCHAR(255) DEFAULT '0',
  image_1 TEXT,
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO panel_testimonials (hash_id, text_review, input_author_name, input_author_role, input_author_initials, input_source, bgcolor_avatar, input_order, visibility, date_created, time_created, created_by) VALUES
('37001', 'Absolutely outstanding end-of-tenancy clean. The property was immaculate — better than when my tenants moved in. I''ve already booked them for two more properties.', 'S. Laing', 'Private Landlord, Edinburgh', 'SL', 'Google Review — [Replace with real review]', '#00878A', '1', 'show', '2026-04-09', '00:00:00', 'system'),
('37002', 'We use NextShine Cleaning for all our end-of-tenancy work. Reliable, professional, and they understand exactly what letting agents need. Communication is excellent.', 'J. Robertson', 'Letting Agent, Edinburgh', 'JR', 'Google Review — [Replace with real review]', '#1A335C', '2', 'show', '2026-04-09', '00:00:00', 'system'),
('37003', 'Our office has never looked this good. Tosin and the team are punctual, thorough, and genuinely great people to work with. Highly recommended for any business.', 'A. Murray', 'Business Owner, Edinburgh', 'AM', 'Google Review — [Replace with real review]', '#7c3aed', '3', 'show', '2026-04-09', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- CONTACT
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS settings_home_contact (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_label VARCHAR(255) DEFAULT '',
  input_title VARCHAR(255) DEFAULT '',
  text_subtitle TEXT,
  input_phone_label VARCHAR(255) DEFAULT '',
  input_email_label VARCHAR(255) DEFAULT '',
  input_response_label VARCHAR(255) DEFAULT '',
  input_response_value VARCHAR(255) DEFAULT '',
  input_coverage_label VARCHAR(255) DEFAULT '',
  input_coverage_value VARCHAR(255) DEFAULT '',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings_home_contact (hash_id, input_label, input_title, text_subtitle, input_phone_label, input_email_label, input_response_label, input_response_value, input_coverage_label, input_coverage_value, visibility, date_created, time_created, created_by)
VALUES ('10010', 'Get in Touch', 'Ready to Book? Let''s Talk.', 'Fill in the form and we''ll get back to you within 3 hours. Or call us directly for an instant quote.', 'Phone / WhatsApp', 'Email', 'Response Time', 'Within 3 hours · Mon–Sat 7am–7pm', 'Coverage', 'Edinburgh & Surrounding Areas', 'show', '2026-04-09', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- FOOTER
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS settings_home_footer (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  text_description TEXT,
  input_copyright VARCHAR(255) DEFAULT '',
  input_services_title VARCHAR(255) DEFAULT '',
  input_company_title VARCHAR(255) DEFAULT '',
  input_contact_title VARCHAR(255) DEFAULT '',
  input_whatsapp_text VARCHAR(255) DEFAULT '',
  input_hours_text VARCHAR(255) DEFAULT '',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings_home_footer (hash_id, text_description, input_copyright, input_services_title, input_company_title, input_contact_title, input_whatsapp_text, input_hours_text, visibility, date_created, time_created, created_by)
VALUES ('10011', 'Professional cleaning services for landlords, letting agents, and businesses across Edinburgh and surrounding areas. Fully insured. Fixed prices. Family-run. Built on trust.', '© 2026 NextShine Group Ltd. All rights reserved. Company registered in Scotland.', 'Services', 'Company', 'Contact', 'WhatsApp Available', 'Mon–Sat · 7am–7pm', 'show', '2026-04-09', '00:00:00', 'system');

CREATE TABLE IF NOT EXISTS panel_footer_links (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_name VARCHAR(255) DEFAULT '',
  input_link VARCHAR(255) DEFAULT '',
  input_group VARCHAR(255) DEFAULT '',
  input_order VARCHAR(255) DEFAULT '0',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO panel_footer_links (hash_id, input_name, input_link, input_group, input_order, visibility, date_created, time_created, created_by) VALUES
('38001', 'End-of-Tenancy Clean', '#services', 'services', '1', 'show', '2026-04-09', '00:00:00', 'system'),
('38002', 'Regular Domestic', '#services', 'services', '2', 'show', '2026-04-09', '00:00:00', 'system'),
('38003', 'Office & Commercial', '#services', 'services', '3', 'show', '2026-04-09', '00:00:00', 'system'),
('38004', 'One-Off Deep Clean', '#services', 'services', '4', 'show', '2026-04-09', '00:00:00', 'system'),
('38005', 'Post-Construction', '#services', 'services', '5', 'show', '2026-04-09', '00:00:00', 'system'),
('38006', 'AirBnB Turnovers', '#services', 'services', '6', 'show', '2026-04-09', '00:00:00', 'system'),
('38007', 'About Us', '#about', 'company', '1', 'show', '2026-04-09', '00:00:00', 'system'),
('38008', 'Why NextShine', '#why', 'company', '2', 'show', '2026-04-09', '00:00:00', 'system'),
('38009', 'Coverage Area', '#coverage', 'company', '3', 'show', '2026-04-09', '00:00:00', 'system'),
('38010', 'Reviews', '#testimonials', 'company', '4', 'show', '2026-04-09', '00:00:00', 'system'),
('38011', 'Contact Us', '#contact', 'company', '5', 'show', '2026-04-09', '00:00:00', 'system'),
('38012', 'Privacy Policy', '#', 'legal', '1', 'show', '2026-04-09', '00:00:00', 'system'),
('38013', 'Terms of Service', '#', 'legal', '2', 'show', '2026-04-09', '00:00:00', 'system'),
('38014', 'Cookie Policy', '#', 'legal', '3', 'show', '2026-04-09', '00:00:00', 'system');

CREATE TABLE IF NOT EXISTS panel_footer_socials (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_icon VARCHAR(255) DEFAULT '',
  input_label VARCHAR(255) DEFAULT '',
  input_link VARCHAR(255) DEFAULT '',
  input_order VARCHAR(255) DEFAULT '0',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO panel_footer_socials (hash_id, input_icon, input_label, input_link, input_order, visibility, date_created, time_created, created_by) VALUES
('39001', 'fa-brands fa-facebook-f', 'Facebook', '#', '1', 'show', '2026-04-10', '00:00:00', 'system'),
('39002', 'fa-brands fa-instagram', 'Instagram', '#', '2', 'show', '2026-04-10', '00:00:00', 'system'),
('39003', 'fa-brands fa-whatsapp', 'WhatsApp', '#', '3', 'show', '2026-04-10', '00:00:00', 'system'),
('39004', 'fa-brands fa-google', 'Google', '#', '4', 'show', '2026-04-10', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- NAVIGATION
-- ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS panel_home_nav (
  id INT PRIMARY KEY AUTO_INCREMENT,
  hash_id VARCHAR(255) NOT NULL,
  input_name VARCHAR(255) DEFAULT '',
  input_link VARCHAR(255) DEFAULT '',
  input_order VARCHAR(255) DEFAULT '0',
  visibility VARCHAR(50) DEFAULT 'show',
  date_created DATE NOT NULL,
  time_created TIME NOT NULL,
  created_by VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO panel_home_nav (hash_id, input_name, input_link, input_order, visibility, date_created, time_created, created_by) VALUES
('50001', 'Services', '#services', '1', 'show', '2026-04-09', '00:00:00', 'system'),
('50002', 'Pricing', '#pricing', '2', 'show', '2026-04-09', '00:00:00', 'system'),
('50003', 'About Us', '#about', '3', 'show', '2026-04-09', '00:00:00', 'system'),
('50004', 'Coverage', '#coverage', '4', 'show', '2026-04-09', '00:00:00', 'system'),
('50005', 'Reviews', '#testimonials', '5', 'show', '2026-04-09', '00:00:00', 'system');


-- ─────────────────────────────────────────────────────
-- MIGRATION: Replace emoji icons with FontAwesome classes
-- (safe to re-run on existing installs)
-- ─────────────────────────────────────────────────────
UPDATE panel_trust_items SET input_icon = 'fa-solid fa-shield-halved' WHERE hash_id = '20001';
UPDATE panel_trust_items SET input_icon = 'fa-solid fa-circle-check' WHERE hash_id = '20002';
UPDATE panel_trust_items SET input_icon = 'fa-solid fa-house' WHERE hash_id = '20003';
UPDATE panel_trust_items SET input_icon = 'fa-solid fa-sack-dollar' WHERE hash_id = '20004';
UPDATE panel_trust_items SET input_icon = 'fa-solid fa-star' WHERE hash_id = '20005';
UPDATE panel_trust_items SET input_icon = 'fa-solid fa-location-dot' WHERE hash_id = '20006';

UPDATE panel_services SET input_icon = 'fa-solid fa-broom' WHERE hash_id = '30001';
UPDATE panel_services SET input_icon = 'fa-solid fa-house-chimney' WHERE hash_id = '30002';
UPDATE panel_services SET input_icon = 'fa-solid fa-building' WHERE hash_id = '30003';
UPDATE panel_services SET input_icon = 'fa-solid fa-wand-magic-sparkles' WHERE hash_id = '30004';
UPDATE panel_services SET input_icon = 'fa-solid fa-hammer' WHERE hash_id = '30005';
UPDATE panel_services SET input_icon = 'fa-solid fa-key' WHERE hash_id = '30006';

UPDATE panel_pricing_hourly SET input_icon = 'fa-solid fa-house-chimney' WHERE hash_id = '33001';
UPDATE panel_pricing_hourly SET input_icon = 'fa-solid fa-house-circle-check' WHERE hash_id = '33002';
UPDATE panel_pricing_hourly SET input_icon = 'fa-solid fa-briefcase' WHERE hash_id = '33003';

UPDATE panel_why_us SET input_icon = 'fa-solid fa-clipboard-list' WHERE hash_id = '34001';
UPDATE panel_why_us SET input_icon = 'fa-solid fa-clock' WHERE hash_id = '34002';
UPDATE panel_why_us SET input_icon = 'fa-solid fa-house-chimney-window' WHERE hash_id = '34003';
UPDATE panel_why_us SET input_icon = 'fa-solid fa-shield-halved' WHERE hash_id = '34004';
UPDATE panel_why_us SET input_icon = 'fa-solid fa-people-roof' WHERE hash_id = '34005';
UPDATE panel_why_us SET input_icon = 'fa-solid fa-comments' WHERE hash_id = '34006';

UPDATE panel_footer_socials SET input_icon = 'fa-brands fa-facebook-f' WHERE hash_id = '39001';
UPDATE panel_footer_socials SET input_icon = 'fa-brands fa-instagram' WHERE hash_id = '39002';
UPDATE panel_footer_socials SET input_icon = 'fa-brands fa-whatsapp' WHERE hash_id = '39003';
UPDATE panel_footer_socials SET input_icon = 'fa-brands fa-google' WHERE hash_id = '39004';
