// The static site has no PHP backend, so Contact Form 7 forms send their
// details to the site's WhatsApp number instead of by email.
(function () {
  function waNumber() {
    var a = document.querySelector('a[href*="wa.me/"]');
    var m = a && a.href.match(/wa\.me\/(\d+)/);
    return m ? m[1] : '';
  }

  function fieldLabel(el) {
    var label = el.closest('label');
    var text = label ? label.childNodes[0].textContent : el.name;
    return text.replace(/\*/g, '').trim() || el.name;
  }

  function show(form, msg, ok) {
    var out = form.querySelector('.wpcf7-response-output');
    form.classList.remove('sent', 'invalid');
    form.classList.add(ok ? 'sent' : 'invalid');
    if (out) {
      out.textContent = msg;
      out.removeAttribute('aria-hidden');
    }
  }

  document.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form.classList || !form.classList.contains('wpcf7-form')) return;
    e.preventDefault();

    var missing = [];
    form.querySelectorAll('.wpcf7-validates-as-required').forEach(function (el) {
      var empty = !el.value.trim();
      el.setAttribute('aria-invalid', empty ? 'true' : 'false');
      if (empty) missing.push(fieldLabel(el));
    });
    if (missing.length) return show(form, 'Please fill in: ' + missing.join(', ') + '.', false);

    var accept = form.querySelector('.wpcf7-acceptance input[type=checkbox]');
    if (accept && !accept.checked) return show(form, 'Please confirm you are 18 or older.', false);

    var lines = [];
    form.querySelectorAll('input, select, textarea').forEach(function (el) {
      if (el.type === 'hidden' || el.type === 'submit' || el.type === 'checkbox' || !el.name) return;
      if (el.value.trim()) lines.push(fieldLabel(el) + ': ' + el.value.trim());
    });

    var number = waNumber();
    var url = 'https://wa.me/' + number + '?text=' + encodeURIComponent(lines.join('\n'));
    show(form, 'Opening WhatsApp to send your message…', true);
    window.open(url, '_blank', 'noopener');
  }, true);
})();
