// Dark Mode Toggle System
let darkmode = localStorage.getItem('darkmode')
const themeSwitch = document.getElementById('theme-switch')

const enableDarkmode = () => {
  document.body.classList.add('darkmode')
  localStorage.setItem('darkmode', 'active')
  if (themeSwitch) {
    themeSwitch.setAttribute('aria-label', 'Switch to light mode')
  }
}

const disableDarkmode = () => {
  document.body.classList.remove('darkmode')
  localStorage.setItem('darkmode', null)
  if (themeSwitch) {
    themeSwitch.setAttribute('aria-label', 'Switch to dark mode')
  }
}

// Initialize dark mode on page load
if (darkmode === "active") {
  enableDarkmode()
}

// Add click event listener
if (themeSwitch) {
  themeSwitch.addEventListener("click", () => {
    darkmode = localStorage.getItem('darkmode')
    darkmode !== "active" ? enableDarkmode() : disableDarkmode()
  })
}







