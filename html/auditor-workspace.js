/**
 * Denetim workspace + ekip kullanıcı yönetimi
 */
(function (global) {
  'use strict';

  const WS_KEY = 'auditor_workspaces';
  const USERS_KEY = 'auditor_team_users';
  const TEAMS = ['B1_EDA', 'B2_MAS', 'B3_HAKAN'];

  function getClient() {
    if (typeof global.getSupabaseClient === 'function') {
      return global.getSupabaseClient();
    }
    return global.__supabaseClientInstance || null;
  }

  // Supabase'e arka planda yaz — hata olursa localStorage tek kaynak kalır
  function pushWorkspaceToSupabase(ws) {
    const client = getClient();
    if (!client) return;
    client.from('auditor_workspaces').upsert({
      client_id: String(ws.clientId),
      team: ws.team,
      client_name: ws.clientName || '',
      vergi_no: ws.vergiNo || '',
      items: ws.items || {}
    }, { onConflict: 'client_id,team' }).then(function (res) {
      if (res.error) console.warn('Workspace Supabase sync hatası:', res.error.message);
    });
  }

  function pushUserToSupabase(user) {
    const client = getClient();
    if (!client) return;
    client.from('auditor_team_members').upsert({
      email: user.email,
      full_name: user.fullName,
      team: user.team,
      role: user.role || 'auditor',
      status: user.status || 'active',
      joined_at: user.joinedAt || null,
      suspended_at: user.suspendedAt || null
    }, { onConflict: 'email' }).then(function (res) {
      if (res.error) console.warn('Denetçi Supabase sync hatası:', res.error.message);
    });
  }

  // Supabase'den localStorage'a yükle (sayfa açılışında çağrılır)
  async function pullFromSupabase() {
    const client = getClient();
    if (!client) return { ok: false };
    try {
      const membersRes = await client.from('auditor_team_members').select('*');
      if (!membersRes.error && membersRes.data && membersRes.data.length) {
        const users = membersRes.data.map(function (m) {
          return {
            id: m.id,
            fullName: m.full_name,
            email: m.email,
            team: m.team,
            role: m.role,
            status: m.status,
            joinedAt: m.joined_at,
            suspendedAt: m.suspended_at || undefined
          };
        });
        writeJson(USERS_KEY, users);
      }

      const wsRes = await client.from('auditor_workspaces').select('*');
      if (!wsRes.error && wsRes.data && wsRes.data.length) {
        const all = readJson(WS_KEY, {});
        wsRes.data.forEach(function (row) {
          const key = workspaceKey(row.client_id, row.team);
          all[key] = {
            clientId: row.client_id,
            team: row.team,
            clientName: row.client_name || '',
            vergiNo: row.vergi_no || '',
            createdAt: row.created_at,
            updatedAt: row.updated_at,
            items: row.items || {}
          };
        });
        writeJson(WS_KEY, all);
      }
      return { ok: true };
    } catch (e) {
      console.warn('Supabase pull hatası:', e);
      return { ok: false };
    }
  }

  function readJson(key, fallback) {
    try {
      const raw = localStorage.getItem(key);
      return raw ? JSON.parse(raw) : fallback;
    } catch (e) {
      return fallback;
    }
  }

  function writeJson(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
  }

  function workspaceKey(clientId, team) {
    return String(clientId) + '__' + String(team || 'GENEL');
  }

  function getCurrentActor() {
    const auditor = localStorage.getItem('auditorUser');
    const portal = localStorage.getItem('loginPortal');
    // auditorUser admin girişinde de set edilebiliyor; portal bilgisi belirleyici
    if (auditor && portal === 'auditor') return { email: auditor, role: 'auditor', source: 'auditor' };
    try {
      if (global.Auth && Auth.getCurrentUser) {
        const u = Auth.getCurrentUser();
        if (u) return { email: u.username || u.email, role: u.role || 'admin', source: 'admin' };
      }
    } catch (e) {}
    return { email: 'sistem', role: 'guest', source: 'none' };
  }

  function canEditWorkspace() {
    const actor = getCurrentActor();
    if (actor.source === 'auditor') {
      const users = listTeamUsers();
      const me = users.find(function (u) { return u.email === actor.email; });
      return !me || me.status !== 'suspended';
    }
    return actor.role === 'admin' || actor.role === 'auditor';
  }

  function canManageUsers() {
    const actor = getCurrentActor();
    if (actor.source === 'auditor') {
      const users = listTeamUsers();
      const me = users.find(function (u) { return u.email === actor.email; });
      return me && me.status === 'active' && (me.role === 'lead' || me.role === 'auditor');
    }
    return actor.role === 'admin';
  }

  function listWorkspaces() {
    return readJson(WS_KEY, {});
  }

  function getWorkspace(clientId, team) {
    const all = listWorkspaces();
    return all[workspaceKey(clientId, team)] || null;
  }

  function provisionWorkspace(clientId, team, meta) {
    if (!clientId || !team) return null;
    const key = workspaceKey(clientId, team);
    const all = listWorkspaces();
    if (all[key]) return all[key];

    const ws = {
      clientId: clientId,
      team: team,
      clientName: (meta && meta.clientName) || '',
      vergiNo: (meta && meta.vergiNo) || '',
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString(),
      items: global.buildDefaultWorkspaceItems ? global.buildDefaultWorkspaceItems() : {}
    };
    all[key] = ws;
    writeJson(WS_KEY, all);
    pushWorkspaceToSupabase(ws);
    return ws;
  }

  function updateWorkspaceItem(clientId, team, itemId, patch) {
    if (!canEditWorkspace()) return { ok: false, error: 'Düzenleme yetkiniz yok.' };
    const key = workspaceKey(clientId, team);
    const all = listWorkspaces();
    const ws = all[key];
    if (!ws || !ws.items[itemId]) return { ok: false, error: 'Workspace bulunamadı.' };

    const actor = getCurrentActor();
    ws.items[itemId] = Object.assign({}, ws.items[itemId], patch, {
      updatedAt: new Date().toISOString(),
      updatedBy: actor.email
    });
    ws.updatedAt = new Date().toISOString();
    all[key] = ws;
    writeJson(WS_KEY, all);
    pushWorkspaceToSupabase(ws);
    return { ok: true, item: ws.items[itemId] };
  }

  function listTeamUsers() {
    const stored = readJson(USERS_KEY, null);
    if (stored && stored.length) return stored;
    return [
      { id: 'u1', fullName: 'Denetim Lideri', email: 'auditor.lead@crowehsy.net', team: 'B1_EDA', role: 'lead', status: 'active', joinedAt: '2026-01-01' },
      { id: 'u2', fullName: 'Denetim Uzmanı', email: 'auditor.staff@crowehsy.net', team: 'B1_EDA', role: 'auditor', status: 'active', joinedAt: '2026-02-01' }
    ];
  }

  function saveTeamUsers(users) {
    writeJson(USERS_KEY, users);
  }

  function addTeamUser(payload) {
    if (!canManageUsers()) return { ok: false, error: 'Kullanıcı ekleme yetkiniz yok.' };
    const users = listTeamUsers();
    const email = (payload.email || '').toLowerCase().trim();
    if (!email || !payload.fullName || !payload.team) {
      return { ok: false, error: 'Ad, e-posta ve ekip zorunludur.' };
    }
    if (users.some(function (u) { return u.email === email; })) {
      return { ok: false, error: 'Bu e-posta zaten kayıtlı.' };
    }
    const newUser = {
      id: 'u_' + Date.now(),
      fullName: payload.fullName.trim(),
      email: email,
      team: payload.team,
      role: payload.role || 'auditor',
      status: 'active',
      joinedAt: new Date().toISOString().slice(0, 10)
    };
    users.push(newUser);
    saveTeamUsers(users);
    pushUserToSupabase(newUser);
    return { ok: true, users: users };
  }

  function suspendTeamUser(userId) {
    if (!canManageUsers()) return { ok: false, error: 'Askıya alma yetkiniz yok.' };
    const users = listTeamUsers();
    const idx = users.findIndex(function (u) { return u.id === userId; });
    if (idx === -1) return { ok: false, error: 'Kullanıcı bulunamadı.' };
    users[idx].status = 'suspended';
    users[idx].suspendedAt = new Date().toISOString().slice(0, 10);
    saveTeamUsers(users);
    pushUserToSupabase(users[idx]);
    return { ok: true, users: users };
  }

  function updateTeamUser(userId, patch) {
    if (!canManageUsers()) return { ok: false, error: 'Düzenleme yetkiniz yok.' };
    const users = listTeamUsers();
    const idx = users.findIndex(function (u) { return u.id === userId; });
    if (idx === -1) return { ok: false, error: 'Kullanıcı bulunamadı.' };
    users[idx] = Object.assign({}, users[idx], patch);
    saveTeamUsers(users);
    pushUserToSupabase(users[idx]);
    return { ok: true, users: users };
  }

  function reactivateTeamUser(userId) {
    if (!canManageUsers()) return { ok: false, error: 'Aktifleştirme yetkiniz yok.' };
    const users = listTeamUsers();
    const idx = users.findIndex(function (u) { return u.id === userId; });
    if (idx === -1) return { ok: false, error: 'Kullanıcı bulunamadı.' };
    users[idx].status = 'active';
    delete users[idx].suspendedAt;
    saveTeamUsers(users);
    pushUserToSupabase(users[idx]);
    return { ok: true, users: users };
  }

  function syncWorkspacesFromClients(clients) {
    (clients || []).forEach(function (c) {
      const team = c.ekip || c.team;
      const id = c.id || c.vergi_no || c.vergiNo;
      if (id && team) {
        provisionWorkspace(id, team, {
          clientName: c.unvan || c.clientName || '',
          vergiNo: c.vergi_no || c.vergiNo || ''
        });
      }
    });
  }

  function requireAuditorAccess() {
    const actor = getCurrentActor();
    if (actor.source === 'auditor') {
      const users = listTeamUsers();
      const me = users.find(function (u) { return u.email === actor.email; });
      if (me && me.status === 'suspended') {
        alert('Hesabınız askıya alınmış. Yöneticinizle iletişime geçin.');
        window.location.href = 'auditor-login.html';
        return false;
      }
      return true;
    }
    if (actor.role === 'admin') return true;
    window.location.href = 'auditor-login.html';
    return false;
  }

  global.AuditorWorkspace = {
    TEAMS: TEAMS,
    workspaceKey: workspaceKey,
    getCurrentActor: getCurrentActor,
    canEditWorkspace: canEditWorkspace,
    canManageUsers: canManageUsers,
    listWorkspaces: listWorkspaces,
    getWorkspace: getWorkspace,
    provisionWorkspace: provisionWorkspace,
    updateWorkspaceItem: updateWorkspaceItem,
    listTeamUsers: listTeamUsers,
    addTeamUser: addTeamUser,
    updateTeamUser: updateTeamUser,
    suspendTeamUser: suspendTeamUser,
    reactivateTeamUser: reactivateTeamUser,
    syncWorkspacesFromClients: syncWorkspacesFromClients,
    requireAuditorAccess: requireAuditorAccess,
    pullFromSupabase: pullFromSupabase
  };
})(window);
