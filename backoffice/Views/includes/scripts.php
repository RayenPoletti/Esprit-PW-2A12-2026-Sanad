<!-- ========= Scripts communs à toutes les pages ========= -->
<script src="Views/assets/js/app.js"></script>
<!-- Contrôle des saisies côté client (doublé par la validation PHP) -->
<script src="Views/assets/js/validation.js"></script>
<!-- Alpine.js et ses plugins (utilisés par TailAdmin) : les plugins avant le cœur -->
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.14.8/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.14.8/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
<script src="Views/assets/lib/apexcharts/apexcharts.min.js"></script>

<?php if (!empty($extraScripts)): ?>
  <?= $extraScripts ?>
<?php endif; ?>
</body>
</html>
