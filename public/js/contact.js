document.addEventListener("DOMContentLoaded", () => {
  // Contact form submission - remove preventDefault to allow normal form submission
  const contactForm = document.getElementById("contact-form")
  const contactSuccessModal = document.getElementById("contact-success-modal")
  const closeContactModal = document.getElementById("close-contact-modal")
  const closeModalX = document.querySelector(".close-modal")

  // Remove the form event listener that prevents default submission
  // The form will now submit normally to Laravel backend

  // Keep modal functionality for success messages if needed
  if (closeContactModal) {
    closeContactModal.addEventListener("click", () => {
      if (contactSuccessModal) {
        contactSuccessModal.style.display = "none"
        document.body.style.overflow = "auto"
      }
    })
  }

  if (closeModalX) {
    closeModalX.addEventListener("click", () => {
      if (contactSuccessModal) {
        contactSuccessModal.style.display = "none"
        document.body.style.overflow = "auto"
      }
    })
  }

  // Close modal when clicking outside the modal
  window.addEventListener("click", (event) => {
    if (contactSuccessModal && event.target === contactSuccessModal) {
      contactSuccessModal.style.display = "none"
      document.body.style.overflow = "auto"
    }
  })
})
