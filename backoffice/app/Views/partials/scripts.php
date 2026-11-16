<!-- ========= Scripts communs à toutes les pages ========= -->
<script src="app/Views/assets/js/app.js"></script>
<!-- Alpine.js et ses plugins (utilisés par TailAdmin) : les plugins avant le cœur -->
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.14.8/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.14.8/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
<script src="app/Views/assets/js/apexcharts.min.js"></script>

<?php if (!empty($extraScripts)): ?>
  <?= $extraScripts ?>
<?php endif; ?>
</body>
</html>
