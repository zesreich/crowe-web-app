// Dark Mode Toggle System - Synchronized across all pages
(function() {
  'use strict';
  
  // Get current theme state
  let darkmode = localStorage.getItem('darkmode')
  let themeSwitch = null;
  
  // Function to get theme switch button (may not exist on all pages)
  function getThemeSwitch() {
    if (!themeSwitch) {
      themeSwitch = document.getElementById('theme-switch');
    }
    return themeSwitch;
  }
  
  // Enable dark mode
  const enableDarkmode = () => {
    document.body.classList.add('darkmode')
    localStorage.setItem('darkmode', 'active')
    const switchBtn = getThemeSwitch();
    if (switchBtn) {
      switchBtn.setAttribute('aria-label', 'Switch to light mode')
      // Update icon visibility
      const svgs = switchBtn.querySelectorAll('svg');
      if (svgs.length >= 2) {
        svgs[0].style.display = 'none';
        svgs[1].style.display = 'block';
      }
    }
    
    // Dispatch custom event for other scripts
    window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: 'dark' } }));
  }
  
  // Disable dark mode
  const disableDarkmode = () => {
    document.body.classList.remove('darkmode')
    localStorage.setItem('darkmode', null)
    const switchBtn = getThemeSwitch();
    if (switchBtn) {
      switchBtn.setAttribute('aria-label', 'Switch to dark mode')
      // Update icon visibility
      const svgs = switchBtn.querySelectorAll('svg');
      if (svgs.length >= 2) {
        svgs[0].style.display = 'block';
        svgs[1].style.display = 'none';
      }
    }
    
    // Dispatch custom event for other scripts
    window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: 'light' } }));
  }
  
  // Apply theme based on localStorage
  const applyTheme = () => {
    darkmode = localStorage.getItem('darkmode')
    if (darkmode === "active") {
      enableDarkmode()
    } else {
      disableDarkmode()
    }
  }
  
  // Initialize dark mode on page load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyTheme);
  } else {
    applyTheme();
  }
  
  // Add click event listener to theme switch button
  function initThemeSwitch() {
    const switchBtn = getThemeSwitch();
    if (switchBtn) {
      // Remove existing listeners to prevent duplicates
      const newSwitchBtn = switchBtn.cloneNode(true);
      switchBtn.parentNode.replaceChild(newSwitchBtn, switchBtn);
      themeSwitch = newSwitchBtn;
      
      newSwitchBtn.addEventListener("click", () => {
        darkmode = localStorage.getItem('darkmode')
        if (darkmode !== "active") {
          enableDarkmode()
        } else {
          disableDarkmode()
        }
      });
    }
  }
  
  // Initialize theme switch after DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initThemeSwitch);
  } else {
    initThemeSwitch();
  }
  
  // Listen for storage changes (when theme is changed in another tab/window)
  // Note: storage event only fires in OTHER tabs, not the current tab
  window.addEventListener('storage', (e) => {
    if (e.key === 'darkmode') {
      applyTheme();
    }
  });
  
  // Listen for custom theme change events (same window/tab)
  // This ensures theme changes are reflected immediately in the same window
  window.addEventListener('themeChanged', (e) => {
    // Theme already changed via localStorage, just update UI
    applyTheme();
  });
  
  // Listen for pageshow event (when navigating between pages in same tab)
  window.addEventListener('pageshow', (e) => {
    // Check if theme changed while on another page
    applyTheme();
  });
  
  // Also listen for focus event (when user switches back to this tab)
  window.addEventListener('focus', () => {
    // Re-apply theme in case it was changed in another tab
    applyTheme();
  });
  
  // Poll localStorage periodically to catch changes (fallback for same-tab sync)
  // This is a backup mechanism in case events don't fire
  setInterval(() => {
    const currentTheme = localStorage.getItem('darkmode');
    const isDarkActive = document.body.classList.contains('darkmode');
    const shouldBeDark = currentTheme === 'active';
    
    if (isDarkActive !== shouldBeDark) {
      applyTheme();
    }
  }, 500); // Check every 500ms
  
  // Expose functions globally for manual control if needed
  window.DarkMode = {
    enable: enableDarkmode,
    disable: disableDarkmode,
    toggle: () => {
      darkmode = localStorage.getItem('darkmode')
      darkmode !== "active" ? enableDarkmode() : disableDarkmode()
    },
    isActive: () => localStorage.getItem('darkmode') === 'active'
  };
})();







