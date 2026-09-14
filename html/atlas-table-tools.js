// Paylaşımlı tablo filtre / sıralama yardımcıları
(function (global) {
  'use strict';

  function normalizeKey(value) {
    return String(value || '')
      .toLocaleLowerCase('tr')
      .replace(/ı/g, 'i')
      .replace(/ş/g, 's')
      .replace(/ğ/g, 'g')
      .replace(/ü/g, 'u')
      .replace(/ö/g, 'o')
      .replace(/ç/g, 'c')
      .replace(/[^a-z0-9]+/g, ' ')
      .trim();
  }

  function compareValues(a, b) {
    var sa = a == null ? '' : String(a);
    var sb = b == null ? '' : String(b);
    var na = parseFloat(sa.replace(/\./g, '').replace(',', '.'));
    var nb = parseFloat(sb.replace(/\./g, '').replace(',', '.'));
    if (!isNaN(na) && !isNaN(nb) && sa.match(/[\d,.]/)) {
      return na - nb;
    }
    return sa.localeCompare(sb, 'tr', { sensitivity: 'base' });
  }

  function sortRows(rows, getter, direction) {
    var dir = direction === 'desc' ? -1 : 1;
    return rows.slice().sort(function (a, b) {
      return compareValues(getter(a), getter(b)) * dir;
    });
  }

  function filterRows(rows, query, getters) {
    var q = String(query || '').trim().toLocaleLowerCase('tr');
    if (!q) return rows.slice();
    return rows.filter(function (row) {
      return getters.some(function (g) {
        return String(g(row) || '').toLocaleLowerCase('tr').indexOf(q) !== -1;
      });
    });
  }

  function uniqueValues(rows, getter) {
    var seen = {};
    var out = [];
    rows.forEach(function (row) {
      var v = String(getter(row) || '').trim();
      if (!v || seen[v]) return;
      seen[v] = true;
      out.push(v);
    });
    return out.sort(function (a, b) { return a.localeCompare(b, 'tr'); });
  }

  global.AtlasTableTools = {
    normalizeKey: normalizeKey,
    sortRows: sortRows,
    filterRows: filterRows,
    uniqueValues: uniqueValues,
    compareValues: compareValues
  };
})(typeof window !== 'undefined' ? window : this);
