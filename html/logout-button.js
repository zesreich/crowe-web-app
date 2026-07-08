// Simplified Logout Button - No animations
(function() {
  'use strict';
  
  function initLogoutButtons() {
document.querySelectorAll('.logoutButton').forEach(button => {
      // Remove existing event listeners if button was already initialized
      if (button.dataset.initialized === 'true') {
        return; // Already initialized
      }
      
      button.dataset.initialized = 'true';
      
      // Simple click handler - no animations
      const clickHandler = (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        // Direct logout without animations
                  if (typeof Auth !== 'undefined' && Auth.logout) {
                    Auth.logout();
                  }
      };
      
      // Add click event listener
      button.addEventListener('click', clickHandler);
    });
  }
  
  // Initialize on page load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLogoutButtons);
  } else {
    initLogoutButtons();
  }
  
  // Re-initialize on page navigation
  window.addEventListener('pageshow', function() {
    setTimeout(initLogoutButtons, 100);
  });
  
  window.addEventListener('hashchange', function() {
    setTimeout(initLogoutButtons, 100);
  });
  
  window.addEventListener('load', function() {
    setTimeout(initLogoutButtons, 100);
  });
})();
