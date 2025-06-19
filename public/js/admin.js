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
      const statCards = document.querySelectorAll('.stat-card')
      if (statCards.length >= 4) {
        statCards[0].querySelector('.stat-value').textContent = stats.newSubscriptions.value
        statCards[0].querySelector('.stat-change').textContent = stats.newSubscriptions.change + " from last period"

        statCards[1].querySelector('.stat-value').textContent = stats.mrr.value
        statCards[1].querySelector('.stat-change').textContent = stats.mrr.change + " from last period"

        statCards[2].querySelector('.stat-value').textContent = stats.reactivations.value
        statCards[2].querySelector('.stat-change').textContent = stats.reactivations.change + " from last period"

        statCards[3].querySelector('.stat-value').textContent = stats.activeSubscriptions.value
        statCards[3].querySelector('.stat-change').textContent = stats.activeSubscriptions.change + " from last period"
      }
    }, 1000)
  }
})

// Global functions for subscription management
function approveSubscription(subscriptionId) {
  const modal = document.getElementById('approve-subscription-modal')
  const form = document.getElementById('approve-form')
  
  // Set form action URL
  form.action = `/subscriptions/${subscriptionId}/approve`
  
  // Show modal
  modal.style.display = 'block'
  document.body.style.overflow = 'hidden'

  // Handle form submission
  form.onsubmit = function(e) {
    e.preventDefault()
    
    const formData = new FormData(form)
    const submitButton = form.querySelector('button[type="submit"]')
    const originalText = submitButton.textContent
    
    submitButton.disabled = true
    submitButton.textContent = 'Processing...'
    
    fetch(form.action, {
      method: 'PATCH',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Close modal
        modal.style.display = 'none'
        document.body.style.overflow = 'auto'
        
        // Show success message
        showNotification('Subscription approved successfully!', 'success')
        
        // Update the table row
        updateSubscriptionRow(subscriptionId, data.subscription)
        
        // Reset form
        form.reset()
      } else {
        showNotification(data.message || 'Error approving subscription', 'error')
      }
    })
    .catch(error => {
      console.error('Error:', error)
      showNotification('An error occurred while approving subscription', 'error')
    })
    .finally(() => {
      submitButton.disabled = false
      submitButton.textContent = originalText
    })
  }
}

function rejectSubscription(subscriptionId) {
  const modal = document.getElementById('reject-subscription-modal')
  const form = document.getElementById('reject-form')
  
  // Set form action URL
  form.action = `/subscriptions/${subscriptionId}/reject`
  
  // Show modal
  modal.style.display = 'block'
  document.body.style.overflow = 'hidden'

  // Handle form submission
  form.onsubmit = function(e) {
    e.preventDefault()
    
    const formData = new FormData(form)
    const submitButton = form.querySelector('button[type="submit"]')
    const originalText = submitButton.textContent
    
    submitButton.disabled = true
    submitButton.textContent = 'Processing...'
    
    fetch(form.action, {
      method: 'PATCH',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Close modal
        modal.style.display = 'none'
        document.body.style.overflow = 'auto'
        
        // Show success message
        showNotification('Subscription rejected successfully!', 'success')
        
        // Update the table row
        updateSubscriptionRow(subscriptionId, data.subscription)
        
        // Reset form
        form.reset()
      } else {
        showNotification(data.message || 'Error rejecting subscription', 'error')
      }
    })
    .catch(error => {
      console.error('Error:', error)
      showNotification('An error occurred while rejecting subscription', 'error')
    })
    .finally(() => {
      submitButton.disabled = false
      submitButton.textContent = originalText
    })
  }
}

function pauseSubscription(subscriptionId) {
  if (confirm('Are you sure you want to pause this subscription?')) {
    fetch(`/subscriptions/${subscriptionId}/pause`, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        pause_start_date: new Date().toISOString().split('T')[0],
        pause_end_date: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0], // 30 days from now
        reason: 'Admin initiated pause'
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showNotification('Subscription paused successfully!', 'success')
        location.reload() // Reload to update the table
      } else {
        showNotification(data.message || 'Error pausing subscription', 'error')
      }
    })
    .catch(error => {
      console.error('Error:', error)
      showNotification('An error occurred while pausing subscription', 'error')
    })
  }
}

function resumeSubscription(subscriptionId) {
  if (confirm('Are you sure you want to resume this subscription?')) {
    fetch(`/subscriptions/${subscriptionId}/resume`, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showNotification('Subscription resumed successfully!', 'success')
        location.reload() // Reload to update the table
      } else {
        showNotification(data.message || 'Error resuming subscription', 'error')
      }
    })
    .catch(error => {
      console.error('Error:', error)
      showNotification('An error occurred while resuming subscription', 'error')
    })
  }
}

function viewSubscription(subscriptionId) {
  const modal = document.getElementById('subscription-detail-modal')
  const content = document.getElementById('subscription-detail-content')
  
  // Show loading
  content.innerHTML = '<p>Loading subscription details...</p>'
  modal.style.display = 'block'
  document.body.style.overflow = 'hidden'
  
  // Fetch subscription details
  fetch(`/subscriptions/${subscriptionId}/details`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const subscription = data.subscription
        content.innerHTML = `
          <div class="subscription-details">
            <div class="detail-section">
              <h3>Customer Information</h3>
              <p><strong>Name:</strong> ${subscription.name}</p>
              <p><strong>Phone:</strong> ${subscription.phone}</p>
              <p><strong>Subscription ID:</strong> #${subscription.id}</p>
            </div>
            
            <div class="detail-section">
              <h3>Plan Details</h3>
              <p><strong>Plan:</strong> ${subscription.meal_plan.name}</p>
              <p><strong>Price per Meal:</strong> Rp ${new Intl.NumberFormat('id-ID').format(subscription.meal_plan.price_per_meal)}</p>
              <p><strong>Total Monthly Price:</strong> Rp ${new Intl.NumberFormat('id-ID').format(subscription.total_price)}</p>
            </div>
            
            <div class="detail-section">
              <h3>Meal Types</h3>
              <div class="meal-types">
                ${subscription.subscription_meals.map(meal => 
                  `<span class="meal-tag">${meal.meal_type.charAt(0).toUpperCase() + meal.meal_type.slice(1)}</span>`
                ).join('')}
              </div>
            </div>
            
            <div class="detail-section">
              <h3>Delivery Days</h3>
              <div class="delivery-days">
                ${subscription.delivery_days.map(day => 
                  `<span class="day-tag">${day.day_of_week.charAt(0).toUpperCase() + day.day_of_week.slice(1)}</span>`
                ).join('')}
              </div>
            </div>
            
            <div class="detail-section">
              <h3>Status & Dates</h3>
              <p><strong>Status:</strong> <span class="status ${subscription.status}">${subscription.status.charAt(0).toUpperCase() + subscription.status.slice(1)}</span></p>
              <p><strong>Created:</strong> ${new Date(subscription.created_at).toLocaleDateString('id-ID')}</p>
              ${subscription.approved_at ? `<p><strong>Approved:</strong> ${new Date(subscription.approved_at).toLocaleDateString('id-ID')}</p>` : ''}
              ${subscription.rejected_at ? `<p><strong>Rejected:</strong> ${new Date(subscription.rejected_at).toLocaleDateString('id-ID')}</p>` : ''}
            </div>
            
            ${subscription.allergies ? `
              <div class="detail-section">
                <h3>Allergies & Dietary Restrictions</h3>
                <p class="allergy-text">${subscription.allergies}</p>
              </div>
            ` : ''}
            
            ${subscription.admin_notes ? `
              <div class="detail-section">
                <h3>Admin Notes</h3>
                <p>${subscription.admin_notes}</p>
              </div>
            ` : ''}
            
            ${subscription.rejection_reason ? `
              <div class="detail-section">
                <h3>Rejection Reason</h3>
                <p class="rejection-reason">${subscription.rejection_reason}</p>
              </div>
            ` : ''}
            
            ${subscription.refund_amount > 0 ? `
              <div class="detail-section">
                <h3>Refund Information</h3>
                <p><strong>Credit Amount:</strong> Rp ${new Intl.NumberFormat('id-ID').format(subscription.refund_amount)}</p>
              </div>
            ` : ''}
          </div>
        `
      } else {
        content.innerHTML = '<p>Error loading subscription details.</p>'
      }
    })
    .catch(error => {
      console.error('Error:', error)
      content.innerHTML = '<p>Error loading subscription details.</p>'
    })
}

function viewAllergies(allergies) {
  const modal = document.getElementById('allergies-modal')
  const allergyText = document.getElementById('allergy-text')
  
  allergyText.textContent = allergies
  modal.style.display = 'block'
  document.body.style.overflow = 'hidden'
}

function filterSubscriptions(status) {
  const rows = document.querySelectorAll('.subscription-row')
  
  rows.forEach(row => {
    if (status === 'all' || row.dataset.status === status) {
      row.style.display = ''
    } else {
      row.style.display = 'none'
    }
  })
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId)
  modal.style.display = 'none'
  document.body.style.overflow = 'auto'
}

function updateSubscriptionRow(subscriptionId, subscription) {
  const row = document.querySelector(`tr[data-subscription-id="${subscriptionId}"]`)
  if (row) {
    // Update status cell
    const statusCell = row.querySelector('.status')
    if (statusCell) {
      statusCell.className = `status ${subscription.status}`
      statusCell.textContent = subscription.status.charAt(0).toUpperCase() + subscription.status.slice(1)
    }
    
    // Update actions cell
    const actionsCell = row.querySelector('.table-actions')
    if (actionsCell) {
      let actionsHTML = ''
      
      if (subscription.status === 'pending') {
        actionsHTML += `
          <button class="action-btn approve-btn" onclick="approveSubscription(${subscription.id})" title="Approve">✅</button>
          <button class="action-btn reject-btn" onclick="rejectSubscription(${subscription.id})" title="Reject">❌</button>
        `
      }
      
      if (subscription.status === 'active') {
        actionsHTML += `<button class="action-btn pause-btn" onclick="pauseSubscription(${subscription.id})" title="Pause">⏸️</button>`
      }
      
      if (subscription.status === 'paused') {
        actionsHTML += `<button class="action-btn resume-btn" onclick="resumeSubscription(${subscription.id})" title="Resume">▶️</button>`
      }
      
      actionsHTML += `<button class="action-btn view-btn" onclick="viewSubscription(${subscription.id})" title="View Details">👁️</button>`
      
      if (subscription.allergies) {
        actionsHTML += `<button class="action-btn allergy-btn" onclick="viewAllergies('${subscription.allergies.replace(/'/g, "\\'")}')" title="View Allergies">🚨</button>`
      }
      
      actionsCell.innerHTML = actionsHTML
    }
    
    // Update row data attribute
    row.dataset.status = subscription.status
  }
}

function showNotification(message, type = 'info') {
  // Create notification element
  const notification = document.createElement('div')
  notification.className = `notification ${type}`
  notification.innerHTML = `
    <span>${message}</span>
    <button onclick="this.parentElement.remove()">×</button>
  `
  
  // Style the notification
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 5px;
    color: white;
    font-weight: 500;
    z-index: 10000;
    max-width: 400px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideIn 0.3s ease-out;
  `
  
  // Set background color based on type
  switch(type) {
    case 'success':
      notification.style.backgroundColor = '#28a745'
      break
    case 'error':
      notification.style.backgroundColor = '#dc3545'
      break
    case 'warning':
      notification.style.backgroundColor = '#ffc107'
      notification.style.color = '#000'
      break
    default:
      notification.style.backgroundColor = '#007bff'
  }
  
  // Add CSS animation if not exists
  if (!document.querySelector('#notification-styles')) {
    const style = document.createElement('style')
    style.id = 'notification-styles'
    style.textContent = `
      @keyframes slideIn {
        from {
          transform: translateX(100%);
          opacity: 0;
        }
        to {
          transform: translateX(0);
          opacity: 1;
        }
      }
    `
    document.head.appendChild(style)
  }
  
  // Add to DOM
  document.body.appendChild(notification)
  
  // Auto-remove after 5 seconds
  setTimeout(() => {
    if (notification.parentElement) {
      notification.remove()
    }
  }, 5000)
}
