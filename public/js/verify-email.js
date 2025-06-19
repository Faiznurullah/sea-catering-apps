document.addEventListener("DOMContentLoaded", () => {
  const resendBtn = document.getElementById("resend-verification")
  const resentMessage = document.getElementById("resent-message")

  if (resendBtn) {
    resendBtn.addEventListener("click", (e) => {
      e.preventDefault()

      // Disable button to prevent multiple clicks
      resendBtn.disabled = true
      resendBtn.textContent = "Sending..."

      // Simulate sending verification email
      setTimeout(() => {
        // Show success message
        resentMessage.style.display = "block"

        // Re-enable button
        resendBtn.disabled = false
        resendBtn.textContent = "click here to request another"

        // Hide message after 5 seconds
        setTimeout(() => {
          resentMessage.style.display = "none"
        }, 5000)
      }, 2000)
    })
  }

  // Check URL parameters for email
  const urlParams = new URLSearchParams(window.location.search)
  const email = urlParams.get("email")

  if (email) {
    // Update the verification text to include the email
    const verifyText = document.querySelector(".verify-text")
    if (verifyText) {
      verifyText.innerHTML = `Before proceeding, please check your email (<strong>${email}</strong>) for a verification link.`
    }
  }
})
