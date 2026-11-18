/**
 * Sanad · Front Office — comportements de la page (repris du template Medilab, sans dépendance) :
 * preloader, ombre du header au défilement, menu mobile, bouton « haut de page ».
 * La validation des formulaires est dans validation.js.
 */
(function () {
  'use strict';

  // Retire le preloader quand la page est chargée
  window.addEventListener('load', function () {
    var preloader = document.querySelector('#preloader');
    if (preloader) {
      preloader.remove();
    }
  });

  // Ombre sous le header après 100 px de défilement + bouton « haut de page »
  var scrollTop = document.querySelector('.scroll-top');
  function surDefilement() {
    var bas = window.scrollY > 100;
    document.body.classList.toggle('scrolled', bas);
    if (scrollTop) {
      scrollTop.classList.toggle('active', bas);
    }
  }
  window.addEventListener('load', surDefilement);
  document.addEventListener('scroll', surDefilement);

  if (scrollTop) {
    scrollTop.addEventListener('click', function (evt) {
      evt.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // Menu mobile : ouverture / fermeture
  var bouton = document.querySelector('.mobile-nav-toggle');
  function basculerMenu() {
    document.body.classList.toggle('mobile-nav-active');
    if (bouton) {
      bouton.classList.toggle('bi-list');
      bouton.classList.toggle('bi-x');
    }
  }
  if (bouton) {
    bouton.setAttribute('role', 'button');
    bouton.setAttribute('tabindex', '0');
    bouton.setAttribute('aria-label', 'Ouvrir ou fermer le menu');
    bouton.addEventListener('click', basculerMenu);
    bouton.addEventListener('keydown', function (evt) {
      if (evt.key === 'Enter' || evt.key === ' ') {
        evt.preventDefault();
        basculerMenu();
      }
    });
  }
  // Un clic sur un lien du menu mobile le referme
  document.querySelectorAll('#navmenu a').forEach(function (lien) {
    lien.addEventListener('click', function () {
      if (document.body.classList.contains('mobile-nav-active')) {
        basculerMenu();
      }
    });
  });
})();
