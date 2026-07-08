// Dark Mode Toggle — Atlas-synced (data-theme + legacy .darkmode)
(function () {
  "use strict";

  const STORAGE_KEY = "darkmode";
  const ATLAS_KEY = "hsy-atlas-theme";
  let themeSwitch = null;
  let isApplyingTheme = false;

  function getThemeSwitch() {
    if (!themeSwitch) {
      themeSwitch = document.getElementById("theme-switch");
    }
    return themeSwitch;
  }

  function readIsDark() {
    const legacy = localStorage.getItem(STORAGE_KEY);
    const atlas = localStorage.getItem(ATLAS_KEY);
    if (legacy === "active") return true;
    if (legacy === null && atlas === "dark") return true;
    if (legacy === "active" || atlas === "dark") return true;
    if (legacy === null && atlas === null) {
      return window.matchMedia("(prefers-color-scheme: dark)").matches;
    }
    return false;
  }

  function updateSwitchUI(isDark) {
    const switchBtn = getThemeSwitch();
    if (!switchBtn) return;
    switchBtn.setAttribute("aria-label", isDark ? "Açık temaya geç" : "Koyu temaya geç");
    const svgs = switchBtn.querySelectorAll("svg");
    if (svgs.length >= 2) {
      svgs[0].style.display = isDark ? "none" : "block";
      svgs[1].style.display = isDark ? "block" : "none";
    }
  }

  function enableDarkmode() {
    document.documentElement.setAttribute("data-theme", "dark");
    document.documentElement.classList.add("darkmode");
    document.body.classList.add("darkmode");
    localStorage.setItem(STORAGE_KEY, "active");
    localStorage.setItem(ATLAS_KEY, "dark");
    updateSwitchUI(true);
    window.dispatchEvent(new CustomEvent("themeChanged", { detail: { theme: "dark" } }));
  }

  function disableDarkmode() {
    document.documentElement.setAttribute("data-theme", "light");
    document.documentElement.classList.remove("darkmode");
    document.body.classList.remove("darkmode");
    localStorage.setItem(STORAGE_KEY, "light");
    localStorage.setItem(ATLAS_KEY, "light");
    updateSwitchUI(false);
    window.dispatchEvent(new CustomEvent("themeChanged", { detail: { theme: "light" } }));
  }

  function applyTheme() {
    if (isApplyingTheme) return;
    isApplyingTheme = true;
    if (readIsDark()) {
      enableDarkmode();
    } else {
      disableDarkmode();
    }
    setTimeout(function () {
      isApplyingTheme = false;
    }, 50);
  }

  // Early apply before paint if possible
  try {
    if (readIsDark()) {
      document.documentElement.setAttribute("data-theme", "dark");
      document.documentElement.classList.add("darkmode");
    } else {
      document.documentElement.setAttribute("data-theme", "light");
    }
  } catch (e) {}

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", applyTheme);
  } else {
    applyTheme();
  }

  function initThemeSwitch() {
    const switchBtn = getThemeSwitch();
    if (!switchBtn) return;
    const fresh = switchBtn.cloneNode(true);
    switchBtn.parentNode.replaceChild(fresh, switchBtn);
    themeSwitch = fresh;
    fresh.addEventListener("click", function () {
      if (readIsDark()) {
        disableDarkmode();
      } else {
        enableDarkmode();
      }
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initThemeSwitch);
  } else {
    initThemeSwitch();
  }

  window.addEventListener("storage", function (e) {
    if (e.key === STORAGE_KEY || e.key === ATLAS_KEY) {
      applyTheme();
    }
  });

  window.addEventListener("themeChanged", function (e) {
    updateSwitchUI(e.detail && e.detail.theme === "dark");
  });

  window.addEventListener("pageshow", applyTheme);
  window.addEventListener("focus", applyTheme);

  setInterval(function () {
    if (isApplyingTheme) return;
    const isDarkActive =
      document.body.classList.contains("darkmode") ||
      document.documentElement.getAttribute("data-theme") === "dark";
    const shouldBeDark = readIsDark();
    if (isDarkActive !== shouldBeDark) {
      applyTheme();
    }
  }, 800);

  window.DarkMode = {
    enable: enableDarkmode,
    disable: disableDarkmode,
    toggle: function () {
      readIsDark() ? disableDarkmode() : enableDarkmode();
    },
    isActive: function () {
      return readIsDark();
    },
  };
})();
