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

      // In a real application, this would send a request to the server to log out
      // For now, we'll just redirect to the home page
      alert("You have been logged out successfully.")
      window.location.href = "index.html"
    })
  }

  // Sidebar Menu Navigation
  const sidebarLinks = document.querySelectorAll(".sidebar-menu a")

  sidebarLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault()

      // Remove active class from all links
      sidebarLinks.forEach((l) => l.parentElement.classList.remove("active"))

      // Add active class to clicked link
      this.parentElement.classList.add("active")

      // Get the target section ID
      const targetId = this.getAttribute("href").substring(1)
      const targetSection = document.getElementById(targetId)

      // Scroll to the target section
      if (targetSection) {
        window.scrollTo({
          top: targetSection.offsetTop - 100,
          behavior: "smooth",
        })
      }
    })
  })

  // Pause Subscription Modal
  const pauseBtns = document.querySelectorAll(".pause-btn")
  const pauseModal = document.getElementById("pause-subscription-modal")
  const cancelPauseBtn = document.getElementById("cancel-pause")
  const pauseForm = document.getElementById("pause-form")
  const closeModalBtns = document.querySelectorAll(".close-modal")

  pauseBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      pauseModal.style.display = "block"
      document.body.style.overflow = "hidden"
    })
  })

  if (cancelPauseBtn) {
    cancelPauseBtn.addEventListener("click", () => {
      pauseModal.style.display = "none"
      document.body.style.overflow = "auto"
    })
  }

  if (pauseForm) {
    pauseForm.addEventListener("submit", (e) => {
      e.preventDefault()

      const startDate = document.getElementById("pause-start").value
      const endDate = document.getElementById("pause-end").value

      // Basic validation
      if (!startDate || !endDate) {
        alert("Please select both start and end dates.")
        return
      }

      if (new Date(startDate) > new Date(endDate)) {
        alert("End date must be after start date.")
        return
      }

      // In a real application, this would send data to a server
      // For now, we'll just show an alert and close the modal
      alert(`Subscription paused from ${startDate} to ${endDate}.`)
      pauseModal.style.display = "none"
      document.body.style.overflow = "auto"

      // Update the subscription status (in a real app, this would come from the server)
      const subscriptionItem = pauseBtns[0].closest(".subscription-item")
      const statusSpan = subscriptionItem.querySelector(".status")
      statusSpan.textContent = `Paused until ${formatDate(endDate)}`
      statusSpan.className = "status paused"

      // Change the button from Pause to Resume
      pauseBtns[0].textContent = "Resume Subscription"
      pauseBtns[0].classList.remove("pause-btn")
      pauseBtns[0].classList.add("resume-btn")
    })
  }

  // Cancel Subscription Modal
  const cancelBtns = document.querySelectorAll(".cancel-btn")
  const cancelModal = document.getElementById("cancel-subscription-modal")
  const cancelCancellationBtn = document.getElementById("cancel-cancellation")
  const cancelForm = document.getElementById("cancel-form")
  const cancelReasonSelect = document.getElementById("cancel-reason")
  const otherReasonGroup = document.getElementById("other-reason-group")

  cancelBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      cancelModal.style.display = "block"
      document.body.style.overflow = "hidden"
    })
  })

  if (cancelCancellationBtn) {
    cancelCancellationBtn.addEventListener("click", () => {
      cancelModal.style.display = "none"
      document.body.style.overflow = "auto"
    })
  }

  if (cancelReasonSelect) {
    cancelReasonSelect.addEventListener("change", function () {
      if (this.value === "other") {
        otherReasonGroup.style.display = "block"
      } else {
        otherReasonGroup.style.display = "none"
      }
    })
  }

  if (cancelForm) {
    cancelForm.addEventListener("submit", (e) => {
      e.preventDefault()

      // In a real application, this would send data to a server
      // For now, we'll just show an alert and close the modal
      alert("Subscription cancelled successfully.")
      cancelModal.style.display = "none"
      document.body.style.overflow = "auto"

      // Remove the subscription item from the UI (in a real app, this would be handled differently)
      const subscriptionItem = cancelBtns[0].closest(".subscription-item")
      subscriptionItem.remove()
    })
  }

  // Close modals when clicking the X button
  closeModalBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const modal = this.closest(".modal")
      modal.style.display = "none"
      document.body.style.overflow = "auto"
    })
  })

  // Close modals when clicking outside the modal content
  window.addEventListener("click", (e) => {
    if (e.target.classList.contains("modal")) {
      e.target.style.display = "none"
      document.body.style.overflow = "auto"
    }
  })

  // Resume Subscription Button
  const resumeBtns = document.querySelectorAll(".resume-btn")

  resumeBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      // In a real application, this would send data to a server
      // For now, we'll just show an alert and update the UI
      alert("Subscription resumed successfully.")

      // Update the subscription status (in a real app, this would come from the server)
      const subscriptionItem = this.closest(".subscription-item")
      const statusSpan = subscriptionItem.querySelector(".status")
      statusSpan.textContent = "Active"
      statusSpan.className = "status active"

      // Change the button from Resume to Pause
      this.textContent = "Pause Subscription"
      this.classList.remove("resume-btn")
      this.classList.add("pause-btn")
    })
  })

  // Helper function to format date
  function formatDate(dateString) {
    const date = new Date(dateString)
    const day = date.getDate()
    const month = date.toLocaleString("default", { month: "short" })
    const year = date.getFullYear()
    return `${day} ${month} ${year}`
  }
})
