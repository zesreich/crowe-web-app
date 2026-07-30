/* HSY Crowe shell — theme, nav, user, profile, soft transitions */
(function () {
  "use strict";

  var STORAGE = "hsy-atlas-theme";
  var LEGACY = "darkmode";

  function applyTheme() {
    document.documentElement.setAttribute("data-theme", "light");
    document.documentElement.classList.remove("darkmode");
    document.body.classList.remove("darkmode");
    localStorage.setItem(STORAGE, "light");
    localStorage.setItem(LEGACY, "light");
    document.documentElement.removeAttribute("data-brand");
    try {
      localStorage.removeItem("hsy-atlas-brand");
    } catch (e) {}
  }

  try {
    applyTheme();
  } catch (e) {}

  function bindTheme() {
    var picker = document.getElementById("brandThemePicker");
    if (picker) picker.remove();
  }

  function initials(name) {
    if (!name) return "HS";
    var parts = String(name).split(/[@\s.]+/).filter(Boolean);
    var a = parts[0] && parts[0][0] ? parts[0][0] : "";
    var b = parts[1] && parts[1][0] ? parts[1][0] : "";
    return (a + b).toUpperCase() || "HS";
  }

  function softNavigate(href) {
    if (!href || href === "#" || href.indexOf("javascript:") === 0) return;
    if (href.indexOf("http") === 0 && href.indexOf(location.origin) !== 0) {
      window.location.href = href;
      return;
    }
    var go = function () {
      window.location.href = href;
    };
    document.body.classList.add("atlas-page-leave");
    if (document.startViewTransition) {
      try {
        document.startViewTransition(go);
        return;
      } catch (e) {}
    }
    setTimeout(go, 180);
  }

  function bindSoftTransitions() {
    document.body.classList.add("atlas-page-enter");
    document.addEventListener("click", function (e) {
      var link = e.target.closest("a.nav-btn, .rail a.brand, a.atlas-soft-nav");
      if (!link) return;
      var href = link.getAttribute("href");
      if (!href || href === "#" || link.hasAttribute("download") || link.target === "_blank") return;
      if (link.classList.contains("active-link-disabled")) {
        e.preventDefault();
        return;
      }
      var current = (location.pathname.split("/").pop() || "").toLowerCase();
      var next = (href.split("/").pop() || href).toLowerCase();
      if (current && next && current === next) {
        e.preventDefault();
        return;
      }
      e.preventDefault();
      softNavigate(href);
    }, true);
  }

  function ensureProfileModal() {
    if (document.getElementById("atlasProfileModal")) return;
    var wrap = document.createElement("div");
    wrap.id = "atlasProfileModal";
    wrap.className = "atlas-profile-modal";
    wrap.hidden = true;
    wrap.innerHTML =
      '<div class="atlas-profile-backdrop" data-close="1"></div>' +
      '<div class="atlas-profile-sheet" role="dialog" aria-modal="true" aria-labelledby="atlasProfileTitle">' +
      '  <div class="atlas-profile-head">' +
      '    <h2 id="atlasProfileTitle">Hesap ayarları</h2>' +
      '    <button type="button" class="icon-btn" data-close="1" aria-label="Kapat"><i class="ph ph-x"></i></button>' +
      "  </div>" +
      '  <div class="atlas-profile-body">' +
      '    <div class="atlas-profile-avatar-row">' +
      '      <div class="atlas-profile-avatar" id="atlasProfileAvatarPreview">HS</div>' +
      '      <div class="atlas-profile-avatar-actions">' +
      '        <label class="btn btn-sm btn-primary mb-0" style="cursor:pointer;">' +
      '          <i class="ph ph-upload-simple"></i> Avatar yükle' +
      '          <input type="file" id="atlasProfileAvatarInput" accept="image/*" hidden>' +
      "        </label>" +
      '        <button type="button" class="btn btn-sm btn-outline-dark" id="atlasProfileAvatarClear">Kaldır</button>' +
      "      </div>" +
      "    </div>" +
      '    <div class="form-group">' +
      '      <label for="atlasProfileName">Görünen ad</label>' +
      '      <input type="text" class="form-control" id="atlasProfileName">' +
      "    </div>" +
      '    <div class="form-group">' +
      '      <label>E-posta</label>' +
      '      <input type="text" class="form-control" id="atlasProfileEmail" readonly>' +
      "    </div>" +
      '    <hr>' +
      '    <h3 class="atlas-profile-section">Şifre</h3>' +
      '    <p class="atlas-profile-hint" id="atlasProfileHintText">Hatırlatıcı: —</p>' +
      '    <div class="atlas-profile-actions">' +
      '      <button type="button" class="btn btn-primary" id="atlasProfileSaveName"><i class="ph ph-floppy-disk"></i> İsmi kaydet</button>' +
      '      <button type="button" class="btn btn-outline-dark" id="atlasProfileChangePass"><i class="ph ph-lock-key"></i> Şifre değiştir</button>' +
      '      <button type="button" class="btn btn-outline-danger" id="atlasProfileResetPass"><i class="ph ph-arrow-counter-clockwise"></i> Şifreyi sıfırla</button>' +
      "    </div>" +
      "  </div>" +
      "</div>";
    document.body.appendChild(wrap);

    wrap.addEventListener("click", function (e) {
      if (e.target && e.target.closest && e.target.closest("[data-close='1']")) {
        e.preventDefault();
        e.stopPropagation();
        closeProfile();
      }
    });
    document.addEventListener("keydown", function (e) {
      if (e.key !== "Escape") return;
      var modal = document.getElementById("atlasProfileModal");
      if (modal && !modal.hidden) closeProfile();
    });

    document.getElementById("atlasProfileSaveName").addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      var authApi = window.Auth || (typeof Auth !== "undefined" ? Auth : null);
      if (!authApi || typeof authApi.updateProfile !== "function") {
        alert("Oturum modülü yüklenemedi. Sayfayı yenileyip tekrar deneyin.");
        return;
      }
      var nameInput = document.getElementById("atlasProfileName");
      var name = nameInput ? nameInput.value : "";
      var result = authApi.updateProfile({ fullName: name });
      if (!result || !result.success) {
        alert((result && result.error) || "Kaydedilemedi");
        return;
      }
      bindUser();
      var nameEl = document.getElementById("personaName");
      if (nameEl) nameEl.textContent = result.user.fullName;
      fillProfileForm();
      alert("İsim güncellendi.");
    });

    document.getElementById("atlasProfileChangePass").addEventListener("click", function () {
      softNavigate("password-change.html?force=1");
    });

    document.getElementById("atlasProfileResetPass").addEventListener("click", function () {
      var authApi = window.Auth || (typeof Auth !== "undefined" ? Auth : null);
      if (!authApi) return;
      if (!confirm("Şifre varsayılan haline (Crowe2022!) sıfırlansın mı? Sonraki girişte yeniden değiştirmeniz gerekir.")) return;
      var result = authApi.resetPasswordWithDefault();
      if (!result.success) {
        alert(result.error || "Sıfırlanamadı");
        return;
      }
      softNavigate("password-change.html");
    });

    document.getElementById("atlasProfileAvatarInput").addEventListener("change", function (e) {
      var file = e.target.files && e.target.files[0];
      var authApi = window.Auth || (typeof Auth !== "undefined" ? Auth : null);
      if (!file || !authApi) return;
      if (file.size > 800 * 1024) {
        alert("Avatar en fazla 800KB olabilir.");
        e.target.value = "";
        return;
      }
      var reader = new FileReader();
      reader.onload = function () {
        var dataUrl = String(reader.result || "");
        var result = authApi.updateProfile({ avatar: dataUrl });
        if (!result.success) {
          alert(result.error || "Avatar kaydedilemedi");
          return;
        }
        bindUser();
        fillProfileForm();
      };
      reader.readAsDataURL(file);
    });

    document.getElementById("atlasProfileAvatarClear").addEventListener("click", function () {
      var authApi = window.Auth || (typeof Auth !== "undefined" ? Auth : null);
      if (!authApi) return;
      authApi.updateProfile({ avatar: "" });
      bindUser();
      fillProfileForm();
    });
  }

  function fillProfileForm() {
    var authApi = window.Auth || (typeof Auth !== "undefined" ? Auth : null);
    if (!authApi) return;
    var user = authApi.getCurrentUser();
    if (!user) return;
    var email = user.username || user.email || "";
    var savedName = "";
    try {
      if (email) savedName = localStorage.getItem("userDisplayName_" + String(email).toLowerCase()) || "";
    } catch (err) {}
    document.getElementById("atlasProfileName").value = savedName || user.fullName || "";
    document.getElementById("atlasProfileEmail").value = email;
    var hint = authApi.getPasswordHint ? authApi.getPasswordHint() : "";
    document.getElementById("atlasProfileHintText").textContent = "Hatırlatıcı: " + (hint || "—");
    var preview = document.getElementById("atlasProfileAvatarPreview");
    if (user.avatar) {
      preview.style.backgroundImage = "url('" + user.avatar + "')";
      preview.textContent = "";
      preview.classList.add("has-image");
    } else {
      preview.style.backgroundImage = "";
      preview.classList.remove("has-image");
      preview.textContent = initials(savedName || user.fullName || user.username || user.email);
    }
  }

  function openProfile() {
    ensureProfileModal();
    fillProfileForm();
    document.getElementById("atlasProfileModal").hidden = false;
    document.body.classList.add("atlas-profile-open");
  }

  function closeProfile() {
    var modal = document.getElementById("atlasProfileModal");
    if (modal) modal.hidden = true;
    document.body.classList.remove("atlas-profile-open");
  }

  function applyAvatarToEl(el, user, email) {
    if (!el) return;
    if (user && user.avatar) {
      el.style.backgroundImage = "url('" + user.avatar + "')";
      el.style.backgroundSize = "cover";
      el.style.backgroundPosition = "center";
      el.textContent = "";
      el.classList.add("has-image");
    } else {
      el.style.backgroundImage = "";
      el.classList.remove("has-image");
      el.textContent = initials(email);
    }
  }

  function bindUser() {
    var user = null;
    var auditorEmail = null;
    try {
      auditorEmail = localStorage.getItem("auditorUser");
    } catch (e) {}

    if (auditorEmail && document.body.getAttribute("data-atlas-page") === "auditor") {
      var avA = document.getElementById("userAvatarInitial");
      applyAvatarToEl(avA, null, auditorEmail);
      var avA2 = document.querySelector(".rail-foot .avatar");
      applyAvatarToEl(avA2, null, auditorEmail);
      var nameElA = document.getElementById("personaName");
      if (nameElA) nameElA.textContent = auditorEmail.split("@")[0];
      var roleElA = document.getElementById("personaRole");
      if (roleElA) roleElA.textContent = "Denetçi";
      return;
    }

    try {
      var authApi = window.Auth || (typeof Auth !== "undefined" ? Auth : null);
      if (authApi && typeof authApi.getCurrentUser === "function") {
        user = authApi.getCurrentUser();
      }
    } catch (e) {}
    if (!user) return;

    var email = user.username || user.email || "";
    var savedName = "";
    try {
      if (email) savedName = localStorage.getItem("userDisplayName_" + String(email).toLowerCase()) || "";
    } catch (e2) {}
    var displayName = savedName || user.fullName || (email ? email.split("@")[0] : "Yönetici");
    if (savedName && user.fullName !== savedName) {
      user.fullName = savedName;
      try {
        localStorage.setItem("auth_user", JSON.stringify(user));
      } catch (e3) {}
    }
    applyAvatarToEl(document.getElementById("userAvatarInitial"), user, displayName || email);
    applyAvatarToEl(document.querySelector(".top-persona .avatar"), user, displayName || email);
    applyAvatarToEl(document.querySelector(".rail-foot .avatar"), user, displayName || email);
    var nameEl = document.getElementById("personaName");
    if (nameEl) nameEl.textContent = displayName;
    var roleEl = document.getElementById("personaRole");
    if (roleEl) roleEl.textContent = user.role === "admin" ? "Yönetici" : "Kullanıcı";
    var emailEl = document.getElementById("userEmail");
    if (emailEl) emailEl.textContent = email || displayName;
  }

  function bindProfileTrigger() {
    var persona = document.querySelector(".top-persona.persona") || document.querySelector(".rail-foot .persona");
    if (!persona || persona.dataset.profileBound) return;
    if (document.body.getAttribute("data-atlas-page") === "auditor") return;
    persona.dataset.profileBound = "1";
    persona.classList.add("is-clickable");
    persona.setAttribute("role", "button");
    persona.setAttribute("tabindex", "0");
    persona.setAttribute("title", "Hesap ayarları");
    persona.addEventListener("click", function (e) {
      e.preventDefault();
      openProfile();
    });
    persona.addEventListener("keydown", function (e) {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        openProfile();
      }
    });
  }

  function bindEnv() {
    var badge = document.getElementById("adminEnvBadge");
    if (!badge || typeof window.APP_ENV === "undefined") return;
    badge.textContent = window.APP_ENV;
    badge.classList.toggle("is-dev", window.APP_ENV === "development");
    badge.classList.remove("d-none");
  }

  function bindSearch() {
    var routes = [
      { k: ["müşteri", "musteri", "client", "firma"], href: "client-list.html" },
      { k: ["teklif", "offer"], href: "offers.html" },
      { k: ["sözleşme", "sozlesme", "contract"], href: "contracts.html" },
      { k: ["ödeme", "odeme", "payment"], href: "payments.html" },
      { k: ["rapor", "report"], href: "reports.html" },
      { k: ["kullanıcı", "kullanici", "user"], href: "users.html" },
      { k: ["çevrim", "cevrim", "online"], href: "online-users.html" },
      { k: ["panel", "dashboard", "kontrol"], href: "dashboard.html" },
      { k: ["ekosistem", "atlas", "dosya", "senkron"], href: "ecosystem-mockup/" },
      { k: ["genel kurul", "beyan"], href: "genel-kurul-beyan.html" },
      { k: ["yetki yazı", "yetki yazi", "yetki"], href: "genel-kurul-yetki.html" },
      { k: ["denetçi", "denetci", "auditor"], href: "auditor-dashboard.html" },
      { k: ["şifre", "sifre", "password"], href: "password-change.html" },
    ];

    function go(input) {
      if (!input) return;
      input.addEventListener("keydown", function (e) {
        if (e.key !== "Enter") return;
        var q = (input.value || "").toLowerCase().trim();
        if (!q) return;
        for (var i = 0; i < routes.length; i++) {
          var r = routes[i];
          for (var j = 0; j < r.k.length; j++) {
            if (q.indexOf(r.k[j]) !== -1) {
              softNavigate(r.href);
              return;
            }
          }
        }
      });
    }

    go(document.getElementById("adminGlobalSearch"));
    go(document.getElementById("atlasSearch"));
  }

  function bindLogout() {
    var btn = document.getElementById("atlasLogout");
    if (!btn || btn.dataset.bound) return;
    btn.dataset.bound = "1";
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      var auditorEmail = null;
      try {
        auditorEmail = localStorage.getItem("auditorUser");
      } catch (err) {}
      if (auditorEmail && document.body.getAttribute("data-atlas-page") === "auditor") {
        localStorage.removeItem("auditorUser");
        localStorage.removeItem("auditorPendingPasswordChange");
        localStorage.removeItem("loginPortal");
        window.location.replace("auditor-login.html");
        return;
      }
      var authApi = window.Auth || (typeof Auth !== "undefined" ? Auth : null);
      if (authApi && typeof authApi.logout === "function") {
        authApi.logout({ redirect: true });
        return;
      }
      try {
        localStorage.removeItem("auth_user");
        localStorage.removeItem("auth_token");
        localStorage.removeItem("pendingPasswordChange");
        localStorage.removeItem("loginPortal");
      } catch (err2) {}
      window.location.replace("login.html");
    });
  }

  function ensureGenelKurulNav() {
    var nav = document.querySelector(".rail > .nav");
    if (!nav) return;

    var existing = nav.querySelector('[data-nav-group="genel-kurul"]');
    if (existing) {
      // Eski/kapalı toggle versiyonunu açık listeye çevir
      var toggle = existing.querySelector(".nav-group-toggle");
      var sub = existing.querySelector(".nav-sub");
      if (toggle) {
        var label = document.createElement("div");
        label.className = "nav-group-label";
        label.innerHTML = '<i class="ph ph-gavel" aria-hidden="true"></i><span>Genel Kurul İşlemleri</span>';
        toggle.replaceWith(label);
      }
      if (sub) {
        sub.hidden = false;
        sub.removeAttribute("hidden");
      }
      existing.classList.add("is-open");

      // Sözleşmeler altına taşı (daha görünür)
      var contracts = nav.querySelector('a.nav-btn[href="contracts.html"]');
      var payments = nav.querySelector('a.nav-btn[href="payments.html"]');
      if (contracts && payments && existing.previousElementSibling !== contracts) {
        nav.insertBefore(existing, payments);
      }
      return;
    }

    var group = document.createElement("div");
    group.className = "nav-group is-open";
    group.setAttribute("data-nav-group", "genel-kurul");
    group.innerHTML =
      '<div class="nav-group-label"><i class="ph ph-gavel" aria-hidden="true"></i><span>Genel Kurul İşlemleri</span></div>' +
      '<div class="nav-sub" id="navGenelKurul">' +
      '<a class="nav-btn nav-sub-btn" href="genel-kurul-beyan.html"><i class="ph ph-article"></i><span>Beyan Yazıları</span></a>' +
      '<a class="nav-btn nav-sub-btn" href="genel-kurul-yetki.html"><i class="ph ph-certificate"></i><span>Yetki Yazıları</span></a>' +
      "</div>";

    var payments = nav.querySelector('a.nav-btn[href="payments.html"]');
    var contracts = nav.querySelector('a.nav-btn[href="contracts.html"]');
    if (payments) nav.insertBefore(group, payments);
    else if (contracts && contracts.nextSibling) nav.insertBefore(group, contracts.nextSibling);
    else {
      var logout = nav.querySelector(".nav-logout");
      if (logout) nav.insertBefore(group, logout);
      else nav.appendChild(group);
    }
  }

  function bindNavGroups() {
    ensureGenelKurulNav();
    var path = String(window.location.pathname || "").split("/").pop() || "";
    document.querySelectorAll(".nav-group[data-nav-group] .nav-sub-btn").forEach(function (link) {
      var href = link.getAttribute("href") || "";
      if (href === path) link.setAttribute("aria-current", "page");
    });
  }

  function init() {
    bindTheme();
    bindSoftTransitions();
    bindUser();
    bindProfileTrigger();
    bindEnv();
    bindSearch();
    bindLogout();
    bindNavGroups();
    setTimeout(bindUser, 200);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }

  window.AtlasShell = {
    applyTheme: applyTheme,
    softNavigate: softNavigate,
    openProfile: openProfile,
    closeProfile: closeProfile,
  };
})();
