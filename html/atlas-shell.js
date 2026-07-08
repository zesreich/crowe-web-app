/* HSY Atlas shell — theme, nav, user, shortcuts (all admin pages) */
(function () {
  "use strict";

  var STORAGE = "hsy-atlas-theme";
  var LEGACY = "darkmode";

  function readDark() {
    var legacy = localStorage.getItem(LEGACY);
    var atlas = localStorage.getItem(STORAGE);
    if (legacy === "active") return true;
    if (legacy === "light") return false;
    if (atlas === "dark") return true;
    if (atlas === "light") return false;
    return window.matchMedia("(prefers-color-scheme: dark)").matches;
  }

  function applyTheme(dark) {
    document.documentElement.setAttribute("data-theme", dark ? "dark" : "light");
    document.documentElement.classList.toggle("darkmode", dark);
    document.body.classList.toggle("darkmode", dark);
    localStorage.setItem(STORAGE, dark ? "dark" : "light");
    localStorage.setItem(LEGACY, dark ? "active" : "light");
    var icon = document.getElementById("themeIcon");
    if (icon) {
      icon.className = dark ? "ph ph-sun" : "ph ph-moon";
    }
    var legacyBtn = document.getElementById("theme-switch");
    if (legacyBtn) {
      var svgs = legacyBtn.querySelectorAll("svg");
      if (svgs.length >= 2) {
        svgs[0].style.display = dark ? "none" : "block";
        svgs[1].style.display = dark ? "block" : "none";
      }
      legacyBtn.setAttribute("aria-label", dark ? "Açık temaya geç" : "Koyu temaya geç");
    }
  }

  try {
    applyTheme(readDark());
  } catch (e) {}

  function bindTheme() {
    var btn = document.getElementById("themeToggle");
    if (btn && !btn.dataset.bound) {
      btn.dataset.bound = "1";
      btn.addEventListener("click", function () {
        applyTheme(!readDark());
      });
    }
    var legacy = document.getElementById("theme-switch");
    if (legacy && !legacy.dataset.bound) {
      legacy.dataset.bound = "1";
      legacy.addEventListener("click", function () {
        applyTheme(!readDark());
      });
    }
  }

  function initials(name) {
    if (!name) return "HS";
    var parts = String(name).split(/[@\s.]+/).filter(Boolean);
    var a = parts[0] && parts[0][0] ? parts[0][0] : "";
    var b = parts[1] && parts[1][0] ? parts[1][0] : "";
    return (a + b).toUpperCase() || "HS";
  }

  function bindUser() {
    var user = null;
    var auditorEmail = null;
    try {
      auditorEmail = localStorage.getItem("auditorUser");
    } catch (e) {}

    if (auditorEmail && document.body.getAttribute("data-atlas-page") === "auditor") {
      var av = document.getElementById("userAvatarInitial");
      if (av) av.textContent = initials(auditorEmail);
      var av2 = document.querySelector(".rail-foot .avatar");
      if (av2) av2.textContent = initials(auditorEmail);
      var nameEl = document.getElementById("personaName");
      if (nameEl) nameEl.textContent = auditorEmail.split("@")[0];
      var roleEl = document.getElementById("personaRole");
      if (roleEl) roleEl.textContent = "Denetçi";
      return;
    }

    try {
      if (window.Auth && typeof Auth.getCurrentUser === "function") {
        user = Auth.getCurrentUser();
      }
    } catch (e) {}
    if (!user) return;

    var email = user.username || user.email || user.fullName || "Yönetici";
    var av = document.getElementById("userAvatarInitial");
    if (av) av.textContent = initials(email);
    var av2 = document.querySelector(".rail-foot .avatar");
    if (av2) av2.textContent = initials(email);
    var nameEl = document.getElementById("personaName");
    if (nameEl) nameEl.textContent = user.fullName || email.split("@")[0];
    var roleEl = document.getElementById("personaRole");
    if (roleEl) roleEl.textContent = user.role === "admin" ? "Yönetici" : "Kullanıcı";
    var emailEl = document.getElementById("userEmail");
    if (emailEl) emailEl.textContent = email;
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
      { k: ["denetçi", "denetci", "auditor"], href: "auditor-dashboard.html" },
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
              window.location.href = r.href;
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
      var auditorEmail = null;
      try {
        auditorEmail = localStorage.getItem("auditorUser");
      } catch (err) {}
      if (auditorEmail && document.body.getAttribute("data-atlas-page") === "auditor") {
        e.preventDefault();
        localStorage.removeItem("auditorUser");
        localStorage.removeItem("auditorPendingPasswordChange");
        window.location.href = "auditor-login.html";
        return;
      }
      if (window.Auth && Auth.logout) Auth.logout();
    });
  }

  function init() {
    bindTheme();
    bindUser();
    bindEnv();
    bindSearch();
    bindLogout();
    setTimeout(bindUser, 200);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }

  window.AtlasShell = { applyTheme: applyTheme, readDark: readDark };
})();
