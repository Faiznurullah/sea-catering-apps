document.addEventListener("DOMContentLoaded", () => {
  // User Dropdown Menu
  const userMenuTrigger = document.querySelector(".user-menu-trigger")
  const userDropdown = document.querySelector(".user-dropdown")

  if (userMenuTrigger && userDropdown) {
    userMenuTrigger.addEventListener("click", (e) => {
      e.preventDefault()
      userDropdown.classList.toggle("show")
    })

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
      if (!e.target.closest(".user-menu")) {
        userDropdown.classList.remove("show")
      }
    })
  }

  // Logout Button
  const logoutBtn = document.getElementById("logout-btn")
  if (logoutBtn) {
    logoutBtn.addEventListener("click", (e) => {
      e.preventDefault()
      alert("You have been logged out successfully.")
      window.location.href = "index.html"
    })
  }

  // Avatar Upload
  const changeAvatarBtn = document.getElementById("change-avatar-btn")
  const avatarUpload = document.getElementById("avatar-upload")
  const profileAvatar = document.querySelector(".profile-avatar")

  if (changeAvatarBtn && avatarUpload) {
    changeAvatarBtn.addEventListener("click", () => {
      avatarUpload.click()
    })

    avatarUpload.addEventListener("change", (e) => {
      const file = e.target.files[0]
      if (file) {
        const reader = new FileReader()
        reader.onload = (e) => {
          // In a real application, you would upload the image to a server
          // For now, we'll just show a preview
          profileAvatar.style.backgroundImage = `url(${e.target.result})`
          profileAvatar.style.backgroundSize = "cover"
          profileAvatar.style.backgroundPosition = "center"
          profileAvatar.textContent = ""
          alert("Avatar updated successfully!")
        }
        reader.readAsDataURL(file)
      }
    })
  }

  // Edit Account Information
  const editAccountBtn = document.getElementById("edit-account-btn")
  const accountForm = document.getElementById("account-form")
  const accountActions = document.getElementById("account-actions")
  const cancelAccountBtn = document.getElementById("cancel-account-btn")

  if (editAccountBtn && accountForm) {
    editAccountBtn.addEventListener("click", () => {
      // Enable form fields
      const inputs = accountForm.querySelectorAll("input, textarea")
      inputs.forEach((input) => {
        if (input.id !== "email") {
          // Keep email readonly
          input.removeAttribute("readonly")
          input.style.backgroundColor = "var(--white)"
          input.style.color = "var(--text-color)"
        }
      })

      // Show form actions
      accountActions.style.display = "flex"
      editAccountBtn.style.display = "none"
    })

    cancelAccountBtn.addEventListener("click", () => {
      // Reset form and disable fields
      accountForm.reset()
      const inputs = accountForm.querySelectorAll("input, textarea")
      inputs.forEach((input) => {
        input.setAttribute("readonly", "true")
        input.style.backgroundColor = "#f8f9fa"
        input.style.color = "var(--light-text)"
      })

      // Hide form actions
      accountActions.style.display = "none"
      editAccountBtn.style.display = "inline-block"

      // Reset to original values
      document.getElementById("first-name").value = "John"
      document.getElementById("last-name").value = "Doe"
      document.getElementById("email").value = "john.doe@example.com"
      document.getElementById("phone").value = "08123456789"
      document.getElementById("address").value = "Jl. Sudirman No. 123, Jakarta Pusat, 10220"
      document.getElementById("birth-date").value = "1990-01-15"
    })

    accountForm.addEventListener("submit", (e) => {
      e.preventDefault()

      // In a real application, this would send data to a server
      alert("Account information updated successfully!")

      // Disable form fields
      const inputs = accountForm.querySelectorAll("input, textarea")
      inputs.forEach((input) => {
        input.setAttribute("readonly", "true")
        input.style.backgroundColor = "#f8f9fa"
        input.style.color = "var(--light-text)"
      })

      // Hide form actions
      accountActions.style.display = "none"
      editAccountBtn.style.display = "inline-block"

      // Update profile header if name changed
      const firstName = document.getElementById("first-name").value
      const lastName = document.getElementById("last-name").value
      const fullName = `${firstName} ${lastName}`
      document.querySelector(".profile-info h1").textContent = fullName
      document.querySelector(".user-menu-trigger").textContent = `${fullName} ▼`
    })
  }

  // Preferences Form
  const preferencesForm = document.getElementById("preferences-form")
  if (preferencesForm) {
    preferencesForm.addEventListener("submit", (e) => {
      e.preventDefault()
      alert("Preferences saved successfully!")
    })
  }

  // Change Password Modal
  const changePasswordBtn = document.getElementById("change-password-btn")
  const changePasswordModal = document.getElementById("change-password-modal")
  const changePasswordForm = document.getElementById("change-password-form")
  const cancelPasswordChange = document.getElementById("cancel-password-change")

  if (changePasswordBtn) {
    changePasswordBtn.addEventListener("click", () => {
      changePasswordModal.style.display = "block"
      document.body.style.overflow = "hidden"
    })
  }

  if (cancelPasswordChange) {
    cancelPasswordChange.addEventListener("click", () => {
      changePasswordModal.style.display = "none"
      document.body.style.overflow = "auto"
      changePasswordForm.reset()
    })
  }

  if (changePasswordForm) {
    changePasswordForm.addEventListener("submit", (e) => {
      e.preventDefault()

      const currentPassword = document.getElementById("current-password").value
      const newPassword = document.getElementById("new-password").value
      const confirmPassword = document.getElementById("confirm-new-password").value

      // Basic validation
      if (!currentPassword || !newPassword || !confirmPassword) {
        alert("Please fill in all fields")
        return
      }

      // Password validation
      const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
      if (!passwordRegex.test(newPassword)) {
        alert("Password must be at least 8 characters and include uppercase, lowercase, number, and special character")
        return
      }

      // Confirm password
      if (newPassword !== confirmPassword) {
        alert("New passwords do not match")
        return
      }

      // In a real application, this would send data to a server
      alert("Password changed successfully!")
      changePasswordModal.style.display = "none"
      document.body.style.overflow = "auto"
      changePasswordForm.reset()
    })
  }

  // Toggle Password Visibility
  const togglePasswordButtons = document.querySelectorAll(".toggle-password")
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

  // Verification Modal
  const verificationBadge = document.getElementById("verification-badge")
  const verificationModal = document.getElementById("verification-modal")
  const resendVerificationBtn = document.getElementById("resend-verification")
  const closeVerificationBtn = document.getElementById("close-verification")

  // Simulate unverified account for demo
  if (Math.random() > 0.7) {
    // 30% chance of unverified account
    verificationBadge.textContent = "⚠️ Unverified Account"
    verificationBadge.className = "status-badge unverified"
    verificationBadge.style.cursor = "pointer"

    verificationBadge.addEventListener("click", () => {
      verificationModal.style.display = "block"
      document.body.style.overflow = "hidden"
    })
  }

  if (resendVerificationBtn) {
    resendVerificationBtn.addEventListener("click", () => {
      resendVerificationBtn.disabled = true
      resendVerificationBtn.textContent = "Sending..."

      setTimeout(() => {
        alert("Verification email sent!")
        resendVerificationBtn.disabled = false
        resendVerificationBtn.textContent = "Resend Email"
      }, 2000)
    })
  }

  if (closeVerificationBtn) {
    closeVerificationBtn.addEventListener("click", () => {
      verificationModal.style.display = "none"
      document.body.style.overflow = "auto"
    })
  }

  // 2FA Enable Button
  const enable2faBtn = document.getElementById("enable-2fa-btn")
  if (enable2faBtn) {
    enable2faBtn.addEventListener("click", () => {
      alert("Two-Factor Authentication setup will be implemented in a future update.")
    })
  }

  // Manage Sessions Button
  const manageSessionsBtn = document.getElementById("manage-sessions-btn")
  if (manageSessionsBtn) {
    manageSessionsBtn.addEventListener("click", () => {
      alert("Session management will be implemented in a future update.")
    })
  }

  // Deactivate Account Button
  const deactivateAccountBtn = document.getElementById("deactivate-account-btn")
  if (deactivateAccountBtn) {
    deactivateAccountBtn.addEventListener("click", () => {
      if (confirm("Are you sure you want to deactivate your account? You can reactivate it anytime.")) {
        alert("Account deactivated successfully. You can reactivate it by logging in again.")
        window.location.href = "index.html"
      }
    })
  }

  // Delete Account Modal
  const deleteAccountBtn = document.getElementById("delete-account-btn")
  const deleteAccountModal = document.getElementById("delete-account-modal")
  const deleteAccountForm = document.getElementById("delete-account-form")
  const cancelDeleteBtn = document.getElementById("cancel-delete")

  if (deleteAccountBtn) {
    deleteAccountBtn.addEventListener("click", () => {
      deleteAccountModal.style.display = "block"
      document.body.style.overflow = "hidden"
    })
  }

  if (cancelDeleteBtn) {
    cancelDeleteBtn.addEventListener("click", () => {
      deleteAccountModal.style.display = "none"
      document.body.style.overflow = "auto"
      deleteAccountForm.reset()
    })
  }

  if (deleteAccountForm) {
    deleteAccountForm.addEventListener("submit", (e) => {
      e.preventDefault()

      const confirmation = document.getElementById("delete-confirmation").value
      const password = document.getElementById("delete-password").value

      if (confirmation !== "DELETE") {
        alert('Please type "DELETE" to confirm account deletion')
        return
      }

      if (!password) {
        alert("Please enter your password")
        return
      }

      // In a real application, this would verify the password and delete the account
      alert("Account deleted successfully. We're sorry to see you go!")
      window.location.href = "index.html"
    })
  }

  // Close modals when clicking the X button
  const closeModalBtns = document.querySelectorAll(".close-modal")
  closeModalBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const modal = this.closest(".modal")
      modal.style.display = "none"
      document.body.style.overflow = "auto"

      // Reset forms when closing modals
      const form = modal.querySelector("form")
      if (form) {
        form.reset()
      }
    })
  })

  // Close modals when clicking outside the modal content
  window.addEventListener("click", (e) => {
    if (e.target.classList.contains("modal")) {
      e.target.style.display = "none"
      document.body.style.overflow = "auto"

      // Reset forms when closing modals
      const form = e.target.querySelector("form")
      if (form) {
        form.reset()
      }
    }
  })
})
