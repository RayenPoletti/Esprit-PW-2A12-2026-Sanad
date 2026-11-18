<!-- Script spécifique au tableau de bord (graphique ApexCharts) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  var el = document.querySelector('#chartAudits');
  if (!el || typeof ApexCharts === 'undefined') return;
  new ApexCharts(el, {
    chart: { type: 'bar', height: 300, fontFamily: 'Outfit, sans-serif', toolbar: { show: false } },
    series: [{ name: 'Audits validés', data: JSON.parse(el.dataset.valeurs || '[]') }],
    xaxis: { categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'], axisBorder: { show: false }, axisTicks: { show: false } },
    colors: ['#2E7D6B'],
    plotOptions: { bar: { columnWidth: '40%', borderRadius: 5, borderRadiusApplication: 'end' } },
    dataLabels: { enabled: false },
    grid: { yaxis: { lines: { show: true } } },
    yaxis: { tickAmount: 4 },
    tooltip: { y: { formatter: function (v) { return v + ' audit' + (v > 1 ? 's' : ''); } } }
  }).render();
});
</script>
