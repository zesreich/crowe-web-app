// Shared reports store — Raporlar + Rotasyon aynı Supabase `reports` tablosunu kullanır.
(function (global) {
  'use strict';

  function resolveSupabaseClient() {
    if (global.__supabaseClientInstance) return global.__supabaseClientInstance;
    if (typeof global.getSupabaseClient === 'function') {
      var c = global.getSupabaseClient();
      if (c) return c;
    }
    return null;
  }

  function mapRow(r) {
    return {
      id: r.id,
      company: r.company,
      service: r.service,
      reportType: r.report_type || r.reportType,
      startDate: r.start_date || r.startDate,
      endDate: r.end_date || r.endDate,
      team: r.team,
      status: r.status,
      createdAt: r.created_at || r.createdAt || null
    };
  }

  function toDbPayload(report) {
    return {
      company: report.company,
      service: report.service,
      report_type: report.reportType,
      start_date: report.startDate,
      end_date: report.endDate,
      team: report.team,
      status: report.status
    };
  }

  function readLocal() {
    try {
      return JSON.parse(localStorage.getItem('reports') || '[]');
    } catch (e) {
      return [];
    }
  }

  function writeLocal(list) {
    localStorage.setItem('reports', JSON.stringify(list || []));
  }

  function syncLocalFromMapped(mappedList) {
    writeLocal(mappedList);
  }

  async function ensureSession() {
    if (global.Auth && typeof global.Auth.ensureSupabaseSession === 'function') {
      return global.Auth.ensureSupabaseSession();
    }
    return { ok: true };
  }

  async function listReports() {
    var supabase = resolveSupabaseClient();
    if (!supabase) return readLocal();

    try {
      await ensureSession();
      var res = await supabase
        .from('reports')
        .select('*')
        .order('start_date', { ascending: false });
      if (res.error) throw res.error;
      var mapped = (res.data || []).map(mapRow);
      syncLocalFromMapped(mapped);
      return mapped;
    } catch (err) {
      console.error('listReports:', err);
      return readLocal();
    }
  }

  async function addReport(report) {
    var supabase = resolveSupabaseClient();
    if (!supabase) {
      var local = readLocal();
      var withId = Object.assign({ id: Date.now(), createdAt: new Date().toISOString() }, report);
      local.push(withId);
      writeLocal(local);
      return { success: true, data: withId };
    }

    try {
      var sessionCheck = await ensureSession();
      if (sessionCheck && sessionCheck.ok === false) {
        return { success: false, error: sessionCheck.error || 'Oturum gerekli' };
      }
      var res = await supabase.from('reports').insert([toDbPayload(report)]).select().single();
      if (res.error) throw res.error;
      var mapped = mapRow(res.data);
      var local = readLocal();
      local.push(mapped);
      writeLocal(local);
      return { success: true, data: mapped };
    } catch (err) {
      console.error('addReport:', err);
      return { success: false, error: (err && err.message) || 'Kayıt başarısız' };
    }
  }

  async function addReportsBatch(reports) {
    if (!reports || !reports.length) return { success: true, data: [], count: 0 };
    var supabase = resolveSupabaseClient();
    if (!supabase) {
      var local = readLocal();
      var added = reports.map(function (r, i) {
        return Object.assign({ id: Date.now() + i, createdAt: new Date().toISOString() }, r);
      });
      writeLocal(local.concat(added));
      return { success: true, data: added, count: added.length };
    }

    try {
      var sessionCheck = await ensureSession();
      if (sessionCheck && sessionCheck.ok === false) {
        return { success: false, error: sessionCheck.error || 'Oturum gerekli' };
      }
      var payload = reports.map(toDbPayload);
      var res = await supabase.from('reports').insert(payload).select();
      if (res.error) throw res.error;
      var mapped = (res.data || []).map(mapRow);
      writeLocal(readLocal().concat(mapped));
      return { success: true, data: mapped, count: mapped.length };
    } catch (err) {
      console.error('addReportsBatch:', err);
      return { success: false, error: (err && err.message) || 'Toplu kayıt başarısız' };
    }
  }

  async function deleteReport(id) {
    var supabase = resolveSupabaseClient();
    if (!supabase) {
      writeLocal(readLocal().filter(function (r) { return String(r.id) !== String(id); }));
      return { success: true };
    }
    try {
      await ensureSession();
      var res = await supabase.from('reports').delete().eq('id', id);
      if (res.error) throw res.error;
      writeLocal(readLocal().filter(function (r) { return String(r.id) !== String(id); }));
      return { success: true };
    } catch (err) {
      console.error('deleteReport:', err);
      writeLocal(readLocal().filter(function (r) { return String(r.id) !== String(id); }));
      return { success: true };
    }
  }

  global.ReportsStore = {
    resolveSupabaseClient: resolveSupabaseClient,
    listReports: listReports,
    addReport: addReport,
    addReportsBatch: addReportsBatch,
    deleteReport: deleteReport,
    mapRow: mapRow
  };
})(typeof window !== 'undefined' ? window : this);
