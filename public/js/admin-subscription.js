document.addEventListener("DOMContentLoaded", () => {
  // Get CSRF token
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Show notification function
  function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
      <span>${message}</span>
      <button class="close-notification">&times;</button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
      notification.classList.add('show');
    }, 100);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
      notification.classList.remove('show');
      setTimeout(() => {
        if (notification.parentNode) {
          document.body.removeChild(notification);
        }
      }, 300);
    }, 5000);
    
    // Close button handler
    notification.querySelector('.close-notification').addEventListener('click', () => {
      notification.classList.remove('show');
      setTimeout(() => {
        if (notification.parentNode) {
          document.body.removeChild(notification);
        }
      }, 300);
    });
  }

  // Make AJAX request helper
  function makeRequest(url, method, data = null) {
    const options = {
      method: method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    };

    if (data) {
      options.body = JSON.stringify(data);
    }

    return fetch(url, options);
  }

  // Approve subscription function
  window.approveSubscription = function(subscriptionId) {
    const modal = document.getElementById('approve-subscription-modal');
    const form = document.getElementById('approve-form');
    
    // Set form action
    form.action = `/subscriptions/${subscriptionId}/approve`;
    
    // Show modal
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Handle form submission
    form.onsubmit = function(e) {
      e.preventDefault();
      
      const adminNotes = document.getElementById('approve-notes').value;
      
      makeRequest(`/subscriptions/${subscriptionId}/approve`, 'PATCH', {
        admin_notes: adminNotes
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification(data.message, 'success');
          
          // Update the row in table
          const row = document.querySelector(`tr[data-subscription-id="${subscriptionId}"]`);
          if (row) {
            // Update status
            const statusCell = row.querySelector('.status');
            statusCell.className = 'status active';
            statusCell.textContent = 'Active';
            
            // Update actions
            const actionsCell = row.querySelector('.table-actions');
            actionsCell.innerHTML = `
              <button class="action-btn pause-btn" 
                      onclick="pauseSubscription(${subscriptionId})" 
                      title="Pause">⏸️</button>
              <button class="action-btn view-btn" 
                      onclick="viewSubscription(${subscriptionId})" 
                      title="View Details">👁️</button>
            `;
          }
          
          // Close modal
          closeModal('approve-subscription-modal');
        } else {
          showNotification(data.message || 'Error approving subscription', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Error approving subscription', 'error');
      });
    };
  };

  // Reject subscription function
  window.rejectSubscription = function(subscriptionId) {
    const modal = document.getElementById('reject-subscription-modal');
    const form = document.getElementById('reject-form');
    
    // Set form action
    form.action = `/subscriptions/${subscriptionId}/reject`;
    
    // Show modal
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Handle form submission
    form.onsubmit = function(e) {
      e.preventDefault();
      
      const rejectionReason = document.getElementById('reject-reason').value;
      
      if (!rejectionReason.trim()) {
        showNotification('Please provide a reason for rejection', 'error');
        return;
      }
      
      makeRequest(`/subscriptions/${subscriptionId}/reject`, 'PATCH', {
        rejection_reason: rejectionReason
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification(data.message, 'success');
          
          // Update the row in table
          const row = document.querySelector(`tr[data-subscription-id="${subscriptionId}"]`);
          if (row) {
            // Update status
            const statusCell = row.querySelector('.status');
            statusCell.className = 'status rejected';
            statusCell.textContent = 'Rejected';
            
            // Update actions - only view button
            const actionsCell = row.querySelector('.table-actions');
            actionsCell.innerHTML = `
              <button class="action-btn view-btn" 
                      onclick="viewSubscription(${subscriptionId})" 
                      title="View Details">👁️</button>
            `;
          }
          
          // Close modal
          closeModal('reject-subscription-modal');
        } else {
          showNotification(data.message || 'Error rejecting subscription', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Error rejecting subscription', 'error');
      });
    };
  };

  // Pause subscription function
  window.pauseSubscription = function(subscriptionId) {
    if (!confirm('Are you sure you want to pause this subscription?')) {
      return;
    }
    
    makeRequest(`/subscriptions/${subscriptionId}/pause`, 'PATCH')
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showNotification(data.message, 'success');
        
        // Update the row in table
        const row = document.querySelector(`tr[data-subscription-id="${subscriptionId}"]`);
        if (row) {
          // Update status
          const statusCell = row.querySelector('.status');
          statusCell.className = 'status paused';
          statusCell.textContent = 'Paused';
          
          // Update actions
          const actionsCell = row.querySelector('.table-actions');
          actionsCell.innerHTML = `
            <button class="action-btn resume-btn" 
                    onclick="resumeSubscription(${subscriptionId})" 
                    title="Resume">▶️</button>
            <button class="action-btn view-btn" 
                    onclick="viewSubscription(${subscriptionId})" 
                    title="View Details">👁️</button>
          `;
        }
      } else {
        showNotification(data.message || 'Error pausing subscription', 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showNotification('Error pausing subscription', 'error');
    });
  };

  // Resume subscription function
  window.resumeSubscription = function(subscriptionId) {
    if (!confirm('Are you sure you want to resume this subscription?')) {
      return;
    }
    
    makeRequest(`/subscriptions/${subscriptionId}/resume`, 'PATCH')
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showNotification(data.message, 'success');
        
        // Update the row in table
        const row = document.querySelector(`tr[data-subscription-id="${subscriptionId}"]`);
        if (row) {
          // Update status
          const statusCell = row.querySelector('.status');
          statusCell.className = 'status active';
          statusCell.textContent = 'Active';
          
          // Update actions
          const actionsCell = row.querySelector('.table-actions');
          actionsCell.innerHTML = `
            <button class="action-btn pause-btn" 
                    onclick="pauseSubscription(${subscriptionId})" 
                    title="Pause">⏸️</button>
            <button class="action-btn view-btn" 
                    onclick="viewSubscription(${subscriptionId})" 
                    title="View Details">👁️</button>
          `;
        }
      } else {
        showNotification(data.message || 'Error resuming subscription', 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showNotification('Error resuming subscription', 'error');
    });
  };

  // View subscription details function
  window.viewSubscription = function(subscriptionId) {
    const modal = document.getElementById('subscription-detail-modal');
    const content = document.getElementById('subscription-detail-content');
    
    // Show loading
    content.innerHTML = '<div class="loading">Loading...</div>';
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Fetch subscription details
    fetch(`/subscriptions/${subscriptionId}/details`, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const subscription = data.subscription;
        content.innerHTML = `
          <div class="subscription-details">
            <div class="detail-section">
              <h3>Customer Information</h3>
              <p><strong>Name:</strong> ${subscription.name}</p>
              <p><strong>Phone:</strong> ${subscription.phone}</p>
              <p><strong>Status:</strong> <span class="status ${subscription.status}">${subscription.status.charAt(0).toUpperCase() + subscription.status.slice(1)}</span></p>
            </div>
            
            <div class="detail-section">
              <h3>Subscription Details</h3>
              <p><strong>Plan:</strong> ${subscription.meal_plan.name}</p>
              <p><strong>Price per Meal:</strong> Rp${new Intl.NumberFormat('id-ID').format(subscription.meal_plan.price_per_meal)}</p>
              <p><strong>Total Price:</strong> Rp${new Intl.NumberFormat('id-ID').format(subscription.total_price)}</p>
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
            
            ${subscription.allergies ? `
              <div class="detail-section">
                <h3>Allergies & Dietary Restrictions</h3>
                <p>${subscription.allergies}</p>
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
                <p>${subscription.rejection_reason}</p>
              </div>
            ` : ''}
            
            <div class="detail-section">
              <h3>Timeline</h3>
              <p><strong>Created:</strong> ${new Date(subscription.created_at).toLocaleString()}</p>
              ${subscription.approved_at ? `<p><strong>Approved:</strong> ${new Date(subscription.approved_at).toLocaleString()}</p>` : ''}
              ${subscription.rejected_at ? `<p><strong>Rejected:</strong> ${new Date(subscription.rejected_at).toLocaleString()}</p>` : ''}
            </div>
          </div>
        `;
      } else {
        content.innerHTML = '<div class="error">Error loading subscription details</div>';
      }
    })
    .catch(error => {
      console.error('Error:', error);
      content.innerHTML = '<div class="error">Error loading subscription details</div>';
    });
  };

  // View allergies function
  window.viewAllergies = function(allergies) {
    const modal = document.getElementById('allergies-modal');
    const allergyText = document.getElementById('allergy-text');
    
    allergyText.textContent = allergies;
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
  };

  // Close modal function
  window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Reset forms
    const forms = modal.querySelectorAll('form');
    forms.forEach(form => form.reset());
  };

  // Filter subscriptions function
  window.filterSubscriptions = function(status) {
    const rows = document.querySelectorAll('.subscription-row');
    
    rows.forEach(row => {
      if (status === 'all' || row.dataset.status === status) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  };

  // Sidebar navigation
  const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
  const sections = document.querySelectorAll('.admin-card');

  sidebarLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      
      // Remove active class from all links
      sidebarLinks.forEach(l => l.parentElement.classList.remove('active'));
      
      // Add active class to clicked link
      link.parentElement.classList.add('active');
      
      // Get target section
      const targetId = link.getAttribute('href').substring(1);
      
      if (targetId === 'dashboard') {
        // Show stats grid for dashboard
        const statsGrid = document.querySelector('.stats-grid');
        const chartSection = document.querySelector('.admin-card');
        if (statsGrid) statsGrid.scrollIntoView({ behavior: 'smooth' });
      } else {
        // Show specific section
        const targetSection = document.getElementById(targetId);
        if (targetSection) {
          targetSection.scrollIntoView({ behavior: 'smooth' });
        }
      }
    });
  });

  // Initial page load - set dashboard as active
  const dashboardLink = document.querySelector('a[href="#dashboard"]');
  if (dashboardLink) {
    dashboardLink.parentElement.classList.add('active');
  }

  // Close modals when clicking outside or on close button
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
      e.target.style.display = 'none';
      document.body.style.overflow = 'auto';
    }
    
    if (e.target.classList.contains('close-modal')) {
      const modal = e.target.closest('.modal');
      modal.style.display = 'none';
      document.body.style.overflow = 'auto';
    }
  });

  // Functions for Experience Users and Contacts

  // Show full review
  window.showFullReview = function(reviewText) {
    const modal = document.getElementById('full-review-modal');
    const reviewTextElement = document.getElementById('full-review-text');
    
    reviewTextElement.textContent = reviewText;
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  // Show full message
  window.showFullMessage = function(messageText, subject) {
    const modal = document.getElementById('full-message-modal');
    const messageTextElement = document.getElementById('full-message-text');
    const subjectElement = document.getElementById('message-subject');
    
    messageTextElement.textContent = messageText;
    subjectElement.textContent = subject || 'Contact Message';
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  // Mark contact as read
  window.markAsRead = function(contactId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/admin/contacts/${contactId}/mark-read`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Update the UI to show as read
        const row = document.querySelector(`tr[data-contact-id="${contactId}"]`);
        if (row) {
          row.classList.remove('unread');
          row.classList.add('read');
          
          // Update status cell
          const statusCell = row.cells[5]; // Assuming status is in 6th column (index 5)
          statusCell.innerHTML = `
            <span class="status read">✓ Read</span>
            <br><small class="text-muted">Just now</small>
          `;
          
          // Remove mark as read button
          const markReadBtn = row.querySelector('.mark-read-btn');
          if (markReadBtn) {
            markReadBtn.remove();
          }
        }
        
        // Update unread count
        updateUnreadCount();
        
        showNotification('Contact marked as read', 'success');
      } else {
        showNotification('Error marking contact as read', 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showNotification('Error marking contact as read', 'error');
    });
  }

  // Update unread count in badge
  function updateUnreadCount() {
    const unreadRows = document.querySelectorAll('#contacts tbody tr.unread');
    const unreadBadge = document.querySelector('#contacts .card-header-actions .badge');
    if (unreadBadge) {
      unreadBadge.textContent = `${unreadRows.length} Unread`;
    }
  }
});
