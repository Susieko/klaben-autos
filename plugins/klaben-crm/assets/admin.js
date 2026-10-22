(function () {
  'use strict';
  document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('klaben-crm-search');
    const status = document.getElementById('klaben-crm-status-filter');
    const type = document.getElementById('klaben-crm-type-filter');
    const counter = document.getElementById('klaben-crm-visible-count');
    const empty = document.getElementById('klaben-crm-no-results');
    const rows = Array.from(document.querySelectorAll('.klaben-crm-record'));
    const indices = rows.map(function (row) {
      const searchable = row.querySelectorAll('.klaben-crm-table-title, .klaben-crm-vehicle, .klaben-crm-contact, .klaben-crm-detail');
      return Array.from(searchable, function (cell) { return cell.textContent; }).join(' ').toLocaleLowerCase('nl-NL');
    });
    if (!search || !status || !type || !counter || !empty || !rows.length) return;

    function update() {
      const needle = search.value.trim().toLocaleLowerCase('nl-NL');
      let shown = 0;
      rows.forEach(function (row, index) {
        const match = (status.value === 'all' || row.dataset.status === status.value) &&
          (type.value === 'all' || row.dataset.form === type.value) &&
          (!needle || indices[index].includes(needle));
        row.hidden = !match;
        if (match) shown++;
      });
      counter.textContent = shown + (shown === 1 ? ' aanvraag' : ' aanvragen');
      empty.hidden = shown > 0;
    }

    search.addEventListener('input', update);
    status.addEventListener('change', update);
    type.addEventListener('change', update);
    update();
  });
})();
