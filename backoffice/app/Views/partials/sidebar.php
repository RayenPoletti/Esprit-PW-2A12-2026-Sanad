<?php
// $currentPage est fourni par le Controller (voir app/Controllers/Controller.php)
$currentPage = $currentPage ?? 'dashboard';
// Groupe de menu à ouvrir automatiquement selon la page courante
$openGroup = groupOf($currentPage);
?>
<!-- ===== Sidebar Start ===== -->
<a class="sr-only" href="#main-content">Aller au contenu</a>
<div x-cloak x-show="sidebarToggle" class="fixed inset-0 z-99 bg-gray-900/50 backdrop-blur-xs xl:hidden" aria-hidden="true" @click="sidebarToggle = false" x-transition.opacity></div>

<aside
  x-cloak
  :class="sidebarToggle ? 'translate-x-0 xl:w-22.5' : '-translate-x-full rtl:translate-x-full'"
  class="sidebar fixed top-0 left-0 z-9999 flex h-screen w-72.5 flex-col border-r border-gray-200 bg-white px-5 transition-all duration-300 xl:static xl:translate-x-0 dark:border-gray-800 dark:bg-black"
  @click.outside="if (window.innerWidth < 1280) sidebarToggle = false"
  aria-label="Navigation du Back Office"
>
  <!-- Logo -->
  <div :class="sidebarToggle ? 'xl:justify-center' : 'justify-between'" class="sidebar-header flex items-center gap-2 py-8">
    <a href="index.php?page=dashboard">
      <span class="sr-only">Tableau de bord</span>
      <span class="logo" :class="sidebarToggle ? 'xl:hidden' : ''">
        <img class="h-10 w-37.5 dark:hidden" src="app/Views/assets/images/logo/logo.svg" alt="Sanad" />
        <img class="hidden h-10 w-37.5 dark:block" src="app/Views/assets/images/logo/logo-dark.svg" alt="Sanad" />
      </span>
      <img class="logo-icon size-8" :class="sidebarToggle ? 'xl:block hidden' : 'hidden'" src="app/Views/assets/images/logo/logo-icon.svg" alt="Sanad" />
    </a>
  </div>

  <div class="no-scrollbar flex flex-1 flex-col overflow-y-auto duration-300 ease-linear">
    <nav x-data="{ selected: '<?= $openGroup ?>' }" aria-label="Menu principal">

  <div>
    <h3 class="mb-4 text-xs leading-5 text-gray-400 uppercase"><span class="menu-group-title" :class="sidebarToggle ? 'xl:hidden' : ''">Menu</span></h3>
    <ul class="mb-6 flex flex-col gap-1">
      <li>
        <a href="index.php?page=dashboard" class="group menu-item <?= isMenuActive(['dashboard'], $currentPage) ?>">
          <svg class="<?= isIconActive(['dashboard'], $currentPage) ?>" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.257 3.25 3.25 4.257 3.25 5.5V9c0 1.243 1.007 2.25 2.25 2.25H9c1.243 0 2.25-1.007 2.25-2.25V5.5C11.25 4.257 10.243 3.25 9 3.25H5.5ZM4.75 5.5c0-.414.336-.75.75-.75H9c.414 0 .75.336.75.75V9c0 .414-.336.75-.75.75H5.5A.75.75 0 0 1 4.75 9V5.5Zm.75 7.25c-1.243 0-2.25 1.007-2.25 2.25v3.5c0 1.243 1.007 2.25 2.25 2.25H9c1.243 0 2.25-1.007 2.25-2.25V15c0-1.243-1.007-2.25-2.25-2.25H5.5ZM4.75 15c0-.414.336-.75.75-.75H9c.414 0 .75.336.75.75v3.5c0 .414-.336.75-.75.75H5.5a.75.75 0 0 1-.75-.75V15ZM12.75 5.5c0-1.243 1.007-2.25 2.25-2.25h3.5c1.243 0 2.25 1.007 2.25 2.25V9c0 1.243-1.007 2.25-2.25 2.25H15A2.25 2.25 0 0 1 12.75 9V5.5ZM15 4.75a.75.75 0 0 0-.75.75V9c0 .414.336.75.75.75h3.5a.75.75 0 0 0 .75-.75V5.5a.75.75 0 0 0-.75-.75H15Zm0 8c-1.243 0-2.25 1.007-2.25 2.25v3.5c0 1.243 1.007 2.25 2.25 2.25h3.5c1.243 0 2.25-1.007 2.25-2.25V15c0-1.243-1.007-2.25-2.25-2.25H15ZM14.25 15c0-.414.336-.75.75-.75h3.5c.414 0 .75.336.75.75v3.5a.75.75 0 0 1-.75.75H15a.75.75 0 0 1-.75-.75V15Z" fill="currentColor"/></svg>
          <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Tableau de bord</span>
        </a>
      </li>
    </ul>
  </div>

  <!-- ===== Module commun · Toute l’équipe ===== -->
  <div>
    <h3 class="mb-4 text-xs leading-5 text-gray-400 uppercase">
      <span class="menu-group-title" :class="sidebarToggle ? 'xl:hidden' : ''">Module commun · Toute l’équipe</span>
    </h3>
    <ul class="mb-6 flex flex-col gap-1">
      <li>
        <a href="#" @click.prevent="selected = (selected === 'Utilisateurs' ? '' : 'Utilisateurs')"
           class="group menu-item <?= isMenuActive(['utilisateurs', 'utilisateur-form', 'passeports'], $currentPage) ?>"
           :aria-expanded="selected === 'Utilisateurs' ? 'true' : 'false'">
          <svg class="<?= isIconActive(['utilisateurs', 'utilisateur-form', 'passeports'], $currentPage) ?>" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8.25c.6-3.2 3.4-5.25 7-5.25s6.4 2.05 7 5.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/></svg>
          <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Utilisateurs</span>
          <svg class="menu-item-arrow" :class="[(selected === 'Utilisateurs') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'xl:hidden' : '']" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
        <div x-cloak x-show="selected === 'Utilisateurs'" x-collapse>
          <ul :class="sidebarToggle ? 'xl:hidden' : 'flex'" class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
          <li><a href="index.php?page=utilisateurs" class="group menu-dropdown-item <?= isActive('utilisateurs', $currentPage) ?>">Utilisateurs</a></li>
          <li><a href="index.php?page=utilisateur-form" class="group menu-dropdown-item <?= isActive('utilisateur-form', $currentPage) ?>">Ajouter un utilisateur</a></li>
          <li><a href="index.php?page=passeports" class="group menu-dropdown-item <?= isActive('passeports', $currentPage) ?>">Passeports d’accessibilité</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </div>

  <!-- ===== Module 1 · Rayen Poletti ===== -->
  <div>
    <h3 class="mb-4 text-xs leading-5 text-gray-400 uppercase">
      <span class="menu-group-title" :class="sidebarToggle ? 'xl:hidden' : ''">Module 1 · Rayen Poletti</span>
    </h3>
    <ul class="mb-6 flex flex-col gap-1">
      <li>
        <a href="#" @click.prevent="selected = (selected === 'Audits' ? '' : 'Audits')"
           class="group menu-item <?= isMenuActive(['lieux', 'lieu-form', 'audits', 'audit-form'], $currentPage) ?>"
           :aria-expanded="selected === 'Audits' ? 'true' : 'false'">
          <svg class="<?= isIconActive(['lieux', 'lieu-form', 'audits', 'audit-form'], $currentPage) ?>" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3.75 4.75 6.5v5.25c0 4.2 3 7.6 7.25 8.5 4.25-.9 7.25-4.3 7.25-8.5V6.5L12 3.75Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" fill="none"/><path d="m8.75 12 2.25 2.25 4.25-4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
          <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Audits</span>
          <svg class="menu-item-arrow" :class="[(selected === 'Audits') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'xl:hidden' : '']" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
        <div x-cloak x-show="selected === 'Audits'" x-collapse>
          <ul :class="sidebarToggle ? 'xl:hidden' : 'flex'" class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
          <li><a href="index.php?page=lieux" class="group menu-dropdown-item <?= isActive('lieux', $currentPage) ?>">Lieux de soin</a></li>
          <li><a href="index.php?page=lieu-form" class="group menu-dropdown-item <?= isActive('lieu-form', $currentPage) ?>">Ajouter un lieu</a></li>
          <li><a href="index.php?page=audits" class="group menu-dropdown-item <?= isActive('audits', $currentPage) ?>">Audits d’accessibilité</a></li>
          <li><a href="index.php?page=audit-form" class="group menu-dropdown-item <?= isActive('audit-form', $currentPage) ?>">Ajouter un audit</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </div>

  <!-- ===== Module 2 · Aziz Kouki ===== -->
  <div>
    <h3 class="mb-4 text-xs leading-5 text-gray-400 uppercase">
      <span class="menu-group-title" :class="sidebarToggle ? 'xl:hidden' : ''">Module 2 · Aziz Kouki</span>
    </h3>
    <ul class="mb-6 flex flex-col gap-1">
      <li>
        <a href="#" @click.prevent="selected = (selected === 'Rendez-vous' ? '' : 'Rendez-vous')"
           class="group menu-item <?= isMenuActive(['professionnels', 'professionnel-form', 'rendezvous', 'rendezvous-form'], $currentPage) ?>"
           :aria-expanded="selected === 'Rendez-vous' ? 'true' : 'false'">
          <svg class="<?= isIconActive(['professionnels', 'professionnel-form', 'rendezvous', 'rendezvous-form'], $currentPage) ?>" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2c.414 0 .75.336.75.75v1h6.5v-1a.75.75 0 0 1 1.5 0v1h1.75c1.243 0 2.25 1.007 2.25 2.25v13c0 1.243-1.007 2.25-2.25 2.25h-13A2.25 2.25 0 0 1 3.25 19V6c0-1.243 1.007-2.25 2.25-2.25h1.75v-1C7.25 2.336 7.586 2 8 2Zm0 3.25H5.5a.75.75 0 0 0-.75.75v2.25h14.5V6a.75.75 0 0 0-.75-.75H8Zm11.25 4.5H4.75V19c0 .414.336.75.75.75h13a.75.75 0 0 0 .75-.75V9.75Z" fill="currentColor"/></svg>
          <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Rendez-vous</span>
          <svg class="menu-item-arrow" :class="[(selected === 'Rendez-vous') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'xl:hidden' : '']" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
        <div x-cloak x-show="selected === 'Rendez-vous'" x-collapse>
          <ul :class="sidebarToggle ? 'xl:hidden' : 'flex'" class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
          <li><a href="index.php?page=professionnels" class="group menu-dropdown-item <?= isActive('professionnels', $currentPage) ?>">Professionnels</a></li>
          <li><a href="index.php?page=professionnel-form" class="group menu-dropdown-item <?= isActive('professionnel-form', $currentPage) ?>">Ajouter un professionnel</a></li>
          <li><a href="index.php?page=rendezvous" class="group menu-dropdown-item <?= isActive('rendezvous', $currentPage) ?>">Rendez-vous</a></li>
          <li><a href="index.php?page=rendezvous-form" class="group menu-dropdown-item <?= isActive('rendezvous-form', $currentPage) ?>">Ajouter un rendez-vous</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </div>

  <!-- ===== Module 3 · Rayen Marzougi ===== -->
  <div>
    <h3 class="mb-4 text-xs leading-5 text-gray-400 uppercase">
      <span class="menu-group-title" :class="sidebarToggle ? 'xl:hidden' : ''">Module 3 · Rayen Marzougi</span>
    </h3>
    <ul class="mb-6 flex flex-col gap-1">
      <li>
        <a href="#" @click.prevent="selected = (selected === 'Programmes' ? '' : 'Programmes')"
           class="group menu-item <?= isMenuActive(['programmes', 'programme-form', 'seances', 'seance-form'], $currentPage) ?>"
           :aria-expanded="selected === 'Programmes' ? 'true' : 'false'">
          <svg class="<?= isIconActive(['programmes', 'programme-form', 'seances', 'seance-form'], $currentPage) ?>" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20s-7.25-4.35-7.25-10A4.25 4.25 0 0 1 12 7.25 4.25 4.25 0 0 1 19.25 10C19.25 15.65 12 20 12 20Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" fill="none"/></svg>
          <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Programmes</span>
          <svg class="menu-item-arrow" :class="[(selected === 'Programmes') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'xl:hidden' : '']" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
        <div x-cloak x-show="selected === 'Programmes'" x-collapse>
          <ul :class="sidebarToggle ? 'xl:hidden' : 'flex'" class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
          <li><a href="index.php?page=programmes" class="group menu-dropdown-item <?= isActive('programmes', $currentPage) ?>">Programmes bien-être</a></li>
          <li><a href="index.php?page=programme-form" class="group menu-dropdown-item <?= isActive('programme-form', $currentPage) ?>">Ajouter un programme</a></li>
          <li><a href="index.php?page=seances" class="group menu-dropdown-item <?= isActive('seances', $currentPage) ?>">Séances</a></li>
          <li><a href="index.php?page=seance-form" class="group menu-dropdown-item <?= isActive('seance-form', $currentPage) ?>">Ajouter une séance</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </div>

  <!-- ===== Module 4 · Houssem Hafsi ===== -->
  <div>
    <h3 class="mb-4 text-xs leading-5 text-gray-400 uppercase">
      <span class="menu-group-title" :class="sidebarToggle ? 'xl:hidden' : ''">Module 4 · Houssem Hafsi</span>
    </h3>
    <ul class="mb-6 flex flex-col gap-1">
      <li>
        <a href="#" @click.prevent="selected = (selected === 'Communauté' ? '' : 'Communauté')"
           class="group menu-item <?= isMenuActive(['publications', 'publication-form', 'commentaires'], $currentPage) ?>"
           :aria-expanded="selected === 'Communauté' ? 'true' : 'false'">
          <svg class="<?= isIconActive(['publications', 'publication-form', 'commentaires'], $currentPage) ?>" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.75 6.5c0-.966.784-1.75 1.75-1.75h11c.966 0 1.75.784 1.75 1.75v8c0 .966-.784 1.75-1.75 1.75H10l-4.25 3.5v-3.5H6.5a1.75 1.75 0 0 1-1.75-1.75v-8Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" fill="none"/></svg>
          <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Communauté</span>
          <svg class="menu-item-arrow" :class="[(selected === 'Communauté') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'xl:hidden' : '']" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
        <div x-cloak x-show="selected === 'Communauté'" x-collapse>
          <ul :class="sidebarToggle ? 'xl:hidden' : 'flex'" class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
          <li><a href="index.php?page=publications" class="group menu-dropdown-item <?= isActive('publications', $currentPage) ?>">Publications</a></li>
          <li><a href="index.php?page=publication-form" class="group menu-dropdown-item <?= isActive('publication-form', $currentPage) ?>">Ajouter une publication</a></li>
          <li><a href="index.php?page=commentaires" class="group menu-dropdown-item <?= isActive('commentaires', $currentPage) ?>">Commentaires</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </div>

  <!-- ===== Module 5 · Karim Bousetta ===== -->
  <div>
    <h3 class="mb-4 text-xs leading-5 text-gray-400 uppercase">
      <span class="menu-group-title" :class="sidebarToggle ? 'xl:hidden' : ''">Module 5 · Karim Bousetta</span>
    </h3>
    <ul class="mb-6 flex flex-col gap-1">
      <li>
        <a href="#" @click.prevent="selected = (selected === 'Boutique' ? '' : 'Boutique')"
           class="group menu-item <?= isMenuActive(['produits', 'produit-form', 'commandes'], $currentPage) ?>"
           :aria-expanded="selected === 'Boutique' ? 'true' : 'false'">
          <svg class="<?= isIconActive(['produits', 'produit-form', 'commandes'], $currentPage) ?>" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.75 8.25h12.5l-.9 11.1a1.5 1.5 0 0 1-1.5 1.4H8.15a1.5 1.5 0 0 1-1.5-1.4l-.9-11.1Zm3.25 0V7a3 3 0 1 1 6 0v1.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
          <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Boutique</span>
          <svg class="menu-item-arrow" :class="[(selected === 'Boutique') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'xl:hidden' : '']" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
        <div x-cloak x-show="selected === 'Boutique'" x-collapse>
          <ul :class="sidebarToggle ? 'xl:hidden' : 'flex'" class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
          <li><a href="index.php?page=produits" class="group menu-dropdown-item <?= isActive('produits', $currentPage) ?>">Produits</a></li>
          <li><a href="index.php?page=produit-form" class="group menu-dropdown-item <?= isActive('produit-form', $currentPage) ?>">Ajouter un produit</a></li>
          <li><a href="index.php?page=commandes" class="group menu-dropdown-item <?= isActive('commandes', $currentPage) ?>">Commandes</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </div>

  <div>
    <h3 class="mb-4 text-xs leading-5 text-gray-400 uppercase"><span class="menu-group-title" :class="sidebarToggle ? 'xl:hidden' : ''">Autres</span></h3>
    <ul class="mb-6 flex flex-col gap-1">
      <li>
        <a href="index.php?page=signin" class="group menu-item <?= isMenuActive(['signin'], $currentPage) ?>">
          <svg class="<?= isIconActive(['signin'], $currentPage) ?>" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.75 10.75V8a4.25 4.25 0 1 1 8.5 0v2.75M6.5 10.75h11c.69 0 1.25.56 1.25 1.25v7c0 .69-.56 1.25-1.25 1.25h-11c-.69 0-1.25-.56-1.25-1.25v-7c0-.69.56-1.25 1.25-1.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/></svg>
          <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">Connexion</span>
        </a>
      </li>
    </ul>
  </div>
    </nav>
  </div>
</aside>
<!-- ===== Sidebar End ===== -->
