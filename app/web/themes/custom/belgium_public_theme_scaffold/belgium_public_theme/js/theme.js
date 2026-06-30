(function () {
  function closeAllMegaMenus() {
    document.querySelectorAll('.mega-menu__item.is-open').forEach((item) => {
      item.classList.remove('is-open');
      const trigger = item.querySelector('.mega-menu__trigger');
      if (trigger) {
        trigger.setAttribute('aria-expanded', 'false');
      }
    });
  }

  function initBurgerMenu() {
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.site-nav');

    if (!toggle || !nav) return;

    toggle.addEventListener('click', function () {
      const isOpen = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  function initMegaMenu() {
    document.addEventListener('click', function (event) {
      const trigger = event.target.closest('.mega-menu__trigger');

      if (trigger) {
        const item = trigger.closest('.mega-menu__item');
        const isOpen = item.classList.contains('is-open');

        if (window.innerWidth <= 960) {
          event.preventDefault();
        }

        closeAllMegaMenus();

        if (!isOpen) {
          item.classList.add('is-open');
          trigger.setAttribute('aria-expanded', 'true');
        }

        return;
      }

      if (!event.target.closest('.mega-menu')) {
        closeAllMegaMenus();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closeAllMegaMenus();
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initBurgerMenu();
    initMegaMenu();
  });
})();