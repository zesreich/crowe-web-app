// Dark Mode Toggle System
let darkmode = localStorage.getItem('darkmode')
const themeSwitch = document.getElementById('theme-switch')

const enableDarkmode = () => {
  document.documentElement.classList.add('darkmode')
  document.body.classList.add('darkmode')
  localStorage.setItem('darkmode', 'active')
  if (themeSwitch) {
    themeSwitch.setAttribute('aria-label', 'Switch to light mode')
  }
}

const disableDarkmode = () => {
  document.documentElement.classList.remove('darkmode')
  document.body.classList.remove('darkmode')
  localStorage.setItem('darkmode', null)
  if (themeSwitch) {
    themeSwitch.setAttribute('aria-label', 'Switch to dark mode')
  }
}

// Initialize dark mode on page load - check both documentElement and body
if (darkmode === "active") {
  enableDarkmode()
} else {
  // Ensure light mode is applied if darkmode is not active
  disableDarkmode()
}

// Add click event listener
if (themeSwitch) {
  themeSwitch.addEventListener("click", () => {
    darkmode = localStorage.getItem('darkmode')
    darkmode !== "active" ? enableDarkmode() : disableDarkmode()
  })
}







