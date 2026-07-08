/**
 * Sistem kullanıcı yönetimi (localStorage)
 */
(function (global) {
  'use strict';

  var USERS_KEY = 'app_users';

  function readJson(key, fallback) {
    try {
      var raw = localStorage.getItem(key);
      return raw ? JSON.parse(raw) : fallback;
    } catch (e) {
      return fallback;
    }
  }

  function writeJson(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
  }

  function seedUsers() {
    var stored = readJson(USERS_KEY, null);
    if (stored && stored.length) return stored;

    var seeds = [];
    var admins = global.FALLBACK_ADMINS || {};
    Object.keys(admins).forEach(function (email, idx) {
      var meta = admins[email];
      seeds.push({
        id: 'sys_' + (idx + 1),
        fullName: meta.fullName || email.split('@')[0],
        email: email.toLowerCase(),
        role: meta.role || 'admin',
        department: 'Yönetim',
        status: 'active',
        joinedAt: '2023-01-01'
      });
    });

    if (!seeds.length) {
      seeds = [
        { id: 'sys_1', fullName: 'Sistem Yöneticisi', email: 'admin.test@crowehsy.net', role: 'admin', department: 'Yönetim', status: 'active', joinedAt: '2026-01-01' }
      ];
    }

    writeJson(USERS_KEY, seeds);
    return seeds;
  }

  function listUsers() {
    return readJson(USERS_KEY, seedUsers());
  }

  function saveUsers(users) {
    writeJson(USERS_KEY, users);
  }

  function addUser(payload) {
    var users = listUsers();
    var email = (payload.email || '').toLowerCase().trim();
    if (!email || !payload.fullName) {
      return { ok: false, error: 'Ad soyad ve e-posta zorunludur.' };
    }
    if (users.some(function (u) { return u.email === email; })) {
      return { ok: false, error: 'Bu e-posta zaten kayıtlı.' };
    }
    users.push({
      id: 'u_' + Date.now(),
      fullName: payload.fullName.trim(),
      email: email,
      role: payload.role || 'employee',
      department: payload.department || 'Operasyon',
      status: 'active',
      joinedAt: new Date().toISOString().slice(0, 10)
    });
    saveUsers(users);
    return { ok: true, users: users };
  }

  function suspendUser(userId) {
    var users = listUsers();
    var idx = users.findIndex(function (u) { return u.id === userId; });
    if (idx === -1) return { ok: false, error: 'Kullanıcı bulunamadı.' };
    users[idx].status = 'suspended';
    users[idx].suspendedAt = new Date().toISOString().slice(0, 10);
    saveUsers(users);
    return { ok: true, users: users };
  }

  function reactivateUser(userId) {
    var users = listUsers();
    var idx = users.findIndex(function (u) { return u.id === userId; });
    if (idx === -1) return { ok: false, error: 'Kullanıcı bulunamadı.' };
    users[idx].status = 'active';
    delete users[idx].suspendedAt;
    saveUsers(users);
    return { ok: true, users: users };
  }

  function roleLabel(role) {
    return ({ admin: 'Yönetici', employee: 'Çalışan', auditor: 'Denetçi' })[role] || role;
  }

  function escapeHtml(text) {
    return String(text || '').replace(/[&<>"']/g, function (m) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[m];
    });
  }

  function renderTable(filter) {
    var tbody = document.getElementById('usersTableBody');
    var countEl = document.getElementById('totalUsers');
    if (!tbody) return;

    var q = (filter || '').toLowerCase().trim();
    var users = listUsers().filter(function (u) {
      if (!q) return true;
      return (
        (u.fullName || '').toLowerCase().indexOf(q) !== -1 ||
        (u.email || '').toLowerCase().indexOf(q) !== -1 ||
        (u.department || '').toLowerCase().indexOf(q) !== -1
      );
    });

    if (countEl) countEl.textContent = String(listUsers().length);

    if (!users.length) {
      tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Kullanıcı bulunamadı</td></tr>';
      return;
    }

    tbody.innerHTML = users.map(function (u) {
      var statusClass = u.status === 'active' ? 'is-active' : 'is-suspended';
      var statusText = u.status === 'active' ? 'Aktif' : 'Askıda';
      var roleClass = u.role === 'admin' ? 'is-admin' : (u.role === 'auditor' ? 'is-auditor' : '');
      var action = u.status === 'active'
        ? '<button type="button" class="btn btn-sm btn-outline-warning users-suspend-btn" data-id="' + escapeHtml(u.id) + '" title="Askıya al"><i class="ph ph-pause-circle"></i></button>'
        : '<button type="button" class="btn btn-sm btn-outline-success users-reactivate-btn" data-id="' + escapeHtml(u.id) + '" title="Aktifleştir"><i class="ph ph-play-circle"></i></button>';

      return '<tr>' +
        '<td><strong>' + escapeHtml(u.fullName) + '</strong></td>' +
        '<td>' + escapeHtml(u.email) + '</td>' +
        '<td><span class="status-badge ' + roleClass + '">' + escapeHtml(roleLabel(u.role)) + '</span></td>' +
        '<td>' + escapeHtml(u.department || '—') + '</td>' +
        '<td><span class="status-badge ' + statusClass + '">' + statusText + '</span></td>' +
        '<td>' + escapeHtml(u.joinedAt || '—') + '</td>' +
        '<td class="text-center">' + action + '</td>' +
        '</tr>';
    }).join('');

    tbody.querySelectorAll('.users-suspend-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        if (!confirm('Bu kullanıcıyı askıya almak istediğinize emin misiniz?')) return;
        var res = suspendUser(btn.getAttribute('data-id'));
        if (!res.ok) { alert(res.error); return; }
        renderTable(document.getElementById('usersSearchInput').value);
      });
    });

    tbody.querySelectorAll('.users-reactivate-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var res = reactivateUser(btn.getAttribute('data-id'));
        if (!res.ok) { alert(res.error); return; }
        renderTable(document.getElementById('usersSearchInput').value);
      });
    });
  }

  function bindForm() {
    var form = document.getElementById('addUserForm');
    if (!form) return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var res = addUser({
        fullName: document.getElementById('newUserName').value,
        email: document.getElementById('newUserEmail').value,
        role: document.getElementById('newUserRole').value,
        department: document.getElementById('newUserDepartment').value
      });
      if (!res.ok) { alert(res.error); return; }
      if (window.jQuery) jQuery('#addUserModal').modal('hide');
      form.reset();
      renderTable(document.getElementById('usersSearchInput').value);
    });
  }

  function initUsersPage() {
    seedUsers();
    bindForm();
    var search = document.getElementById('usersSearchInput');
    if (search) {
      search.addEventListener('input', function () {
        renderTable(search.value);
      });
    }
    renderTable('');
  }

  global.UsersAdmin = {
    listUsers: listUsers,
    addUser: addUser,
    suspendUser: suspendUser,
    reactivateUser: reactivateUser,
    initUsersPage: initUsersPage,
    renderTable: renderTable
  };
})(window);
