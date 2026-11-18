<!-- ===== Header Start ===== -->
<!-- Copié de TailAdmin : identique sur toutes les pages, aucune logique PHP. -->
<header
  x-data="{ isApplicationMenuOpen: false }"
  @keydown.window="(e) => { if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); $refs.searchInput?.focus(); } }"
  class="sticky top-0 z-50 flex w-full shrink-0 border-gray-200 bg-white xl:border-b dark:border-gray-800 dark:bg-gray-900"
>
  <div
    class="flex grow flex-col items-center justify-between xl:flex-row xl:px-6"
  >
    <!-- Left side content -->
    <div
      class="flex w-full items-center justify-between gap-2 border-b border-gray-200 px-3 py-3 sm:gap-4 xl:justify-normal xl:border-b-0 xl:px-0 xl:py-4 dark:border-gray-800"
    >
      <!-- Sidebar Toggle Button -->
      <button
        type="button"
        @click.stop="sidebarToggle = !sidebarToggle"
        aria-label="Afficher ou masquer le menu"
        class="group/button relative inline-flex size-10 shrink-0 items-center justify-center rounded-[10px] border border-gray-300 bg-transparent text-gray-500 shadow-theme-xs transition-all select-none hover:bg-gray-50 hover:text-gray-800 focus-visible:ring-3 focus-visible:ring-gray-300/50 focus-visible:outline-hidden active:translate-y-px lg:size-11 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700/50 dark:hover:text-white"
      >
        <!-- XIcon (shown when mobile sidebar is open) -->
        <svg
          x-show="sidebarToggle && window.innerWidth < 1280"
          class="size-6"
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M18 6L6 18M6 6l12 12" />
        </svg>

        <!-- MenuTwoIcon (shown by default / desktop) -->
        <svg
          x-show="!sidebarToggle || window.innerWidth >= 1280"
          class="size-auto! rtl:-scale-x-100"
          width="16"
          height="12"
          viewBox="0 0 16 12"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25L14.6666 10.25C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75L1.33325 11.75C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75L7.99992 6.75C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25L1.33325 5.25Z"
            fill="currentColor"
          />
        </svg>
      </button>

      <!-- Mobile Logo -->
      <a href="index.php?page=dashboard" class="xl:hidden">
        <img
          class="h-8 w-38.5 dark:hidden"
          src="Views/assets/img/logo/logo.svg"
          alt="Logo"
        />
        <img
          class="hidden h-8 w-38.5 dark:block"
          src="Views/assets/img/logo/logo-dark.svg"
          alt="Logo"
        />
      </a>

      <!-- Mobile Application Menu Toggle Button -->
      <button
        type="button"
        @click="isApplicationMenuOpen = !isApplicationMenuOpen"
        aria-label="Menu"
        class="inline-flex size-10 shrink-0 items-center justify-center rounded-[10px] border-none bg-transparent text-gray-700 transition-all select-none hover:bg-gray-50 hover:text-gray-800 focus-visible:ring-3 focus-visible:ring-gray-300/50 focus-visible:outline-hidden xl:hidden dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700/50 dark:hover:text-white"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <circle cx="12" cy="12" r="1" />
          <circle cx="19" cy="12" r="1" />
          <circle cx="5" cy="12" r="1" />
        </svg>
      </button>

      <!-- Search Input (Desktop) -->
      <div class="hidden xl:block">
        <form @submit.prevent>
          <div class="xl:w-107.5">
            <div
              data-slot="input-group"
              role="group"
              class="group/input-group relative flex h-11 w-full min-w-0 items-center rounded-[10px] border border-gray-200 bg-transparent shadow-theme-xs outline-hidden transition-colors has-[[data-slot=input-group-control]:focus]:border-brand-300 has-[[data-slot=input-group-control]:focus]:ring-3 has-[[data-slot=input-group-control]:focus]:ring-brand-500/20 has-[[data-slot=input-group-control]:focus-visible]:border-brand-300 has-[[data-slot=input-group-control]:focus-visible]:ring-3 has-[[data-slot=input-group-control]:focus-visible]:ring-brand-500/20 dark:border-gray-800 dark:bg-white/3 dark:has-[[data-slot=input-group-control]:focus]:border-brand-500/40 dark:has-[[data-slot=input-group-control]:focus-visible]:border-brand-500/40"
            >
              <div
                role="group"
                data-slot="input-group-addon"
                data-align="inline-start"
                class="flex h-auto cursor-text items-center justify-center gap-2 py-1.5 ps-3.5 text-sm font-medium text-gray-500 select-none dark:text-gray-400"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="none"
                  class="size-5 text-gray-500 dark:text-gray-400"
                >
                  <path
                    d="M14.382 14.383l3.325 3.326m-1.249-8.334a7.082 7.082 0 01-7.083 7.081 7.082 7.082 0 110-14.163 7.082 7.082 0 017.083 7.082z"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </div>

              <input
                x-ref="searchInput"
                data-slot="input-group-control"
                type="text"
                placeholder="Rechercher un lieu, un audit, un membre…"
                class="h-full flex-1 rounded-none border-0 bg-transparent px-2 text-sm text-gray-800 shadow-none ring-0 outline-hidden placeholder:text-gray-400 focus:border-transparent focus:ring-0 focus:outline-hidden focus-visible:border-transparent focus-visible:ring-0 dark:text-white/90 dark:placeholder:text-white/30"
              />

              <div
                role="group"
                data-slot="input-group-addon"
                data-align="inline-end"
                class="flex h-auto cursor-text items-center justify-center gap-2 py-1.5 pe-3.5 text-sm font-medium text-gray-500 select-none dark:text-gray-400"
              >
                <span
                  data-slot="badge"
                  data-variant="light"
                  data-color="gray"
                  data-size="sm"
                  @click="$refs.searchInput.focus()"
                  class="group/badge inline-flex h-5.5 w-fit shrink-0 cursor-pointer items-center justify-center gap-0.5 rounded-lg border border-gray-200 bg-gray-50 px-1.75 py-[4.5px] text-xs font-medium tracking-[-0.2px] whitespace-nowrap text-gray-500 shadow-none transition-colors select-none [text-shadow:0_4px_4px_var(--color-gray-300)] dark:border-gray-800 dark:bg-white/3 dark:text-gray-400 dark:[text-shadow:0_4px_4px_rgba(255,255,255,0.10)] [&:has(>svg:first-child)]:ps-1.5 [&:has(>svg:last-child)]:pe-1.5 [&>svg]:pointer-events-none [&>svg]:size-3 [&>svg]:shrink-0"
                >
                  <span>⌘</span>
                  <span>K</span>
                </span>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Right side content -->
    <div
      class="w-full items-center justify-between gap-4 px-5 py-4 shadow-theme-md xl:flex xl:justify-end xl:px-0 xl:shadow-none"
      :class="isApplicationMenuOpen ? 'flex' : 'hidden'"
    >
      <div class="flex items-center gap-2 2xsm:gap-3">
        <!-- Theme Toggler -->
        <!-- Theme Toggle Button -->
<button
  type="button"
  @click.prevent="darkMode = !darkMode; document.documentElement.classList.toggle('dark', darkMode); localStorage.setItem('darkMode', JSON.stringify(darkMode))"
  class="relative inline-flex size-11 shrink-0 items-center justify-center rounded-[10px] border border-gray-300 bg-transparent text-gray-700 shadow-theme-xs transition-all select-none hover:bg-gray-50 hover:text-gray-800 focus-visible:ring-3 focus-visible:ring-gray-300/50 focus-visible:outline-hidden active:translate-y-px dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700/50 dark:hover:text-white"
  aria-label="Changer de thème"
>
  <!-- Sun Icon (Dark Mode) -->
  <svg
    x-cloak
    x-show="darkMode"
    class="size-auto!"
    xmlns="http://www.w3.org/2000/svg"
    width="20"
    height="20"
    viewBox="0 0 24 24"
    fill="none"
  >
    <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.5" />
    <path
      d="M12 2v2m0 16v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M2 12h2m16 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"
      stroke="currentColor"
      stroke-width="1.5"
      stroke-linecap="round"
    />
  </svg>

  <!-- Moon Icon (Light Mode) -->
  <svg
    x-cloak
    x-show="!darkMode"
    class="size-auto!"
    xmlns="http://www.w3.org/2000/svg"
    width="20"
    height="20"
    viewBox="0 0 20 20"
    fill="none"
  >
    <path
      d="M6.247 6.668a6.668 6.668 0 0011.207 4.885A7.708 7.708 0 118.03 2.129a6.645 6.645 0 00-1.783 4.54z"
      stroke="currentColor"
      stroke-width="1.5"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
  </svg>
</button>
<!-- Notification Dropdown -->
        <!-- Notification Dropdown -->
<div
  class="relative"
  x-data="dropdown({ placement: 'bottom-end', offset: 8, notifying: true })"
  @click.outside="open = false"
  @keydown.escape.window="open = false"
>
  <!-- Trigger Button -->
  <button
    x-ref="trigger"
    type="button"
    @click="toggle(); if (open) notifying = false"
    class="relative inline-flex size-11 shrink-0 items-center justify-center rounded-[10px] border border-gray-300 bg-transparent text-gray-700 shadow-theme-xs transition-all select-none hover:bg-gray-50 hover:text-gray-800 focus-visible:ring-3 focus-visible:ring-gray-300/50 focus-visible:outline-hidden active:translate-y-px dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700/50 dark:hover:text-white"
    aria-label="Notifications"
  >
    <!-- Ping Indicator Dot -->
    <span
      class="absolute inset-e-0 -top-0.5 z-10 h-2 w-2 translate-x-1/2 rounded-full bg-orange-400 rtl:-inset-e-1.5"
      :class="!notifying ? 'hidden' : 'flex'"
    >
      <span
        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-orange-400 opacity-75"
      ></span>
    </span>

    <!-- Bell Icon -->
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="20"
      height="20"
      viewBox="0 0 20 20"
      fill="none"
      class="size-auto!"
    >
      <path
        d="M9.861 1.876v1.25m0 0a5.625 5.625 0 015.625 5.625v6.041H4.236V8.751a5.625 5.625 0 015.625-5.625zm-1.25 14.166h2.5m-7.917-2.5h13.334"
        stroke="currentColor"
        stroke-width="1.5"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
    </svg>
  </button>

  <!-- Dropdown Menu Content -->
  <div
    x-ref="dropdown"
    x-cloak
    x-show="open"
    x-transition:enter="animate-in fade-in-0 zoom-in-95 duration-100"
    x-transition:leave="animate-out fade-out-0 zoom-out-95 fill-mode-forwards duration-75"
    class="fixed z-50 flex w-87.5 max-w-[calc(100vw-1rem)] flex-col gap-1 overflow-hidden rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg outline-none sm:w-90.25 dark:border-gray-800 dark:bg-[#1E2635]"
  >
    <!-- Dropdown Header -->
    <div class="flex items-center justify-between pb-3">
      <h5
        class="text-title-sm font-semibold text-gray-800 dark:text-gray-200"
      >
        Notifications
      </h5>
      <button
        type="button"
        @click="open = false"
        class="inline-flex size-8 shrink-0 items-center justify-center rounded-lg border-none bg-transparent text-gray-700 transition-all hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700/50 dark:hover:text-white"
        aria-label="Fermer"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="size-5"
        >
          <path d="M18 6L6 18M6 6l12 12" />
        </svg>
      </button>
    </div>

    <div class="-mx-3 my-0 h-px bg-gray-100 dark:bg-gray-700"></div>

    <!-- Notification List (ScrollArea) -->
    <div class="flex custom-scrollbar max-h-96 flex-col overflow-y-auto pe-2">
      <a href="index.php?page=audits" class="group/dropdown-menu-item flex items-start gap-3 rounded-lg p-3 hover:bg-gray-100 dark:hover:bg-white/5">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-orange-500 text-sm font-semibold text-white">HM</span>
        <span class="block flex-1 text-start">
          <span class="mb-1.5 block space-x-1 text-sm text-gray-500 dark:text-gray-400">
            <span class="font-medium text-gray-800 dark:text-white/90">Hédi M.</span>
            <span>a envoyé un audit pour</span>
            <span class="font-medium text-gray-800 dark:text-white/90">Dr Sonia Gharbi</span>
          </span>
          <span class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400"><span>Audit</span><span>·</span><span>il y a 10 min</span></span>
        </span>
      </a>
      <a href="index.php?page=professionnels" class="group/dropdown-menu-item flex items-start gap-3 rounded-lg p-3 hover:bg-gray-100 dark:hover:bg-white/5">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-500 text-sm font-semibold text-white">NF</span>
        <span class="block flex-1 text-start">
          <span class="mb-1.5 block space-x-1 text-sm text-gray-500 dark:text-gray-400">
            <span class="font-medium text-gray-800 dark:text-white/90">Dr Nadia Ferchichi</span>
            <span>attend la validation de</span>
            <span class="font-medium text-gray-800 dark:text-white/90">son profil</span>
          </span>
          <span class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400"><span>Professionnel</span><span>·</span><span>il y a 1 h</span></span>
        </span>
      </a>
      <a href="index.php?page=publications" class="group/dropdown-menu-item flex items-start gap-3 rounded-lg p-3 hover:bg-gray-100 dark:hover:bg-white/5">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-sky-500 text-sm font-semibold text-white">MT</span>
        <span class="block flex-1 text-start">
          <span class="mb-1.5 block space-x-1 text-sm text-gray-500 dark:text-gray-400">
            <span class="font-medium text-gray-800 dark:text-white/90">Mourad T.</span>
            <span>a signalé une</span>
            <span class="font-medium text-gray-800 dark:text-white/90">publication</span>
          </span>
          <span class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400"><span>Communauté</span><span>·</span><span>il y a 3 h</span></span>
        </span>
      </a>
      <a href="index.php?page=commandes" class="group/dropdown-menu-item flex items-start gap-3 rounded-lg p-3 hover:bg-gray-100 dark:hover:bg-white/5">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-gray-700 text-sm font-semibold text-white">FR</span>
        <span class="block flex-1 text-start">
          <span class="mb-1.5 block space-x-1 text-sm text-gray-500 dark:text-gray-400">
            <span class="font-medium text-gray-800 dark:text-white/90">Fatma R.</span>
            <span>a passé la commande</span>
            <span class="font-medium text-gray-800 dark:text-white/90">CMD-1042</span>
          </span>
          <span class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400"><span>Boutique</span><span>·</span><span>hier</span></span>
        </span>
      </a>
    </div>

    <!-- Voir toutes les notifications Button -->
    <a
      href="index.php?page=dashboard"
      @click="open = false"
      class="mt-3 inline-flex h-10 w-full items-center justify-center rounded-[10px] border border-gray-300 bg-transparent px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs transition-all select-none hover:bg-gray-50 hover:text-gray-800 focus-visible:ring-3 focus-visible:ring-gray-300/50 focus-visible:outline-hidden active:translate-y-px dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700/50 dark:hover:text-white"
    >
      Voir toutes les notifications
    </a>
  </div>
</div>
</div>

      <!-- User Dropdown -->
<div class="relative" x-data="dropdown({ placement: 'bottom-end', offset: 8 })" @click.outside="open = false" @keydown.escape.window="open = false">
  <button type="button" x-ref="trigger" @click="toggle()" :aria-expanded="open.toString()" aria-label="Menu du compte"
    class="dropdown-toggle flex items-center text-gray-700 dark:text-gray-400">
    <span class="me-3 flex h-11 w-11 items-center justify-center overflow-hidden rounded-lg bg-brand-500 text-sm font-semibold text-white"><?= e(initiales($_SESSION['prenom'] ?? '', $_SESSION['nom'] ?? '')) ?></span>
    <span class="me-1 hidden text-sm font-medium sm:block"><?= e($_SESSION['prenom'] ?? '') ?></span>
    <svg class="size-5 text-gray-500 transition-transform duration-200 dark:text-gray-400" :class="open ? 'rotate-180' : 'rotate-0'" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
  </button>
  <div x-ref="menu" x-cloak x-show="open" role="menu"
    x-transition:enter="animate-in fade-in-0 zoom-in-95 duration-100"
    x-transition:leave="animate-out fade-out-0 zoom-out-95 fill-mode-forwards duration-75"
    class="fixed z-50 flex w-65 flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-[#1E2635]">
    <div class="px-3 pb-3">
      <span class="block text-theme-sm font-medium text-gray-700 dark:text-gray-300"><?= e(($_SESSION['prenom'] ?? '') . ' ' . ($_SESSION['nom'] ?? '')) ?></span>
      <span class="mt-0.5 block text-theme-xs text-gray-500 dark:text-gray-400"><?= e(Utilisateur::LIBELLES_ROLES[$_SESSION['role'] ?? ''] ?? '') ?> · session active</span>
    </div>
    <div class="-mx-3 my-1.5 h-px bg-gray-100 dark:bg-gray-700"></div>
    <a href="index.php?page=utilisateur-edit&amp;id=<?= (int) ($_SESSION['user_id'] ?? 0) ?>" role="menuitem" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">Mon profil</a>
    <a href="../frontoffice/index.php?page=accueil" role="menuitem" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">Voir le site (Front Office)</a>
    <div class="-mx-3 my-1.5 h-px bg-gray-100 dark:bg-gray-700"></div>
    <a href="index.php?page=logout" role="menuitem" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">Se déconnecter</a>
  </div>
</div>
</div>
  </div>
</header>
<!-- ===== Header End ===== -->
