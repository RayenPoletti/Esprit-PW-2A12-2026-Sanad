/**
 * Sanad · validation.js — contrôle des saisies côté client (Front Office et Back Office).
 *
 * Le JavaScript aide l'utilisateur (message immédiat) mais NE REMPLACE PAS la validation PHP :
 * chaque règle ci-dessous est refaite côté serveur par Controllers/Validator.php.
 *
 * Utilisation : <form data-validate novalidate> puis, sur chaque champ :
 *   data-label="Le prénom"         libellé utilisé dans les messages
 *   data-required                  champ obligatoire
 *   data-rule="nom|email|telephone|motdepasse|entier|decimal|url|date-future|date-passee"
 *   data-min / data-max            bornes (entier, decimal) · data-minlength / data-maxlength (texte)
 *   data-match="motDePasse"        doit être identique à un autre champ
 * Le message s'affiche dans l'élément [data-error-for="nomDuChamp"].
 * Bonus : <form data-confirm="Supprimer ce compte ?"> demande une confirmation avant l'envoi.
 */
(function () {
  'use strict';

  var REGLES = {
    nom: {
      test: function (v) { return /^[\p{L}][\p{L}\s'’-]{1,49}$/u.test(v); },
      msg: function (l) { return l + ' doit contenir de 2 à 50 lettres (espaces, tirets et apostrophes acceptés).'; }
    },
    email: {
      test: function (v) { return v.length <= 150 && /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v); },
      msg: function () { return 'Adresse e-mail invalide (exemple : nom@exemple.tn).'; }
    },
    telephone: {
      test: function (v) { return /^\+?[0-9 ]{8,15}$/.test(v); },
      msg: function () { return 'Téléphone invalide : 8 à 15 chiffres, « + » accepté au début.'; }
    },
    motdepasse: {
      test: function (v) { return v.length >= 8 && /[A-Za-z]/.test(v) && /\d/.test(v); },
      msg: function () { return 'Le mot de passe doit contenir au moins 8 caractères, dont une lettre et un chiffre.'; }
    },
    entier: {
      test: function (v) { return /^-?\d+$/.test(v); },
      msg: function (l) { return l + ' doit être un nombre entier.'; }
    },
    'date-future': {
      test: function (v) { var d = new Date(v); return !isNaN(d) && d > new Date(); },
      msg: function (l) { return l + ' doit être une date à venir.'; }
    },
    decimal: {
      test: function (v) { return /^-?\d+([.,]\d+)?$/.test(v); },
      msg: function (l) { return l + ' doit être un nombre (exemple : 12.5).'; }
    },
    url: {
      test: function (v) { return /^https?:\/\/[^\s]+\.[^\s]+$/.test(v); },
      msg: function (l) { return l + ' doit être une adresse web commençant par http:// ou https://.'; }
    },
    'date-passee': {
      test: function (v) { var d = new Date(v); return !isNaN(d) && d <= new Date(); },
      msg: function (l) { return l + ' ne peut pas être dans le futur.'; }
    },
    texte: {
      test: function () { return true; },
      msg: function () { return ''; }
    }
  };

  function libelle(champ) {
    return champ.getAttribute('data-label') || 'Ce champ';
  }

  /** Renvoie le message d'erreur du champ, ou '' s'il est valide. */
  function verifier(champ, form) {
    var v = champ.type === 'checkbox' ? (champ.checked ? 'on' : '') : champ.value.trim();
    var l = libelle(champ);

    if (champ.hasAttribute('data-required') && v === '') {
      return l + ' est obligatoire.';
    }
    if (v === '') {
      return '';
    }
    var nomRegle = champ.getAttribute('data-rule');
    if (nomRegle && REGLES[nomRegle] && !REGLES[nomRegle].test(champ.type === 'password' ? champ.value : v)) {
      return REGLES[nomRegle].msg(l);
    }
    if (nomRegle === 'entier' || nomRegle === 'decimal') {
      var n = parseFloat(v.replace(',', '.'));
      var min = champ.getAttribute('data-min');
      var max = champ.getAttribute('data-max');
      if ((min !== null && n < +min) || (max !== null && n > +max)) {
        return l + ' doit être compris entre ' + min + ' et ' + max + '.';
      }
    }
    var minL = champ.getAttribute('data-minlength');
    if (minL !== null && v.length < +minL) {
      return l + ' doit contenir au moins ' + minL + ' caractères.';
    }
    var maxL = champ.getAttribute('data-maxlength');
    if (maxL !== null && v.length > +maxL) {
      return l + ' ne doit pas dépasser ' + maxL + ' caractères.';
    }
    var autre = champ.getAttribute('data-match');
    if (autre && form.elements[autre] && champ.value !== form.elements[autre].value) {
      return champ.getAttribute('data-match-msg') || 'Les deux valeurs ne sont pas identiques.';
    }
    return '';
  }

  function afficher(champ, form, message) {
    var zone = form.querySelector('[data-error-for="' + champ.name + '"]');
    if (zone) {
      zone.textContent = message;
    }
    champ.classList.toggle('is-invalid', message !== '');
    if (message) {
      champ.setAttribute('aria-invalid', 'true');
    } else {
      champ.removeAttribute('aria-invalid');
    }
  }

  function champsDe(form) {
    return Array.prototype.filter.call(form.elements, function (c) {
      return c.name && (c.hasAttribute('data-required') || c.hasAttribute('data-rule') || c.hasAttribute('data-match') ||
        c.hasAttribute('data-maxlength') || c.hasAttribute('data-minlength'));
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-validate]').forEach(function (form) {
      form.setAttribute('novalidate', 'novalidate');

      champsDe(form).forEach(function (champ) {
        // Vérification à la sortie du champ, puis en direct une fois l'erreur affichée
        champ.addEventListener('blur', function () { afficher(champ, form, verifier(champ, form)); });
        champ.addEventListener('input', function () {
          if (champ.getAttribute('aria-invalid') === 'true') {
            afficher(champ, form, verifier(champ, form));
          }
        });
      });

      form.addEventListener('submit', function (evt) {
        var premier = null;
        champsDe(form).forEach(function (champ) {
          var m = verifier(champ, form);
          afficher(champ, form, m);
          if (m && !premier) {
            premier = champ;
          }
        });
        if (premier) {
          evt.preventDefault();
          premier.focus();
        }
      });
    });

    // Confirmation avant une action irréversible (suppression, blocage...)
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
      form.addEventListener('submit', function (evt) {
        if (!window.confirm(form.getAttribute('data-confirm'))) {
          evt.preventDefault();
        }
      });
    });
  });
})();
