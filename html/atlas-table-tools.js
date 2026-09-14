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

  function buildHeaderEntries(headerRow) {
    var entries = [];
    (headerRow || []).forEach(function (cell, idx) {
      var key = normalizeKey(cell);
      if (key) entries.push({ idx: idx, key: key });
    });
    return entries;
  }

  function findColumn(entries, aliases, options) {
    options = options || {};
    var excludeIdx = options.excludeIdx || [];
    var excludeKeyPattern = options.excludeKeyPattern || null;
    var keyFilter = options.keyFilter || null;
    var i;
    var j;

    function allowed(entry) {
      if (excludeIdx.indexOf(entry.idx) !== -1) return false;
      if (excludeKeyPattern && excludeKeyPattern.test(entry.key)) return false;
      if (keyFilter && !keyFilter(entry.key)) return false;
      return true;
    }

    for (i = 0; i < aliases.length; i++) {
      for (j = 0; j < entries.length; j++) {
        if (!allowed(entries[j])) continue;
        if (entries[j].key === aliases[i]) return entries[j].idx;
      }
    }

    var bestIdx = -1;
    var bestScore = 0;
    for (i = 0; i < aliases.length; i++) {
      var alias = aliases[i];
      for (j = 0; j < entries.length; j++) {
        if (!allowed(entries[j])) continue;
        var key = entries[j].key;
        var score = 0;
        if (key === alias) score = 100 + alias.length;
        else if (key.indexOf(alias) === 0 && (key.length === alias.length || key.charAt(alias.length) === ' ')) {
          score = 50 + alias.length;
        } else if (alias.indexOf(key) === 0 && (alias.length === key.length || alias.charAt(key.length) === ' ')) {
          score = 40 + key.length;
        }
        if (score > bestScore) {
          bestScore = score;
          bestIdx = entries[j].idx;
        }
      }
    }
    return bestIdx;
  }

  function scoreHeaderRow(headerRow, aliasGroups) {
    var entries = buildHeaderEntries(headerRow);
    var score = 0;
    aliasGroups.forEach(function (aliases) {
      if (findColumn(entries, aliases) >= 0) score += 1;
    });
    return score;
  }

  function findBestHeaderRow(rows, aliasGroups, maxScan) {
    var bestIdx = 0;
    var bestScore = -1;
    var limit = Math.min(rows.length, maxScan || 20);
    for (var i = 0; i < limit; i++) {
      var score = scoreHeaderRow(rows[i], aliasGroups);
      if (score > bestScore) {
        bestScore = score;
        bestIdx = i;
      }
    }
    return { index: bestIdx, score: bestScore };
  }

  function isThreePartAccountCode(code) {
    var c = String(code || '').trim();
    if (!c) return false;
    var parts = c.split('.');
    return parts.length === 3 && parts.every(function (p) { return /^\d+$/.test(p); });
  }

  function isSummaryAccountCode(code) {
    return isThreePartAccountCode(code);
  }

  global.AtlasTableTools = {
    normalizeKey: normalizeKey,
    sortRows: sortRows,
    filterRows: filterRows,
    uniqueValues: uniqueValues,
    compareValues: compareValues,
    buildHeaderEntries: buildHeaderEntries,
    findColumn: findColumn,
    scoreHeaderRow: scoreHeaderRow,
    findBestHeaderRow: findBestHeaderRow,
    isSummaryAccountCode: isSummaryAccountCode,
    isThreePartAccountCode: isThreePartAccountCode
  };
})(typeof window !== 'undefined' ? window : this);
