<?php
$siteColorsArr = selectContent($conn, "settings_site_colors", ["visibility" => "show"]);
$sc = !empty($siteColorsArr) ? $siteColorsArr[0] : [];
?>
:root {
  --navy:       <?= $sc['bgcolor_secondary'] ?? '#1A335C' ?>;
  --navy-dark:  <?= $sc['bgcolor_secondary_dark'] ?? '#111f3a' ?>;
  --teal:       <?= $sc['bgcolor_primary'] ?? '#00878A' ?>;
  --teal-light: <?= $sc['bgcolor_primary_light'] ?? '#00B4B7' ?>;
  --teal-pale:  <?= $sc['bgcolor_primary_pale'] ?? '#E0F5F5' ?>;
  --white:      <?= $sc['bgcolor_page'] ?? '#FFFFFF' ?>;
  --off-white:  <?= $sc['bgcolor_surface'] ?? '#F8FAFC' ?>;
  --grey-light: <?= $sc['bgcolor_surface_alt'] ?? '#F0F4F8' ?>;
  --grey:       <?= $sc['textcolor_muted'] ?? '#6B7280' ?>;
  --grey-dark:  <?= $sc['textcolor_dark'] ?? '#374151' ?>;
  --text:       <?= $sc['textcolor_body'] ?? '#1A1A2E' ?>;
  --shadow-sm:  0 1px 3px rgba(0,0,0,0.08);
  --shadow-md:  0 4px 16px rgba(0,0,0,0.10);
  --shadow-lg:  0 8px 32px rgba(0,0,0,0.14);
  --radius:     12px;
  --radius-lg:  20px;
  --transition: all 0.25s ease;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; font-size: 16px; }
body { font-family: 'Inter', sans-serif; color: var(--text); background: var(--white); line-height: 1.6; overflow-x: hidden; }
img  { max-width: 100%; display: block; }
a    { text-decoration: none; color: inherit; }
ul   { list-style: none; }

.container { max-width: 1140px; margin: 0 auto; padding: 0 24px; }
#navbar .container { max-width: 1260px; }
.section    { padding: 88px 0; }
.section-sm { padding: 56px 0; }

/* TYPOGRAPHY */
h1, h2, h3, h4 { font-family: 'Poppins', sans-serif; line-height: 1.2; }
.section-label {
  display: inline-block;
  font-size: 0.78rem;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--teal);
  margin-bottom: 10px;
}
.section-title {
  font-size: clamp(1.75rem, 3vw, 2.5rem);
  font-weight: 700;
  color: var(--navy);
  margin-bottom: 16px;
}
.section-subtitle {
  font-size: 1.05rem;
  color: var(--grey);
  max-width: 580px;
  line-height: 1.7;
}
.section-header { margin-bottom: 52px; }
.section-header.center { text-align: center; }
.section-header.center .section-subtitle { margin: 0 auto; }

/* BUTTONS */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 14px 28px;
  border-radius: 50px;
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  transition: var(--transition);
  border: 2px solid transparent;
}
.btn-primary {
  background: var(--teal);
  color: var(--white);
  border-color: var(--teal);
}
.btn-primary:hover {
  background: var(--teal-light);
  border-color: var(--teal-light);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0,135,138,0.35);
}
.btn-outline {
  background: transparent;
  color: var(--white);
  border-color: rgba(255,255,255,0.6);
}
.btn-outline:hover {
  background: rgba(255,255,255,0.12);
  border-color: var(--white);
}
.btn-navy {
  background: var(--navy);
  color: var(--white);
  border-color: var(--navy);
}
.btn-navy:hover {
  background: var(--navy-dark);
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}
.btn-lg { padding: 17px 36px; font-size: 1.05rem; }

/* NAVIGATION */
#navbar {
  position: fixed;
  top: 0; left: 0; right: 0;
  z-index: 1000;
  transition: var(--transition);
  padding: 20px 0;
}
#navbar.scrolled {
  background: var(--white);
  box-shadow: var(--shadow-md);
  padding: 12px 0;
}
.nav-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.nav-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-shrink: 0;
}
.nav-logo-icon {
  width: 38px; height: 38px;
  background: var(--teal);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Poppins', sans-serif;
  font-weight: 800;
  font-size: 1.1rem;
  color: var(--white);
  letter-spacing: -1px;
}
.nav-logo-text {
  display: flex;
  flex-direction: column;
  line-height: 1;
}
.nav-logo-name {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  font-size: 1.15rem;
  color: var(--white);
  transition: var(--transition);
}
.nav-logo-tag {
  font-size: 0.65rem;
  font-weight: 500;
  color: rgba(255,255,255,0.7);
  letter-spacing: 0.05em;
  transition: var(--transition);
}
#navbar.scrolled .nav-logo-name { color: var(--navy); }
#navbar.scrolled .nav-logo-tag  { color: var(--grey); }

.nav-links {
  display: flex;
  align-items: center;
  gap: 24px;
  flex-shrink: 1;
}
.nav-links a {
  font-size: 0.9rem;
  font-weight: 500;
  color: rgba(255,255,255,0.85);
  transition: var(--transition);
}
.nav-links a:hover { color: var(--white); }
#navbar.scrolled .nav-links a { color: var(--grey-dark); }
#navbar.scrolled .nav-links a:hover { color: var(--teal); }

.nav-cta { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
.nav-phone {
  font-size: 0.88rem;
  font-weight: 600;
  color: rgba(255,255,255,0.9);
  transition: var(--transition);
}
#navbar.scrolled .nav-phone { color: var(--navy); }

.hamburger {
  display: none;
  flex-direction: column;
  gap: 5px;
  cursor: pointer;
  padding: 4px;
}
.hamburger span {
  display: block;
  width: 24px; height: 2px;
  background: var(--white);
  border-radius: 2px;
  transition: var(--transition);
}
#navbar.scrolled .hamburger span { background: var(--navy); }

.mobile-menu {
  display: none;
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: var(--navy);
  z-index: 9999;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 24px;
  padding: 40px;
  overflow-y: auto;
}
.mobile-menu.open { display: flex; }
.mobile-menu a {
  font-family: 'Poppins', sans-serif;
  font-size: 1.3rem;
  font-weight: 600;
  color: var(--white);
  transition: var(--transition);
}
.mobile-menu a:hover { color: var(--teal-light); }
.mobile-close {
  position: absolute;
  top: 24px; right: 24px;
  background: none; border: none;
  color: var(--white);
  font-size: 2rem;
  cursor: pointer;
  line-height: 1;
  padding: 8px;
  z-index: 10;
}
.mobile-menu-phone {
  display: flex;
  align-items: center;
  gap: 8px;
  font-family: 'Poppins', sans-serif;
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--teal-light);
  margin-top: 8px;
}
.mobile-menu-divider {
  width: 60px;
  height: 2px;
  background: rgba(255,255,255,0.15);
  border-radius: 2px;
}

/* HERO */
#hero {
  min-height: 100vh;
  background:
    linear-gradient(135deg, rgba(26,51,92,0.92) 0%, rgba(0,135,138,0.80) 100%),
    linear-gradient(160deg, var(--navy) 0%, var(--teal) 50%, var(--navy) 100%);
  background-size: cover;
  background-position: center;
  display: flex;
  align-items: center;
  padding-top: 100px;
  position: relative;
  overflow: hidden;
}
#hero.has-image {
  background-size: cover;
  background-position: center;
}
#hero::before {
  content: '';
  position: absolute;
  bottom: -2px; left: 0; right: 0;
  height: 80px;
  background: var(--white);
  clip-path: ellipse(55% 100% at 50% 100%);
}
.hero-content {
  display: grid;
  grid-template-columns: 1.15fr 1fr;
  gap: 48px;
  align-items: start;
  padding: 60px 0 80px;
}
.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(255,255,255,0.12);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 50px;
  padding: 6px 16px;
  font-size: 0.82rem;
  font-weight: 500;
  color: rgba(255,255,255,0.9);
  margin-bottom: 20px;
  backdrop-filter: blur(8px);
}
.hero-badge-dot {
  width: 8px; height: 8px;
  background: #4ade80;
  border-radius: 50%;
  animation: pulse 2s infinite;
}
@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50%       { opacity: 0.6; transform: scale(1.3); }
}
.hero-headline {
  font-family: 'Poppins', sans-serif;
  font-size: clamp(2.4rem, 4.5vw, 3.6rem);
  font-weight: 800;
  color: var(--white);
  line-height: 1.1;
  margin-bottom: 20px;
}
.hero-headline span { color: var(--teal-light); }
.hero-desc {
  font-size: 1.08rem;
  color: rgba(255,255,255,0.82);
  line-height: 1.75;
  margin-bottom: 36px;
  max-width: 480px;
}
.hero-actions {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
  margin-bottom: 44px;
}
.hero-stats {
  display: flex;
  gap: 32px;
}
.hero-stat-item { display: flex; flex-direction: column; }
.hero-stat-num {
  font-family: 'Poppins', sans-serif;
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--white);
  line-height: 1;
}
.hero-stat-label {
  font-size: 0.78rem;
  color: rgba(255,255,255,0.65);
  font-weight: 500;
  margin-top: 4px;
}

/* Hero card (right side) */
.hero-card {
  background: rgba(255,255,255,0.08);
  backdrop-filter: blur(16px);
  border: 1px solid rgba(255,255,255,0.18);
  border-radius: var(--radius-lg);
  padding: 32px;
}
.hero-card-title {
  font-family: 'Poppins', sans-serif;
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--white);
  margin-bottom: 20px;
  padding-bottom: 16px;
  border-bottom: 1px solid rgba(255,255,255,0.15);
}
.quote-form { display: flex; flex-direction: column; gap: 14px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group label {
  font-size: 0.8rem;
  font-weight: 600;
  color: rgba(255,255,255,0.75);
  text-transform: uppercase;
  letter-spacing: 0.05em;
}
.form-group input,
.form-group select,
.form-group textarea {
  background: rgba(255,255,255,0.10);
  border: 1px solid rgba(255,255,255,0.20);
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 0.9rem;
  color: var(--white);
  font-family: 'Inter', sans-serif;
  outline: none;
  transition: var(--transition);
}
.form-group input::placeholder,
.form-group select::placeholder,
.form-group textarea::placeholder { color: rgba(255,255,255,0.45); }
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: var(--teal-light);
  background: rgba(255,255,255,0.15);
}
.form-group select option { background: var(--navy); color: var(--white); }

/* TRUST STRIP */
#trust {
  background: var(--navy);
  padding: 20px 0;
}
.trust-items {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0;
  flex-wrap: wrap;
}
.trust-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 28px;
  border-right: 1px solid rgba(255,255,255,0.12);
}
.trust-item:last-child { border-right: none; }
.trust-icon { font-size: 1.3rem; flex-shrink: 0; color: var(--teal-light); }
.trust-text {
  font-size: 0.82rem;
  font-weight: 600;
  color: rgba(255,255,255,0.85);
  white-space: nowrap;
}

/* SERVICES */
#services { background: var(--off-white); }
.services-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}
.service-card {
  background: var(--white);
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-sm);
  transition: var(--transition);
  cursor: pointer;
}
.service-card:hover {
  transform: translateY(-6px);
  box-shadow: var(--shadow-lg);
}
.service-card-img {
  height: 180px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3.5rem;
  position: relative;
  overflow: hidden;
}
.service-card-badge {
  position: absolute;
  top: 14px; right: 14px;
  background: rgba(255,255,255,0.18);
  border: 1px solid rgba(255,255,255,0.3);
  border-radius: 50px;
  padding: 4px 12px;
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--white);
  letter-spacing: 0.04em;
}
.service-card-body { padding: 24px; }
.service-card-title {
  font-family: 'Poppins', sans-serif;
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--navy);
  margin-bottom: 8px;
}
.service-card-desc {
  font-size: 0.88rem;
  color: var(--grey);
  line-height: 1.6;
  margin-bottom: 16px;
}
.service-card-price {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.service-price-tag {
  font-family: 'Poppins', sans-serif;
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--teal);
}
.service-card-link {
  font-size: 0.82rem;
  font-weight: 600;
  color: var(--teal);
  display: flex;
  align-items: center;
  gap: 4px;
  transition: var(--transition);
}
.service-card-link:hover { gap: 8px; }

/* HOW IT WORKS */
#how { background: var(--white); }
.steps-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 32px;
  position: relative;
}
.steps-grid::before {
  content: '';
  position: absolute;
  top: 28px; left: 10%; right: 10%;
  height: 2px;
  background: linear-gradient(90deg, var(--teal-pale), var(--teal), var(--teal-pale));
  z-index: 0;
}
.step-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  position: relative;
  z-index: 1;
}
.step-num {
  width: 56px; height: 56px;
  background: var(--teal);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Poppins', sans-serif;
  font-weight: 800;
  font-size: 1.2rem;
  color: var(--white);
  margin-bottom: 20px;
  box-shadow: 0 4px 16px rgba(0,135,138,0.35);
}
.step-title {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  font-size: 1rem;
  color: var(--navy);
  margin-bottom: 8px;
}
.step-desc { font-size: 0.87rem; color: var(--grey); line-height: 1.6; }

/* PRICING */
#pricing { background: var(--off-white); }
.pricing-tabs {
  display: flex;
  gap: 0;
  background: var(--grey-light);
  border-radius: 50px;
  padding: 4px;
  margin-bottom: 40px;
  width: fit-content;
}
.pricing-tab {
  padding: 10px 28px;
  border-radius: 50px;
  font-family: 'Poppins', sans-serif;
  font-size: 0.88rem;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  color: var(--grey);
  border: none;
  background: none;
}
.pricing-tab.active {
  background: var(--white);
  color: var(--navy);
  box-shadow: var(--shadow-sm);
}
.pricing-panel { display: none; }
.pricing-panel.active { display: block; }

.eot-table {
  background: var(--white);
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-md);
}
.eot-table-header {
  display: grid;
  grid-template-columns: 2fr 1.2fr 1.2fr 1.2fr;
  background: var(--navy);
  padding: 16px 24px;
  gap: 12px;
}
.eot-th {
  font-family: 'Poppins', sans-serif;
  font-size: 0.78rem;
  font-weight: 700;
  color: rgba(255,255,255,0.8);
  text-transform: uppercase;
  letter-spacing: 0.07em;
}
.eot-row {
  display: grid;
  grid-template-columns: 2fr 1.2fr 1.2fr 1.2fr;
  padding: 18px 24px;
  gap: 12px;
  border-bottom: 1px solid var(--grey-light);
  transition: var(--transition);
  align-items: center;
}
.eot-row:last-child { border-bottom: none; }
.eot-row:hover { background: var(--off-white); }
.eot-property {
  font-weight: 600;
  color: var(--navy);
  font-size: 0.95rem;
}
.eot-detail { font-size: 0.82rem; color: var(--grey); margin-top: 2px; }
.eot-time { font-size: 0.9rem; color: var(--grey-dark); }
.eot-price {
  font-family: 'Poppins', sans-serif;
  font-weight: 800;
  font-size: 1.1rem;
  color: var(--teal);
}
.eot-market { font-size: 0.82rem; color: var(--grey); }
.eot-note {
  padding: 16px 24px;
  background: var(--teal-pale);
  font-size: 0.83rem;
  color: var(--teal);
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
}

.hourly-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}
.hourly-card {
  background: var(--white);
  border-radius: var(--radius-lg);
  padding: 32px 24px;
  box-shadow: var(--shadow-md);
  text-align: center;
  transition: var(--transition);
  border: 2px solid transparent;
}
.hourly-card:hover,
.hourly-card.featured {
  border-color: var(--teal);
  transform: translateY(-4px);
  box-shadow: var(--shadow-lg);
}
.hourly-card.featured { position: relative; overflow: hidden; }
.hourly-badge {
  position: absolute;
  top: 0; left: 0; right: 0;
  background: var(--teal);
  padding: 6px;
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--white);
  text-transform: uppercase;
  letter-spacing: 0.1em;
}
.hourly-icon { font-size: 2.5rem; margin-bottom: 16px; }
.hourly-title {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  font-size: 1.05rem;
  color: var(--navy);
  margin-bottom: 8px;
}
.hourly-price-wrap { margin: 20px 0 8px; }
.hourly-price {
  font-family: 'Poppins', sans-serif;
  font-size: 2.8rem;
  font-weight: 800;
  color: var(--teal);
  line-height: 1;
}
.hourly-price sup { font-size: 1.2rem; vertical-align: top; margin-top: 8px; }
.hourly-per { font-size: 0.85rem; color: var(--grey); }
.hourly-min { font-size: 0.82rem; color: var(--grey); margin-bottom: 20px; }
.hourly-features { text-align: left; margin-bottom: 24px; }
.hourly-feature {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 0.88rem;
  color: var(--grey-dark);
  padding: 5px 0;
}
.hourly-feature::before {
  content: '✓';
  color: var(--teal);
  font-weight: 700;
  flex-shrink: 0;
}

/* WHY CHOOSE US */
#why {
  background: linear-gradient(135deg, var(--navy) 0%, #0f3460 100%);
  color: var(--white);
}
#why .section-title { color: var(--white); }
#why .section-subtitle { color: rgba(255,255,255,0.7); }
.why-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px;
}
.why-card {
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.10);
  border-radius: var(--radius-lg);
  padding: 32px 28px;
  transition: var(--transition);
}
.why-card:hover {
  background: rgba(255,255,255,0.10);
  border-color: var(--teal-light);
  transform: translateY(-4px);
}
.why-icon {
  width: 52px; height: 52px;
  background: rgba(0,180,183,0.15);
  border-radius: var(--radius);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  margin-bottom: 18px;
}
.why-title {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  font-size: 1rem;
  color: var(--white);
  margin-bottom: 10px;
}
.why-desc { font-size: 0.88rem; color: rgba(255,255,255,0.65); line-height: 1.65; }

/* ABOUT */
#about { background: var(--white); }
.about-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 72px;
  align-items: center;
}
.about-img-wrap {
  position: relative;
  border-radius: var(--radius-lg);
  overflow: hidden;
}
.about-img {
  height: 460px;
  background: linear-gradient(160deg, var(--teal) 0%, var(--navy) 100%);
  border-radius: var(--radius-lg);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 16px;
  position: relative;
}
.about-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: var(--radius-lg);
}
.about-img-icon { font-size: 5rem; opacity: 0.3; }
.about-img-placeholder {
  font-size: 0.85rem;
  color: rgba(255,255,255,0.5);
  text-align: center;
  padding: 0 32px;
}
.about-floatcard {
  position: absolute;
  bottom: -20px;
  right: -20px;
  background: var(--white);
  border-radius: var(--radius);
  padding: 18px 22px;
  box-shadow: var(--shadow-lg);
  display: flex;
  align-items: center;
  gap: 14px;
}
.about-floatcard-icon {
  width: 44px; height: 44px;
  background: var(--teal-pale);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.4rem;
  flex-shrink: 0;
}
.about-floatcard-num {
  font-family: 'Poppins', sans-serif;
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--navy);
  line-height: 1;
}
.about-floatcard-label { font-size: 0.75rem; color: var(--grey); }

.about-quote {
  background: var(--teal-pale);
  border-left: 4px solid var(--teal);
  border-radius: 0 var(--radius) var(--radius) 0;
  padding: 20px 24px;
  margin: 28px 0;
  font-style: italic;
  color: var(--grey-dark);
  font-size: 0.95rem;
  line-height: 1.7;
}
.about-bullets { display: flex; flex-direction: column; gap: 12px; margin: 24px 0 32px; }
.about-bullet {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  font-size: 0.9rem;
  color: var(--grey-dark);
}
.about-bullet-check {
  width: 22px; height: 22px;
  background: var(--teal);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--white);
  font-size: 0.7rem;
  font-weight: 800;
  flex-shrink: 0;
  margin-top: 1px;
}

/* COVERAGE */
#coverage { background: var(--off-white); }
.coverage-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}
.coverage-map {
  background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
  border-radius: var(--radius-lg);
  height: 380px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  box-shadow: var(--shadow-md);
}
.coverage-map-pin {
  width: 60px; height: 60px;
  background: var(--teal);
  border-radius: 50% 50% 50% 0;
  transform: rotate(-45deg);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 20px rgba(0,135,138,0.4);
}
.coverage-map-pin::after {
  content: '\f3c5'; /* fa-location-dot */
  font-family: 'Font Awesome 6 Free';
  font-weight: 900;
  color: var(--white);
  transform: rotate(45deg);
  font-size: 1.5rem;
}
.map-placeholder-text {
  position: absolute;
  bottom: 20px;
  font-size: 0.78rem;
  color: #64748b;
  text-align: center;
  padding: 0 20px;
}
.coverage-areas { display: flex; flex-direction: column; gap: 24px; }
.area-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 16px;
}
.area-tag {
  background: var(--white);
  border: 1px solid var(--grey-light);
  border-radius: 50px;
  padding: 6px 16px;
  font-size: 0.85rem;
  font-weight: 500;
  color: var(--grey-dark);
  transition: var(--transition);
}
.area-tag:hover,
.area-tag.primary {
  background: var(--navy);
  color: var(--white);
  border-color: var(--navy);
}

/* TESTIMONIALS */
#testimonials { background: var(--white); }
.reviews-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}
.review-card {
  background: var(--off-white);
  border-radius: var(--radius-lg);
  padding: 28px;
  transition: var(--transition);
  border: 1px solid transparent;
}
.review-card:hover {
  background: var(--white);
  border-color: var(--teal-pale);
  box-shadow: var(--shadow-md);
  transform: translateY(-3px);
}
.review-stars {
  display: flex;
  gap: 3px;
  margin-bottom: 14px;
}
.star { color: #f59e0b; font-size: 1rem; }
.review-text {
  font-size: 0.92rem;
  color: var(--grey-dark);
  line-height: 1.7;
  margin-bottom: 20px;
  font-style: italic;
}
.review-author { display: flex; align-items: center; gap: 12px; }
.review-avatar {
  width: 42px; height: 42px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  font-size: 0.9rem;
  color: var(--white);
  flex-shrink: 0;
}
.review-name {
  font-weight: 600;
  font-size: 0.9rem;
  color: var(--navy);
}
.review-role { font-size: 0.78rem; color: var(--grey); }
.review-source {
  margin-top: 16px;
  font-size: 0.72rem;
  color: var(--grey);
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.07em;
}

.reviews-cta {
  text-align: center;
  margin-top: 40px;
}
.google-rating {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: var(--white);
  border: 1px solid var(--grey-light);
  border-radius: var(--radius);
  padding: 14px 24px;
  box-shadow: var(--shadow-sm);
}
.google-g {
  width: 32px; height: 32px;
  background: linear-gradient(135deg, #4285F4, #DB4437, #F4B400, #0F9D58);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 800;
  font-size: 0.9rem;
}
.google-score {
  font-family: 'Poppins', sans-serif;
  font-size: 1.2rem;
  font-weight: 800;
  color: var(--navy);
}
.google-text { font-size: 0.82rem; color: var(--grey); }

/* CONTACT */
#contact {
  background: linear-gradient(135deg, var(--navy) 0%, #0f3460 100%);
  color: var(--white);
}
.contact-grid {
  display: grid;
  grid-template-columns: 1fr 1.2fr;
  gap: 72px;
  align-items: start;
}
#contact .section-title { color: var(--white); }
#contact .section-subtitle { color: rgba(255,255,255,0.7); }
.contact-info { display: flex; flex-direction: column; gap: 20px; margin-top: 32px; }
.contact-item {
  display: flex;
  align-items: center;
  gap: 14px;
}
.contact-icon {
  width: 46px; height: 46px;
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.12);
  border-radius: var(--radius);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  flex-shrink: 0;
}
.contact-item-text { display: flex; flex-direction: column; }
.contact-item-label { font-size: 0.75rem; font-weight: 600; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.07em; }
.contact-item-value { font-weight: 600; color: var(--white); font-size: 0.95rem; margin-top: 2px; }

.contact-form-wrap {
  background: rgba(255,255,255,0.06);
  backdrop-filter: blur(16px);
  border: 1px solid rgba(255,255,255,0.12);
  border-radius: var(--radius-lg);
  padding: 36px;
}
.contact-form-title {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  font-size: 1.2rem;
  color: var(--white);
  margin-bottom: 24px;
}
.contact-form { display: flex; flex-direction: column; gap: 16px; }
.contact-form .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.contact-form .form-group label { color: rgba(255,255,255,0.65); font-size: 0.78rem; }
.contact-form .form-group input,
.contact-form .form-group select,
.contact-form .form-group textarea {
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.15);
  color: var(--white);
  border-radius: var(--radius);
  padding: 12px 16px;
  font-family: 'Inter', sans-serif;
  font-size: 0.9rem;
  outline: none;
  transition: var(--transition);
  width: 100%;
}
.contact-form .form-group input:focus,
.contact-form .form-group select:focus,
.contact-form .form-group textarea:focus {
  border-color: var(--teal-light);
  background: rgba(255,255,255,0.12);
}
.contact-form .form-group input::placeholder,
.contact-form .form-group textarea::placeholder { color: rgba(255,255,255,0.35); }
.contact-form .form-group select option { background: var(--navy); }
.form-submit { margin-top: 4px; width: 100%; justify-content: center; }

/* FOOTER */
footer {
  background: var(--navy-dark);
  padding: 56px 0 28px;
  color: rgba(255,255,255,0.65);
}
.footer-top {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 48px;
  padding-bottom: 40px;
  border-bottom: 1px solid rgba(255,255,255,0.08);
  margin-bottom: 28px;
}
.footer-brand-desc {
  font-size: 0.87rem;
  line-height: 1.7;
  margin: 16px 0 24px;
  color: rgba(255,255,255,0.55);
}
.footer-socials { display: flex; gap: 10px; }
.social-btn {
  width: 36px; height: 36px;
  background: rgba(255,255,255,0.07);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  transition: var(--transition);
  color: rgba(255,255,255,0.65);
}
.social-btn:hover { background: var(--teal); color: var(--white); }
.footer-col-title {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  font-size: 0.9rem;
  color: var(--white);
  margin-bottom: 16px;
}
.footer-links { display: flex; flex-direction: column; gap: 9px; }
.footer-links a, .footer-links li {
  font-size: 0.85rem;
  color: rgba(255,255,255,0.55);
  transition: var(--transition);
}
.footer-links a:hover { color: var(--teal-light); }
.footer-bottom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 0.8rem;
}
.footer-legal { display: flex; gap: 20px; }
.footer-legal a { color: rgba(255,255,255,0.45); transition: var(--transition); }
.footer-legal a:hover { color: var(--teal-light); }

/* FLOATING CTA */
.float-cta {
  position: fixed;
  bottom: 28px; right: 28px;
  z-index: 900;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 10px;
}
.float-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 13px 20px;
  border-radius: 50px;
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
  font-size: 0.88rem;
  cursor: pointer;
  box-shadow: var(--shadow-lg);
  transition: var(--transition);
  text-decoration: none;
}
.float-btn-primary {
  background: var(--teal);
  color: var(--white);
}
.float-btn-primary:hover { background: var(--teal-light); transform: scale(1.04); }
.float-btn-call {
  background: var(--white);
  color: var(--navy);
}
.float-btn-call:hover { background: var(--off-white); transform: scale(1.04); }

/* RESPONSIVE */
@media (max-width: 1024px) {
  .services-grid   { grid-template-columns: repeat(2, 1fr); }
  .steps-grid      { grid-template-columns: repeat(2, 1fr); }
  .steps-grid::before { display: none; }
  .why-grid        { grid-template-columns: repeat(2, 1fr); }
  .footer-top      { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 768px) {
  .nav-links       { display: none; }
  .nav-cta         { display: none; }
  .hamburger       { display: flex; }
  .nav-logo-tag    { display: none; }
  #hero            { min-height: auto; padding-top: 80px; padding-bottom: 40px; }
  .hero-content    { grid-template-columns: 1fr; gap: 32px; padding: 40px 0; }
  .hero-card       { padding: 24px; }
  .hero-stats      { gap: 24px; }
  .hero-headline   { font-size: clamp(2rem, 8vw, 2.8rem); }
  .services-grid   { grid-template-columns: 1fr; }
  .steps-grid      { grid-template-columns: 1fr; }
  .hourly-grid     { grid-template-columns: 1fr; }
  .why-grid        { grid-template-columns: 1fr; }
  .about-grid      { grid-template-columns: 1fr; }
  .coverage-grid   { grid-template-columns: 1fr; }
  .reviews-grid    { grid-template-columns: 1fr; }
  .contact-grid    { grid-template-columns: 1fr; }
  .footer-top      { grid-template-columns: 1fr; gap: 32px; }
  .footer-bottom   { flex-direction: column; gap: 14px; text-align: center; }
  .float-cta       { bottom: 16px; right: 16px; flex-direction: row; align-items: center; }
  .float-btn       { padding: 10px 16px; font-size: 0.8rem; }
  .trust-item      { padding: 10px 16px; }
  .eot-table-header,
  .eot-row         { grid-template-columns: 2fr 1fr 1fr; }
  .eot-th:last-child,
  .eot-market      { display: none; }
  .section         { padding: 60px 0; }
  .pricing-tabs    { width: 100%; }
  .pricing-tab     { flex: 1; text-align: center; font-size: 0.8rem; padding: 10px 14px; }
  .about-img       { height: 320px; }
  .contact-form-wrap { padding: 24px; }
}
@media (max-width: 480px) {
  .hero-actions    { flex-direction: column; }
  .btn-lg          { width: 100%; justify-content: center; }
  .hero-stats      { flex-wrap: wrap; }
  .trust-items     { flex-direction: column; }
  .trust-item      { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.12); width: 100%; justify-content: center; }
  .trust-item:last-child { border-bottom: none; }
  .form-row        { grid-template-columns: 1fr; }
  .nav-logo-name   { font-size: 0.95rem; }
  .nav-logo-icon   { width: 32px; height: 32px; font-size: 0.9rem; }
  .container       { padding: 0 16px; }
  .section         { padding: 48px 0; }
  .section-title   { font-size: clamp(1.5rem, 6vw, 2rem); }
  .hero-headline   { font-size: clamp(1.8rem, 7vw, 2.4rem); }
  .about-img       { height: 260px; }
  .coverage-map    { height: 260px; }
  .hero-card       { padding: 20px; }
  .hero-card-title { font-size: 1rem; margin-bottom: 16px; }
  .quote-form      { gap: 10px; }
}