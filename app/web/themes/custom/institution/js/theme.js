(function () {
  function qs(sel, root = document) { return root.querySelector(sel); }
  function qsa(sel, root = document) { return Array.from(root.querySelectorAll(sel)); }

  function openDrawer() {
    const drawer = qs('#search-drawer');
    const backdrop = qs('.drawer-backdrop');
    if (!drawer || !backdrop) return;

    drawer.hidden = false;
    backdrop.hidden = false;

    // Focus input if present
    const input = qs('input[type="search"]', drawer);
    if (input) setTimeout(() => input.focus(), 0);

    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    const drawer = qs('#search-drawer');
    const backdrop = qs('.drawer-backdrop');
    if (!drawer || !backdrop) return;

    drawer.hidden = true;
    backdrop.hidden = true;
    document.body.style.overflow = '';
  }

  document.addEventListener('click', (e) => {
    const toggle = e.target.closest('.search-toggle');
    if (toggle) {
      e.preventDefault();
      openDrawer();
      return;
    }

    if (e.target.matches('[data-drawer-close]') || e.target.closest('[data-drawer-close]')) {
      e.preventDefault();
      closeDrawer();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDrawer();
  });
})();