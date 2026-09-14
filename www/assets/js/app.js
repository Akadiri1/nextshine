/* =============================================================================
   NextShine Cleaning — interaction layer
   No dependencies. Everything degrades to a fully readable page without JS.
   ========================================================================== */
(function () {
  'use strict';

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const $  = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

  // Set by initMenu; anchor links call it so the menu closes as they scroll.
  let closeMenu = () => {};

  /* ---------------------------------------------------------------------
     Navigation: solid bar and dark logo once scrolled. The floating
     Call / Get a Quote buttons appear at the same point.
     ------------------------------------------------------------------ */
  function initNav() {
    const nav = $('[data-nav]');
    const cta = $('[data-float-cta]');
    if (!nav && !cta) return;

    const SOLID_AT = 60;

    const apply = () => {
      const on = window.scrollY > SOLID_AT;
      if (nav) nav.classList.toggle('is-scrolled', on);
      if (cta) cta.classList.toggle('is-visible', on);
    };

    apply();
    window.addEventListener('scroll', apply, { passive: true });
  }

  /* ---------------------------------------------------------------------
     Mobile menu (full-screen overlay below 769px)
     ------------------------------------------------------------------ */
  function initMenu() {
    const menu = $('[data-menu]');
    if (!menu) return;

    const open = () => {
      menu.classList.add('is-open');
      document.body.style.overflow = 'hidden';
      $$('[data-menu-open]').forEach(b => b.setAttribute('aria-expanded', 'true'));
    };

    const close = () => {
      menu.classList.remove('is-open');
      document.body.style.overflow = '';
      $$('[data-menu-open]').forEach(b => b.setAttribute('aria-expanded', 'false'));
    };

    closeMenu = close;

    $$('[data-menu-open]').forEach(b => b.addEventListener('click', open));
    $$('[data-menu-close]', menu).forEach(b => b.addEventListener('click', close));
    $$('a', menu).forEach(a => a.addEventListener('click', close));
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && menu.classList.contains('is-open')) close();
    });
  }

  /* ---------------------------------------------------------------------
     Tabs (pricing: fixed end-of-tenancy / hourly)

     Markup contract:
       [data-tabs]
         button[data-tab="<id>"]
         [data-tab-panel="<id>"]
     ------------------------------------------------------------------ */
  function initTabs() {
    $$('[data-tabs]').forEach(group => {
      const tabs   = $$('[data-tab]', group);
      const panels = $$('[data-tab-panel]', group);

      tabs.forEach(tab => {
        tab.addEventListener('click', () => {
          tabs.forEach(t => {
            const on = t === tab;
            t.classList.toggle('is-active', on);
            t.setAttribute('aria-selected', on ? 'true' : 'false');
          });
          panels.forEach(p => p.classList.toggle('is-active', p.dataset.tabPanel === tab.dataset.tab));
        });
      });
    });
  }

  /* ---------------------------------------------------------------------
     Quote request forms → POST /quote-request (views/quote-request-mail-backend.php)

     Fields are read by name, so any form carrying data-quote-form works
     whatever its layout. Fields a form does not have are sent empty.
     ------------------------------------------------------------------ */
  const QUOTE_FIELDS = ['first_name', 'last_name', 'phone', 'email', 'service', 'property_size', 'postcode', 'notes'];

  function initQuoteForms() {
    $$('[data-quote-form]').forEach(form => {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const btn   = $('[type="submit"]', form);
        const label = btn.textContent;

        const fail = (message) => {
          btn.textContent = '✗ ' + message;
          btn.style.background = '#dc2626';
          setTimeout(() => {
            btn.textContent = label;
            btn.style.background = '';
            btn.disabled = false;
          }, 4000);
        };

        btn.textContent = 'Sending...';
        btn.disabled = true;

        const data = {};
        QUOTE_FIELDS.forEach(name => {
          const field = form.elements[name];
          data[name] = field ? field.value : '';
        });

        let response;
        try {
          response = await fetch('/quote-request', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
          });
        } catch (err) {
          fail('Network error — please call us directly.');
          return;
        }

        let result;
        try {
          result = await response.json();
        } catch (err) {
          fail('Server error — please call us directly.');
          return;
        }

        if (result.success) {
          btn.textContent = '✓ Request Sent! We\'ll be in touch shortly.';
          btn.style.background = '#16a34a';
          form.reset();
        } else {
          fail(result.failed || 'Error — please try again.');
        }
      });
    });
  }

  /* ---------------------------------------------------------------------
     In-page anchors: smooth scroll, clearing the fixed navigation
     ------------------------------------------------------------------ */
  function initAnchors() {
    const NAV_OFFSET = 80;

    $$('a[href^="#"]').forEach(a => {
      a.addEventListener('click', e => {
        const href = a.getAttribute('href');
        if (href === '#') return;

        let target = null;
        try { target = $(href); } catch (err) { return; }
        if (!target) return;

        e.preventDefault();
        window.scrollTo({ top: target.offsetTop - NAV_OFFSET, behavior: reduceMotion ? 'auto' : 'smooth' });
        closeMenu();
      });
    });
  }

  /* ---------------------------------------------------------------------
     Scroll reveal for cards (.reveal → .is-in)
     ------------------------------------------------------------------ */
  function initReveal() {
    const items = $$('.reveal');
    if (!items.length) return;

    if (reduceMotion || !('IntersectionObserver' in window)) {
      revealAll();
      return;
    }

    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-in');
        io.unobserve(entry.target);
      });
    }, { threshold: 0.12 });

    items.forEach(el => io.observe(el));
  }

  // Failsafe so a scripting error can never leave cards sitting at opacity 0.
  function revealAll() {
    $$('.reveal').forEach(el => el.classList.add('is-in'));
  }

  function init() {
    // Each module is independent: one throwing must not stop the rest.
    [initNav, initMenu, initTabs, initQuoteForms, initAnchors, initReveal].forEach(fn => {
      try { fn(); } catch (err) {
        console.error(`[nextshine] ${fn.name} failed:`, err);
        if (fn === initReveal) revealAll();
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
