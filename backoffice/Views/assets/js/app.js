/**
 * Sanad — Back Office
 * Remplace le bundle webpack de TailAdmin : on garde uniquement ce dont nos pages ont besoin.
 *  - le composant Alpine « dropdown » (menus notifications et profil du header) ;
 *  - l'année courante dans le footer.
 * Ce fichier doit être chargé AVANT Alpine.js (voir Views/includes/scripts.php).
 */
document.addEventListener('alpine:init', () => {
  // Version simplifiée du composant dropdown de TailAdmin (sans @floating-ui) :
  // le panneau est positionné en « fixed » sous le bouton déclencheur.
  window.Alpine.data('dropdown', (options = {}) => ({
    ...options,
    open: false,
    subOpen: false,
    notifying: options.notifying ?? false,
    init() {
      this.$watch('open', (value) => { if (value) this.$nextTick(() => this.place()); });
      window.addEventListener('resize', () => { if (this.open) this.place(); });
    },
    toggle() { this.open = !this.open; },
    place() {
      const trigger = this.$refs.trigger;
      const panel = this.$refs.dropdown || this.$refs.menu;
      if (!trigger || !panel) return;
      const r = trigger.getBoundingClientRect();
      panel.style.top = `${r.bottom + (options.offset ?? 8)}px`;
      panel.style.right = `${Math.max(8, window.innerWidth - r.right)}px`;
      panel.style.left = 'auto';
    },
  }));
});

document.addEventListener('DOMContentLoaded', () => {
  const year = document.getElementById('year');
  if (year) year.textContent = new Date().getFullYear();
});
