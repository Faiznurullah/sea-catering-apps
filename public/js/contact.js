document.addEventListener("DOMContentLoaded", () => {
  // Contact form submission
  const contactForm = document.getElementById("contact-form")
  const contactSuccessModal = document.getElementById("contact-success-modal")
  const closeContactModal = document.getElementById("close-contact-modal")
  const closeModalX = document.querySelector(".close-modal")

  if (contactForm) {
    contactForm.addEventListener("submit", (event) => {
      event.preventDefault()

      // In a real application, this would send data to a server
      // For now, we'll just show the success modal
      contactSuccessModal.style.display = "block"
      document.body.style.overflow = "hidden"

      // Reset the form
      contactForm.reset()
    })
  }

  // Close modal when clicking the close button
  if (closeContactModal) {
    closeContactModal.addEventListener("click", () => {
      contactSuccessModal.style.display = "none"
      document.body.style.overflow = "auto"
    })
  }

  if (closeModalX) {
    closeModalX.addEventListener("click", () => {
      contactSuccessModal.style.display = "none"
      document.body.style.overflow = "auto"
    })
  }

  // Close modal when clicking outside the modal
  window.addEventListener("click", (event) => {
    if (event.target === contactSuccessModal) {
      contactSuccessModal.style.display = "none"
      document.body.style.overflow = "auto"
    }
  })
})
