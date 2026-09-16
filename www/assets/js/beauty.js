/* =============================================================================
   NextShine Beauty — interaction layer
   No dependencies. The page stays fully readable without JS.
   ========================================================================== */
(function () {
  'use strict';

  const $  = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

  /* ---------------------------------------------------------------------
     Mobile menu: opens under the navigation bar below 769px
     ------------------------------------------------------------------ */
  function initMenu() {
    const menu   = $('[data-menu]');
    const toggle = $('[data-menu-toggle]');
    if (!menu || !toggle) return;

    const setOpen = (open) => {
      menu.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    };

    toggle.addEventListener('click', () => setOpen(!menu.classList.contains('is-open')));

    // Close on a link tap, a click anywhere outside the menu, or Escape.
    $$('a', menu).forEach(a => a.addEventListener('click', () => setOpen(false)));
    document.addEventListener('click', e => {
      if (!menu.contains(e.target) && !toggle.contains(e.target)) setOpen(false);
    });
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') setOpen(false);
    });
  }

  /* ---------------------------------------------------------------------
     Appointment request → POST /beauty/booking-request
     (views/beauty/booking-request-mail-backend.php)
     ------------------------------------------------------------------ */
  const BOOKING_FIELDS = ['first_name', 'last_name', 'phone', 'service', 'notes'];

  function initBookingForm() {
    const form = $('[data-booking-form]');
    if (!form) return;

    const success  = $('[data-booking-success]');
    const note     = $('[data-form-note]', form);
    const button   = $('[type="submit"]', form);
    const noteText = note ? note.textContent : '';
    const label    = button.textContent;

    const fail = (message) => {
      button.textContent = label;
      button.disabled = false;
      if (note) {
        note.textContent = message;
        note.classList.add('is-error');
      }
    };

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      button.textContent = 'Sending...';
      button.disabled = true;
      if (note) {
        note.textContent = noteText;
        note.classList.remove('is-error');
      }

      const data = {};
      BOOKING_FIELDS.forEach(name => {
        const field = form.elements[name];
        data[name] = field ? field.value : '';
      });

      let result;
      try {
        const response = await fetch('/beauty/booking-request', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data),
        });
        result = await response.json();
      } catch (err) {
        fail('Sorry, something went wrong. Please message us on WhatsApp instead.');
        return;
      }

      if (result.success) {
        form.classList.add('hidden');
        if (success) success.classList.remove('hidden');
      } else {
        fail(result.failed || 'Sorry, something went wrong. Please try again.');
      }
    });
  }

  function init() {
    [initMenu, initBookingForm].forEach(fn => {
      try { fn(); } catch (err) { console.error(`[nextshine-beauty] ${fn.name} failed:`, err); }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
