document.addEventListener("DOMContentLoaded", () => {
  const forgotForm = document.getElementById("forgot-password-form")
  const statusMessage = document.getElementById("status-message")
  const emailError = document.getElementById("email-error")

  if (forgotForm) {
    forgotForm.addEventListener("submit", (e) => {
      e.preventDefault()

      const email = document.getElementById("forgot-email").value

      // Clear previous errors
      emailError.classList.remove("show")
      emailError.textContent = ""

      // Basic validation
      if (!email) {
        emailError.textContent = "Please enter your email address"
        emailError.classList.add("show")
        return
      }

      // Email format validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (!emailRegex.test(email)) {
        emailError.textContent = "Please enter a valid email address"
        emailError.classList.add("show")
        return
      }

      // Disable submit button
      const submitBtn = forgotForm.querySelector('button[type="submit"]')
      submitBtn.disabled = true
      submitBtn.textContent = "Sending..."

      // Simulate sending reset email
      setTimeout(() => {
        // Show success message
        statusMessage.style.display = "block"

        // Hide form
        forgotForm.style.display = "none"

        // In a real application, this would send data to a server
        console.log("Password reset email sent to:", email)
      }, 2000)
    })
  }
})
