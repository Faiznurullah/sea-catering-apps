document.addEventListener("DOMContentLoaded", () => {
  const resetForm = document.getElementById("reset-password-form")
  const togglePasswordButtons = document.querySelectorAll(".toggle-password")

  // Get URL parameters
  const urlParams = new URLSearchParams(window.location.search)
  const token = urlParams.get("token")
  const email = urlParams.get("email")

  // Set token and email from URL parameters
  if (token) {
    document.getElementById("reset-token").value = token
  }

  if (email) {
    document.getElementById("reset-email").value = email
  }

  // Toggle Password Visibility
  togglePasswordButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const passwordInput = button.previousElementSibling

      if (passwordInput.type === "password") {
        passwordInput.type = "text"
        button.textContent = "🔒"
      } else {
        passwordInput.type = "password"
        button.textContent = "👁️"
      }
    })
  })
  // Form Submission
  if (resetForm) {
    resetForm.addEventListener("submit", (e) => {
      const email = document.getElementById("reset-email").value
      const password = document.getElementById("new-password").value
      const confirmPassword = document.getElementById("confirm-new-password").value
      const token = document.getElementById("reset-token").value

      // Basic validation
      if (!email || !password || !confirmPassword || !token) {
        e.preventDefault()
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please fill in all fields',
            confirmButtonColor: '#dc3545'
          })
        } else {
          alert("Please fill in all fields")
        }
        return
      }

      // Password validation
      const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
      if (!passwordRegex.test(password)) {
        e.preventDefault()
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'error',
            title: 'Invalid Password!',
            text: 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character',
            confirmButtonColor: '#dc3545'
          })
        } else {
          alert("Password must be at least 8 characters and include uppercase, lowercase, number, and special character")
        }
        return
      }

      // Confirm password
      if (password !== confirmPassword) {
        e.preventDefault()
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'error',
            title: 'Password Mismatch!',
            text: 'Passwords do not match',
            confirmButtonColor: '#dc3545'
          })
        } else {
          alert("Passwords do not match")
        }
        return
      }

      // If all validations pass, allow form submission
      // Form will be submitted to Laravel backend
    })
  }
})
