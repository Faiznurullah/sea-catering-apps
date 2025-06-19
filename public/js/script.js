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

  // Testimonial Slider
  const testimonials = document.querySelectorAll(".testimonial-card")
  const dots = document.querySelectorAll(".dot")
  let currentTestimonial = 0

  function showTestimonial(index) {
    testimonials.forEach((testimonial) => testimonial.classList.remove("active"))
    dots.forEach((dot) => dot.classList.remove("active"))

    testimonials[index].classList.add("active")
    dots[index].classList.add("active")
    currentTestimonial = index
  }

  // Initialize dots click events
  if (dots.length > 0) {
    dots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        showTestimonial(index)
      })
    })
  }

  // Auto-rotate testimonials
  function rotateTestimonials() {
    currentTestimonial = (currentTestimonial + 1) % testimonials.length
    showTestimonial(currentTestimonial)
  }

  if (testimonials.length > 0) {
    // Set initial testimonial
    showTestimonial(0)

    // Auto-rotate every 5 seconds
    setInterval(rotateTestimonials, 5000)
  }

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
