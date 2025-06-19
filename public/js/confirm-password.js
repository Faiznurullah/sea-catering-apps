document.addEventListener("DOMContentLoaded", () => {
  const confirmForm = document.getElementById("confirm-password-form")
  const togglePasswordButton = document.querySelector(".toggle-password")
  const passwordError = document.getElementById("password-error")

  // Toggle Password Visibility
  if (togglePasswordButton) {
    togglePasswordButton.addEventListener("click", () => {
      const passwordInput = togglePasswordButton.previousElementSibling

      if (passwordInput.type === "password") {
        passwordInput.type = "text"
        togglePasswordButton.textContent = "🔒"
      } else {
        passwordInput.type = "password"
        togglePasswordButton.textContent = "👁️"
      }
    })
  }

  // Form Submission
  if (confirmForm) {
    confirmForm.addEventListener("submit", (e) => {
      e.preventDefault()

      const password = document.getElementById("current-password").value

      // Clear previous errors
      passwordError.classList.remove("show")
      passwordError.textContent = ""

      // Basic validation
      if (!password) {
        passwordError.textContent = "Please enter your password"
        passwordError.classList.add("show")
        return
      }

      // Disable submit button
      const submitBtn = confirmForm.querySelector('button[type="submit"]')
      submitBtn.disabled = true
      submitBtn.textContent = "Confirming..."

      // Simulate password confirmation
      setTimeout(() => {
        // In a real application, this would verify the password with the server
        // For demo purposes, we'll assume it's correct
        alert("Password confirmed successfully!")

        // Redirect to intended page or dashboard
        const redirectUrl = new URLSearchParams(window.location.search).get("redirect") || "dashboard.html"
        window.location.href = redirectUrl
      }, 1500)
    })
  }
})
