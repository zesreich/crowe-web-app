/**
 * Denetçi dashboard UI
 */
(function () {
  'use strict';

  var state = {
    workspaces: [],
    selectedKey: null,
    clients: [],
    activeTab: 'workspace'
  };

  function escapeHtml(text) {
    return String(text || '').replace(/[&<>"']/g, function (m) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[m];
    });
  }

  function statusLabel(status) {
    return ({ pending: 'Bekliyor', active: 'Devam ediyor', done: 'Tamamlandı' })[status] || status;
  }

  async function loadClients() {
    var supabase = window.__supabaseClientInstance || null;
    if (supabase) {
        var res = await supabase.from('clients').select('*').order('unvan');
        if (!res.error && res.data) {
          state.clients = res.data;
          AuditorWorkspace.syncWorkspacesFromClients(res.data);
        }
    }
    if (!state.clients.length) {
      try {
        state.clients = JSON.parse(localStorage.getItem('clients') || '[]');
        AuditorWorkspace.syncWorkspacesFromClients(state.clients.map(function (c) {
          return { id: c.vergiNo || c.id, unvan: c.unvan, ekip: c.ekip, vergi_no: c.vergiNo };
        }));
      } catch (e) {}
    }
  }

  function refreshWorkspaceList() {
    var all = AuditorWorkspace.listWorkspaces();
    state.workspaces = Object.keys(all).map(function (key) {
      return Object.assign({ key: key }, all[key]);
    }).sort(function (a, b) {
      return (a.clientName || '').localeCompare(b.clientName || '', 'tr');
    });
    if (!state.selectedKey && state.workspaces.length) {
      state.selectedKey = state.workspaces[0].key;
    }
  }

  function renderWorkspaceList() {
    var el = document.getElementById('auditorWorkspaceList');
    if (!el) return;
    if (!state.workspaces.length) {
      el.innerHTML = '<div class="auditor-empty">Henüz workspace yok. Müşteri ekleyince otomatik oluşur.</div>';
      return;
    }
    el.innerHTML = state.workspaces.map(function (ws) {
      var active = ws.key === state.selectedKey ? ' is-active' : '';
      return '<button type="button" class="auditor-workspace-item' + active + '" data-key="' + escapeHtml(ws.key) + '">' +
        '<strong>' + escapeHtml(ws.clientName || 'Müşteri') + '</strong>' +
        '<span>' + escapeHtml(ws.team) + ' · ' + escapeHtml(ws.vergiNo || ws.clientId) + '</span>' +
        '</button>';
    }).join('');

    el.querySelectorAll('.auditor-workspace-item').forEach(function (btn) {
      btn.addEventListener('click', function () {
        state.selectedKey = btn.getAttribute('data-key');
        renderWorkspaceList();
        renderWorkspaceDetail();
      });
    });
  }

  function renderWorkspaceDetail() {
    var el = document.getElementById('auditorWorkspaceDetail');
    if (!el) return;
    var ws = state.workspaces.find(function (w) { return w.key === state.selectedKey; });
    if (!ws) {
      el.innerHTML = '<div class="auditor-empty">Sol listeden müşteri workspace seçin.</div>';
      return;
    }

    var canEdit = AuditorWorkspace.canEditWorkspace();
    var html = '<div class="auditor-panel-head"><div>' + escapeHtml(ws.clientName) +
      '<p>' + escapeHtml(ws.team) + ' · Vergi no ' + escapeHtml(ws.vergiNo || '—') + '</p></div></div>';

    window.AUDIT_WORKSPACE_SCHEMA.sections.forEach(function (section) {
      html += '<div class="auditor-section"><button type="button" class="auditor-section-toggle" data-section="' + section.id + '">' +
        '<span>' + escapeHtml(section.title) + '</span><span class="meta">' + escapeHtml(section.code) + '</span></button>' +
        '<div class="auditor-section-body" id="section-' + section.id + '">';

      function renderItem(item, groupTitle) {
        var itemId = section.id + '_' + item.code.replace(/[^a-zA-Z0-9]/g, '_');
        var data = ws.items[itemId] || {};
        var status = data.status || 'pending';
        html += '<div class="auditor-file-row" data-item-id="' + escapeHtml(itemId) + '">' +
          '<div class="auditor-file-code">' + escapeHtml(item.code) + '</div>' +
          '<div><div class="auditor-file-title">' + escapeHtml(item.title) +
          (groupTitle ? ' <span style="color:var(--muted);font-size:0.6875rem">· ' + escapeHtml(groupTitle) + '</span>' : '') +
          '</div><div class="auditor-file-note"><textarea placeholder="Denetçi notu…" data-note-for="' + escapeHtml(itemId) + '"' +
          (canEdit ? '' : ' disabled') + '>' + escapeHtml(data.note || '') + '</textarea></div></div>' +
          '<select class="auditor-status-select" data-status-for="' + escapeHtml(itemId) + '"' + (canEdit ? '' : ' disabled') + '>' +
          ['pending', 'active', 'done'].map(function (s) {
            return '<option value="' + s + '"' + (status === s ? ' selected' : '') + '>' + statusLabel(s) + '</option>';
          }).join('') + '</select></div>';
      }

      if (section.items) section.items.forEach(function (item) { renderItem(item); });
      if (section.groups) {
        section.groups.forEach(function (group) {
          group.items.forEach(function (item) { renderItem(item, group.title); });
        });
      }
      html += '</div></div>';
    });

    el.innerHTML = html;

    el.querySelectorAll('.auditor-section-toggle').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var body = document.getElementById('section-' + btn.getAttribute('data-section'));
        if (body) body.hidden = !body.hidden;
      });
    });

    if (canEdit) {
      el.querySelectorAll('[data-status-for]').forEach(function (sel) {
        sel.addEventListener('change', function () {
          AuditorWorkspace.updateWorkspaceItem(ws.clientId, ws.team, sel.getAttribute('data-status-for'), { status: sel.value });
          refreshWorkspaceList();
        });
      });
      el.querySelectorAll('[data-note-for]').forEach(function (ta) {
        ta.addEventListener('blur', function () {
          AuditorWorkspace.updateWorkspaceItem(ws.clientId, ws.team, ta.getAttribute('data-note-for'), { note: ta.value });
        });
      });
    }
  }

  function renderTeamUsers() {
    var tbody = document.getElementById('auditorUsersBody');
    if (!tbody) return;
    var users = AuditorWorkspace.listTeamUsers();
    var canManage = AuditorWorkspace.canManageUsers();

    tbody.innerHTML = users.map(function (u) {
      var suspended = u.status === 'suspended';
      var teamCell;
      if (canManage) {
        teamCell = '<select class="form-control form-control-sm" data-team-for="' + escapeHtml(u.id) + '" style="min-width:110px;">' +
          AuditorWorkspace.TEAMS.map(function (t) {
            return '<option value="' + t + '"' + (t === u.team ? ' selected' : '') + '>' + t + '</option>';
          }).join('') +
          '</select>';
      } else {
        teamCell = '<span class="team-badge">' + escapeHtml(u.team) + '</span>';
      }
      return '<tr class="' + (suspended ? 'is-suspended' : '') + '">' +
        '<td>' + escapeHtml(u.fullName) + '</td>' +
        '<td class="mono-cell">' + escapeHtml(u.email) + '</td>' +
        '<td>' + teamCell + '</td>' +
        '<td><span class="status-badge ' + (u.role === 'lead' ? 'is-auditor' : 'is-admin') + '">' + escapeHtml(u.role) + '</span></td>' +
        '<td><span class="status-badge ' + (suspended ? 'is-suspended' : 'is-active') + '">' + (suspended ? 'Askıda' : 'Aktif') + '</span></td>' +
        '<td class="text-center">' + (canManage ?
          (suspended ?
            '<button type="button" class="btn btn-success btn-sm" data-reactivate="' + escapeHtml(u.id) + '"><i class="ph ph-play"></i></button>' :
            '<button type="button" class="btn btn-danger btn-sm" data-suspend="' + escapeHtml(u.id) + '"><i class="ph ph-pause"></i></button>') :
          '—') +
        '</td></tr>';
    }).join('') || '<tr><td colspan="6" class="text-center text-muted">Kullanıcı yok</td></tr>';

    tbody.querySelectorAll('[data-suspend]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        if (confirm('Kullanıcı askıya alınsın mı?')) {
          AuditorWorkspace.suspendTeamUser(btn.getAttribute('data-suspend'));
          renderTeamUsers();
        }
      });
    });
    tbody.querySelectorAll('[data-reactivate]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        AuditorWorkspace.reactivateTeamUser(btn.getAttribute('data-reactivate'));
        renderTeamUsers();
      });
    });
    tbody.querySelectorAll('[data-team-for]').forEach(function (sel) {
      sel.addEventListener('change', function () {
        var res = AuditorWorkspace.updateTeamUser(sel.getAttribute('data-team-for'), { team: sel.value });
        if (!res.ok) { alert(res.error); renderTeamUsers(); }
      });
    });

    var addBtn = document.getElementById('auditorAddUserBtn');
    if (addBtn) addBtn.style.display = canManage ? '' : 'none';
  }

  function bindTabs() {
    document.querySelectorAll('.auditor-tab').forEach(function (tab) {
      tab.addEventListener('click', function () {
        state.activeTab = tab.getAttribute('data-tab');
        document.querySelectorAll('.auditor-tab').forEach(function (t) {
          t.classList.toggle('is-active', t === tab);
        });
        document.getElementById('auditorTabWorkspace').hidden = state.activeTab !== 'workspace';
        document.getElementById('auditorTabUsers').hidden = state.activeTab !== 'users';
      });
    });
  }

  function bindAddUserForm() {
    var form = document.getElementById('auditorAddUserForm');
    if (!form) return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var res = AuditorWorkspace.addTeamUser({
        fullName: document.getElementById('newAuditorName').value,
        email: document.getElementById('newAuditorEmail').value,
        team: document.getElementById('newAuditorTeam').value,
        role: document.getElementById('newAuditorRole').value
      });
      if (!res.ok) { alert(res.error); return; }
      $('#addAuditorUserModal').modal('hide');
      form.reset();
      renderTeamUsers();
    });
  }

  function bindPersona() {
    var actor = AuditorWorkspace.getCurrentActor();
    // Denetçi portalından girenlere admin linklerini gösterme
    if (actor.source === 'auditor') {
      document.querySelectorAll('[data-admin-only]').forEach(function (el) {
        el.style.display = 'none';
      });
    }
    var nameEl = document.getElementById('personaName');
    var roleEl = document.getElementById('personaRole');
    var av = document.getElementById('userAvatarInitial');
    var display = document.getElementById('auditorWelcomeName');
    if (display) display.textContent = actor.email.split('@')[0];
    if (nameEl) nameEl.textContent = actor.email.split('@')[0];
    if (roleEl) roleEl.textContent = actor.source === 'auditor' ? 'Denetçi' : 'Yönetici';
    if (av && actor.email) {
      var p = actor.email.split(/[@.]+/);
      av.textContent = ((p[0][0] || '') + (p[1] ? p[1][0] : '')).toUpperCase();
    }
  }

  function bindLogout() {
    var btn = document.getElementById('atlasLogout');
    if (!btn || btn.dataset.auditorBound) return;
    btn.dataset.auditorBound = '1';
    btn.addEventListener('click', function (e) {
      if (AuditorWorkspace.getCurrentActor().source === 'auditor') {
        e.preventDefault();
        e.stopImmediatePropagation();
        localStorage.removeItem('auditorUser');
        localStorage.removeItem('auditorPendingPasswordChange');
        localStorage.removeItem('loginPortal');
        window.location.href = 'auditor-login.html';
      }
    }, true);
  }

  window.initAuditorDashboard = async function initAuditorDashboard() {
    if (!AuditorWorkspace.requireAuditorAccess()) return;
    bindPersona();
    bindLogout();
    bindTabs();
    bindAddUserForm();
    try { await AuditorWorkspace.pullFromSupabase(); } catch (e) {}
    await loadClients();
    refreshWorkspaceList();
    renderWorkspaceList();
    renderWorkspaceDetail();
    renderTeamUsers();
  };
})();
