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

  // Date Range Selector
  const dateRangeSelect = document.getElementById("date-range")
  const dateRangeModal = document.getElementById("date-range-modal")
  const cancelDateRangeBtn = document.getElementById("cancel-date-range")
  const dateRangeForm = document.getElementById("date-range-form")
  const closeModalBtns = document.querySelectorAll(".close-modal")

  if (dateRangeSelect) {
    dateRangeSelect.addEventListener("change", function () {
      if (this.value === "custom") {
        dateRangeModal.style.display = "block"
        document.body.style.overflow = "hidden"
      } else {
        // In a real application, this would update the dashboard data based on the selected date range
        updateDashboardData(this.value)
      }
    })
  }

  if (cancelDateRangeBtn) {
    cancelDateRangeBtn.addEventListener("click", () => {
      dateRangeModal.style.display = "none"
      document.body.style.overflow = "auto"
      dateRangeSelect.value = "30" // Reset to default
    })
  }

  if (dateRangeForm) {
    dateRangeForm.addEventListener("submit", (e) => {
      e.preventDefault()

      const startDate = document.getElementById("start-date").value
      const endDate = document.getElementById("end-date").value

      // Basic validation
      if (!startDate || !endDate) {
        alert("Please select both start and end dates.")
        return
      }

      if (new Date(startDate) > new Date(endDate)) {
        alert("End date must be after start date.")
        return
      }

      // In a real application, this would update the dashboard data based on the custom date range
      updateDashboardData("custom", startDate, endDate)

      dateRangeModal.style.display = "none"
      document.body.style.overflow = "auto"
    })
  }

  // Close modals when clicking the X button
  closeModalBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const modal = this.closest(".modal")
      modal.style.display = "none"
      document.body.style.overflow = "auto"

      if (modal.id === "date-range-modal") {
        dateRangeSelect.value = "30" // Reset to default
      }
    })
  })

  // Close modals when clicking outside the modal content
  window.addEventListener("click", (e) => {
    if (e.target.classList.contains("modal")) {
      e.target.style.display = "none"
      document.body.style.overflow = "auto"

      if (e.target.id === "date-range-modal") {
        dateRangeSelect.value = "30" // Reset to default
      }
    }
  })

  // Table Action Buttons
  const viewBtns = document.querySelectorAll(".view-btn")
  const editBtns = document.querySelectorAll(".edit-btn")

  viewBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const row = this.closest("tr")
      const customer = row.cells[0].textContent

      // In a real application, this would open a modal with detailed information
      alert(`Viewing details for ${customer}`)
    })
  })

  editBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const row = this.closest("tr")
      const customer = row.cells[0].textContent

      // In a real application, this would open a modal for editing
      alert(`Editing subscription for ${customer}`)
    })
  })

  // Function to update dashboard data based on date range
  function updateDashboardData(range, startDate, endDate) {
    // In a real application, this would make an API call to get data for the selected date range
    // For now, we'll just show an alert
    if (range === "custom") {
      alert(`Updating dashboard data for custom range: ${startDate} to ${endDate}`)
    } else {
      alert(`Updating dashboard data for last ${range} days`)
    }

    // Simulate loading state
    document.querySelectorAll(".stat-value, .stat-change").forEach((el) => {
      el.textContent = "Loading..."
    })

    // Simulate data update after a short delay
    setTimeout(() => {
      // Update stats based on selected range
      // In a real application, these values would come from the server
      let stats

      switch (range) {
        case "7":
          stats = {
            newSubscriptions: { value: "42", change: "+5%" },
            mrr: { value: "Rp 32,150,000", change: "+3%" },
            reactivations: { value: "8", change: "+10%" },
            activeSubscriptions: { value: "1,245", change: "+2%" },
          }
          break
        case "90":
          stats = {
            newSubscriptions: { value: "356", change: "+18%" },
            mrr: { value: "Rp 87,450,000", change: "+15%" },
            reactivations: { value: "68", change: "+25%" },
            activeSubscriptions: { value: "1,245", change: "+12%" },
          }
          break
        case "custom":
          stats = {
            newSubscriptions: { value: "215", change: "+10%" },
            mrr: { value: "Rp 65,320,000", change: "+8%" },
            reactivations: { value: "42", change: "+15%" },
            activeSubscriptions: { value: "1,245", change: "+7%" },
          }
          break
        default: // 30 days
          stats = {
            newSubscriptions: { value: "128", change: "+12%" },
            mrr: { value: "Rp 87,450,000", change: "+8%" },
            reactivations: { value: "24", change: "+15%" },
            activeSubscriptions: { value: "1,245", change: "+5%" },
          }
      }

      // Update the DOM with new values
      document.querySelector(".stat-card:nth-child(1) .stat-value").textContent = stats.newSubscriptions.value
      document.querySelector(".stat-card:nth-child(1) .stat-change").textContent =
        stats.newSubscriptions.change + " from last period"

      document.querySelector(".stat-card:nth-child(2) .stat-value").textContent = stats.mrr.value
      document.querySelector(".stat-card:nth-child(2) .stat-change").textContent =
        stats.mrr.change + " from last period"

      document.querySelector(".stat-card:nth-child(3) .stat-value").textContent = stats.reactivations.value
      document.querySelector(".stat-card:nth-child(3) .stat-change").textContent =
        stats.reactivations.change + " from last period"

      document.querySelector(".stat-card:nth-child(4) .stat-value").textContent = stats.activeSubscriptions.value
      document.querySelector(".stat-card:nth-child(4) .stat-change").textContent =
        stats.activeSubscriptions.change + " from last period"
    }, 1000)
  }
})
