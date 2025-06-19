document.addEventListener("DOMContentLoaded", () => {
  // Auth Tabs
  const authTabs = document.querySelectorAll(".auth-tab")
  const authFormContainers = document.querySelectorAll(".auth-form-container")

  authTabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      // Remove active class from all tabs and form containers
      authTabs.forEach((t) => t.classList.remove("active"))
      authFormContainers.forEach((c) => c.classList.remove("active"))

      // Add active class to clicked tab and corresponding form container
      tab.classList.add("active")
      const tabId = tab.getAttribute("data-tab")
      document.getElementById(`${tabId}-form-container`).classList.add("active")
    })
  })

  // Toggle Password Visibility
  const togglePasswordButtons = document.querySelectorAll(".toggle-password")

  togglePasswordButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const passwordInput = button.previousElementSibling

      // Toggle password visibility
      if (passwordInput.type === "password") {
        passwordInput.type = "text"
        button.textContent = "🔒"
      } else {
        passwordInput.type = "password"
        button.textContent = "👁️"
      }
    })
  })

  // Login Form Validation and Submission
  const loginForm = document.getElementById("login-form")

  if (loginForm) {
    loginForm.addEventListener("submit", (event) => {
      event.preventDefault()

      const email = document.getElementById("login-email").value
      const password = document.getElementById("login-password").value

      // Basic validation
      if (!email || !password) {
        alert("Please fill in all fields")
        return
      }

      // In a real application, this would send data to a server for authentication
      // For now, we'll just simulate a successful login
      alert("Login successful! Redirecting to dashboard...")

      // Redirect to dashboard (in a real app)
      // window.location.href = 'dashboard.html';
    })
  }

  // Registration Form Validation and Submission
  const registerForm = document.getElementById("register-form")

  if (registerForm) {
    registerForm.addEventListener("submit", (event) => {
      event.preventDefault()

      const name = document.getElementById("register-name").value
      const email = document.getElementById("register-email").value
      const password = document.getElementById("register-password").value
      const confirmPassword = document.getElementById("register-confirm-password").value
      const termsChecked = document.getElementById("terms").checked

      // Basic validation
      if (!name || !email || !password || !confirmPassword) {
        alert("Please fill in all fields")
        return
      }

      // Password validation
      const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
      if (!passwordRegex.test(password)) {
        alert("Password must be at least 8 characters and include uppercase, lowercase, number, and special character")
        return
      }

      // Confirm password
      if (password !== confirmPassword) {
        alert("Passwords do not match")
        return
      }

      // Terms agreement
      if (!termsChecked) {
        alert("Please agree to the Terms of Service and Privacy Policy")
        return
      }

      // In a real application, this would send data to a server for registration
      // For now, we'll just simulate a successful registration
      alert("Registration successful! You can now log in with your credentials.")

      // Reset form and switch to login tab
      registerForm.reset()
      document.querySelector('.auth-tab[data-tab="login"]').click()
    })
  }

  // Password strength indicator (could be implemented for better UX)
  const registerPassword = document.getElementById("register-password")

  if (registerPassword) {
    registerPassword.addEventListener("input", () => {
      // This is where you could add a password strength indicator
      // For simplicity, we're not implementing it in this example
    })
  }
})
