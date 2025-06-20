document.addEventListener("DOMContentLoaded", () => {
  // Mobile Menu Toggle
  const menuToggle = document.querySelector(".menu-toggle")
  const navMenu = document.querySelector(".nav-menu")

  if (menuToggle) {
    menuToggle.addEventListener("click", () => {
      navMenu.classList.toggle("active")
      menuToggle.classList.toggle("active")
    })
  }

  // User Menu Toggle for Mobile
  const userMenuTrigger = document.querySelector(".user-menu-trigger")
  const userMenu = document.querySelector(".user-menu")

  if (userMenuTrigger && userMenu) {
    userMenuTrigger.addEventListener("click", (e) => {
      // Only handle click on mobile screens
      if (window.innerWidth <= 768) {
        e.preventDefault()
        userMenu.classList.toggle("active")
      }
    })
  }

  // Close mobile menu when clicking outside
  document.addEventListener("click", (e) => {
    if (navMenu && !navMenu.contains(e.target) && !menuToggle.contains(e.target)) {
      navMenu.classList.remove("active")
      menuToggle.classList.remove("active")
    }
  })

  // Testimonial Slider functionality is now handled by Swiper.js
  // Legacy code removed to prevent conflicts

  // Highlight active nav item
  const currentPage = window.location.pathname.split("/").pop() || "index.html"
  const navLinks = document.querySelectorAll(".nav-menu li a")

  navLinks.forEach((link) => {
    const linkPage = link.getAttribute("href")
    if (linkPage === currentPage) {
      link.parentElement.classList.add("active")
    }
  })
})
