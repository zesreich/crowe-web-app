// Prevent page reload when clicking on the active sidebar link
(function() {
    'use strict';
    
    // Store handlers to prevent duplicate listeners
    const linkHandlers = new WeakMap();
    let isInitialized = false;
    
    function preventActiveLinkReload() {
        // Get current page filename (normalize it)
        const currentPath = window.location.pathname;
        const currentPage = currentPath.split('/').pop() || currentPath;
        const normalizedCurrent = currentPage.toLowerCase();
        
        // Find all sidebar navigation links
        document.querySelectorAll('.sidebar .nav-link[href], .notika-navbar .nav-link[href]').forEach(link => {
            // Remove existing handler if any
            const existingHandler = linkHandlers.get(link);
            if (existingHandler) {
                link.removeEventListener('click', existingHandler.handler, existingHandler.useCapture);
                linkHandlers.delete(link);
            }
            
            const linkHref = link.getAttribute('href');
            if (!linkHref || linkHref === '#' || linkHref.startsWith('javascript:')) {
                return; // Skip non-page links
            }
            
            // Normalize link href
            const linkPage = linkHref.split('/').pop() || linkHref;
            const normalizedLink = linkPage.toLowerCase();
            
            // Check if this link points to the current page
            const isCurrentPage = normalizedLink === normalizedCurrent || 
                                 linkHref === currentPage ||
                                 linkHref === currentPath ||
                                 (normalizedLink && normalizedCurrent.includes(normalizedLink));
            
            // Check if parent has 'active' class
            const parentItem = link.closest('.nav-item');
            const isActive = parentItem && parentItem.classList.contains('active');
            
            if (isCurrentPage || isActive) {
                // Create click handler - only prevent default, don't stop propagation
                // This allows other event listeners to work normally
                const clickHandler = function(e) {
                    // Only prevent if it's actually the active link
                    if (link.classList.contains('active-link-disabled')) {
                        e.preventDefault();
                        return false;
                    }
                };
                
                // Store handler with metadata
                linkHandlers.set(link, {
                    handler: clickHandler,
                    useCapture: false // Don't use capture phase - it breaks other listeners
                });
                
                // Add event listener in bubble phase (normal)
                link.addEventListener('click', clickHandler, false);
                
                // Add visual feedback that it's disabled
                if (!link.classList.contains('active-link-disabled')) {
                    link.classList.add('active-link-disabled');
                }
            } else {
                // Remove disabled styling if not active
                link.classList.remove('active-link-disabled');
            }
        });
        
        isInitialized = true;
    }
    
    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', preventActiveLinkReload);
    } else {
        preventActiveLinkReload();
    }
    
    // Also run on page show (for back/forward navigation) - but only once
    window.addEventListener('pageshow', function(e) {
        if (!isInitialized || e.persisted) {
            setTimeout(preventActiveLinkReload, 50);
        }
    });
    
    // Run after a short delay to ensure DOM is fully ready
    setTimeout(preventActiveLinkReload, 100);
})();

