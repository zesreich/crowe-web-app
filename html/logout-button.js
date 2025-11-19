const logoutButtonStates = {
  default: {
    '--figure-duration': '100',
    '--transform-figure': 'none',
    '--walking-duration': '100',
    '--transform-arm1': 'none',
    '--transform-wrist1': 'none',
    '--transform-arm2': 'none',
    '--transform-wrist2': 'none',
    '--transform-leg1': 'none',
    '--transform-calf1': 'none',
    '--transform-leg2': 'none',
    '--transform-calf2': 'none'
  },
  hover: {
    '--figure-duration': '100',
    '--transform-figure': 'translateX(1.5px)',
    '--walking-duration': '100',
    '--transform-arm1': 'rotate(-5deg)',
    '--transform-wrist1': 'rotate(-15deg)',
    '--transform-arm2': 'rotate(5deg)',
    '--transform-wrist2': 'rotate(6deg)',
    '--transform-leg1': 'rotate(-10deg)',
    '--transform-calf1': 'rotate(5deg)',
    '--transform-leg2': 'rotate(20deg)',
    '--transform-calf2': 'rotate(-20deg)'
  },
  walking1: {
    '--figure-duration': '300',
    '--transform-figure': 'translateX(11px)',
    '--walking-duration': '300',
    '--transform-arm1': 'translateX(-4px) translateY(-2px) rotate(120deg)',
    '--transform-wrist1': 'rotate(-5deg)',
    '--transform-arm2': 'translateX(4px) rotate(-110deg)',
    '--transform-wrist2': 'rotate(-5deg)',
    '--transform-leg1': 'translateX(-3px) rotate(80deg)',
    '--transform-calf1': 'rotate(-30deg)',
    '--transform-leg2': 'translateX(4px) rotate(-60deg)',
    '--transform-calf2': 'rotate(20deg)'
  },
  walking2: {
    '--figure-duration': '400',
    '--transform-figure': 'translateX(17px)',
    '--walking-duration': '300',
    '--transform-arm1': 'rotate(60deg)',
    '--transform-wrist1': 'rotate(-15deg)',
    '--transform-arm2': 'rotate(-45deg)',
    '--transform-wrist2': 'rotate(6deg)',
    '--transform-leg1': 'rotate(-5deg)',
    '--transform-calf1': 'rotate(10deg)',
    '--transform-leg2': 'rotate(10deg)',
    '--transform-calf2': 'rotate(-20deg)'
  },
  falling1: {
    '--figure-duration': '1600',
    '--walking-duration': '400',
    '--transform-arm1': 'rotate(-60deg)',
    '--transform-wrist1': 'none',
    '--transform-arm2': 'rotate(30deg)',
    '--transform-wrist2': 'rotate(120deg)',
    '--transform-leg1': 'rotate(-30deg)',
    '--transform-calf1': 'rotate(-20deg)',
    '--transform-leg2': 'rotate(20deg)'
  },
  falling2: {
    '--walking-duration': '300',
    '--transform-arm1': 'rotate(-100deg)',
    '--transform-arm2': 'rotate(-60deg)',
    '--transform-wrist2': 'rotate(60deg)',
    '--transform-leg1': 'rotate(80deg)',
    '--transform-calf1': 'rotate(20deg)',
    '--transform-leg2': 'rotate(-60deg)'
  },
  falling3: {
    '--walking-duration': '500',
    '--transform-arm1': 'rotate(-30deg)',
    '--transform-wrist1': 'rotate(40deg)',
    '--transform-arm2': 'rotate(50deg)',
    '--transform-wrist2': 'none',
    '--transform-leg1': 'rotate(-30deg)',
    '--transform-leg2': 'rotate(20deg)',
    '--transform-calf2': 'none'
  }
};

function applyLogoutButtonState(button, state) {
  const stateConfig = logoutButtonStates[state];
  if (!stateConfig) {
    return;
  }
  button.state = state;
  Object.keys(stateConfig).forEach(key => {
    button.style.setProperty(key, stateConfig[key]);
  });
}

function resetLogoutButton(button) {
  button.classList.remove('clicked', 'door-slammed', 'falling');
  applyLogoutButtonState(button, 'default');
}

function getStateDuration(state, property, fallback) {
  const raw = logoutButtonStates[state] && logoutButtonStates[state][property];
  const parsed = parseInt(raw, 10);
  return Number.isNaN(parsed) ? fallback : parsed;
}

function initLogoutButtons() {
  document.querySelectorAll('.logoutButton').forEach(button => {
    // Skip if already initialized and working
    if (button.dataset.initialized === 'true' && button.state !== undefined) {
      // Just reset to default state
      resetLogoutButton(button);
      return;
    }
    
    // Always reset button state first
    resetLogoutButton(button);
    button.dataset.initialized = 'true';

    button.addEventListener('mouseenter', () => {
      if (button.state === 'default') {
        applyLogoutButtonState(button, 'hover');
      }
    });

    button.addEventListener('mouseleave', () => {
      if (button.state === 'hover') {
        applyLogoutButtonState(button, 'default');
      }
    });

    button.addEventListener('click', () => {
      if (button.state === 'default' || button.state === 'hover') {
        button.classList.add('clicked');
        applyLogoutButtonState(button, 'walking1');
        setTimeout(() => {
          button.classList.add('door-slammed');
          applyLogoutButtonState(button, 'walking2');
          setTimeout(() => {
            button.classList.add('falling');
            applyLogoutButtonState(button, 'falling1');
            setTimeout(() => {
              applyLogoutButtonState(button, 'falling2');
              setTimeout(() => {
                applyLogoutButtonState(button, 'falling3');
                setTimeout(() => {
                  resetLogoutButton(button);
                  if (typeof Auth !== 'undefined' && Auth.logout) {
                    Auth.logout();
                  }
                }, 1000);
              }, getStateDuration('falling2', '--walking-duration', 300));
            }, getStateDuration('falling1', '--walking-duration', 400));
          }, getStateDuration('walking2', '--figure-duration', 400));
        }, getStateDuration('walking1', '--figure-duration', 300));
      }
    });
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initLogoutButtons);
} else {
  initLogoutButtons();
}

// Reset on page navigation
window.addEventListener('pageshow', function(event) {
  // Re-initialize buttons after a short delay to ensure DOM is ready
  setTimeout(initLogoutButtons, 50);
});

window.addEventListener('load', function() {
  document.querySelectorAll('.logoutButton').forEach(button => {
    resetLogoutButton(button);
    // Force reset all CSS variables
    button.style.removeProperty('--figure-duration');
    button.style.removeProperty('--transform-figure');
    button.style.removeProperty('--walking-duration');
    button.style.removeProperty('--transform-arm1');
    button.style.removeProperty('--transform-wrist1');
    button.style.removeProperty('--transform-arm2');
    button.style.removeProperty('--transform-wrist2');
    button.style.removeProperty('--transform-leg1');
    button.style.removeProperty('--transform-calf1');
    button.style.removeProperty('--transform-leg2');
    button.style.removeProperty('--transform-calf2');
  });
});

// Reset on beforeunload to prevent animation state from persisting
window.addEventListener('beforeunload', function() {
  document.querySelectorAll('.logoutButton').forEach(button => {
    resetLogoutButton(button);
  });
});
