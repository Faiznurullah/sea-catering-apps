document.addEventListener("DOMContentLoaded", () => {
  // Form elements
  const subscriptionForm = document.getElementById("subscription-form")
  const planOptions = document.querySelectorAll('input[name="plan"]')
  const mealTypeCheckboxes = document.querySelectorAll(".meal-type-checkbox")
  const deliveryDayCheckboxes = document.querySelectorAll(".delivery-day-checkbox")

  // Summary elements
  const summaryPlan = document.getElementById("summary-plan")
  const summaryMealTypes = document.getElementById("summary-meal-types")
  const summaryDeliveryDays = document.getElementById("summary-delivery-days")
  const summaryTotal = document.getElementById("summary-total")

  // Error message elements
  const mealTypeError = document.getElementById("meal-type-error")
  const deliveryDayError = document.getElementById("delivery-day-error")

  // Modal elements
  const successModal = document.getElementById("subscription-success-modal")
  const closeSuccessModal = document.getElementById("close-success-modal")
  const closeModalX = document.querySelector(".close-modal")
  const modalPlan = document.getElementById("modal-plan")
  const modalMealTypes = document.getElementById("modal-meal-types")
  const modalDeliveryDays = document.getElementById("modal-delivery-days")
  const modalTotal = document.getElementById("modal-total")

  // Calculate and update price
  function updatePrice() {
    let planPrice = 0
    let selectedPlan = ""

    // Get selected plan price
    planOptions.forEach((option) => {
      if (option.checked) {
        planPrice = Number.parseInt(option.dataset.price)
        selectedPlan = option.nextElementSibling.querySelector("h4").textContent
      }
    })

    // Count selected meal types
    let mealTypeCount = 0
    const selectedMealTypes = []
    mealTypeCheckboxes.forEach((checkbox) => {
      if (checkbox.checked) {
        mealTypeCount++
        selectedMealTypes.push(checkbox.nextElementSibling.textContent)
      }
    })

    // Count selected delivery days
    let deliveryDayCount = 0
    const selectedDeliveryDays = []
    deliveryDayCheckboxes.forEach((checkbox) => {
      if (checkbox.checked) {
        deliveryDayCount++
        selectedDeliveryDays.push(checkbox.nextElementSibling.textContent)
      }
    })

    // Calculate total price
    // Total Price = (Plan Price) × (Number of Meal Types Selected) × (Number of Delivery Days Selected) × 4.3
    const totalPrice = planPrice * mealTypeCount * deliveryDayCount * 4.3

    // Update summary
    summaryPlan.textContent = selectedPlan || "-"
    summaryMealTypes.textContent = selectedMealTypes.length > 0 ? selectedMealTypes.join(", ") : "-"
    summaryDeliveryDays.textContent = selectedDeliveryDays.length > 0 ? selectedDeliveryDays.join(", ") : "-"
    summaryTotal.textContent = `Rp${totalPrice.toLocaleString("id-ID")}`

    return {
      plan: selectedPlan,
      mealTypes: selectedMealTypes,
      deliveryDays: selectedDeliveryDays,
      total: `Rp${totalPrice.toLocaleString("id-ID")}`,
    }
  }

  // Add event listeners to all form inputs to update price
  planOptions.forEach((option) => {
    option.addEventListener("change", updatePrice)
  })

  mealTypeCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", updatePrice)
  })

  deliveryDayCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", updatePrice)
  })
  // Form validation and submission
  subscriptionForm.addEventListener("submit", (event) => {
    event.preventDefault()

    let isValid = true

    // Validate meal types (at least one must be selected)
    let mealTypeSelected = false
    mealTypeCheckboxes.forEach((checkbox) => {
      if (checkbox.checked) {
        mealTypeSelected = true
      }
    })

    if (!mealTypeSelected) {
      mealTypeError.textContent = "Please select at least one meal type"
      mealTypeError.classList.add("show")
      isValid = false
    } else {
      mealTypeError.classList.remove("show")
    }

    // Validate delivery days (at least one must be selected)
    let deliveryDaySelected = false
    deliveryDayCheckboxes.forEach((checkbox) => {
      if (checkbox.checked) {
        deliveryDaySelected = true
      }
    })

    if (!deliveryDaySelected) {
      deliveryDayError.textContent = "Please select at least one delivery day"
      deliveryDayError.classList.add("show")
      isValid = false
    } else {
      deliveryDayError.classList.remove("show")
    }

    // If form is valid, submit via AJAX
    if (isValid) {
      const formData = new FormData(subscriptionForm)
      
      // Show loading state
      const submitBtn = subscriptionForm.querySelector('.submit-btn')
      const originalText = submitBtn.textContent
      submitBtn.textContent = 'Processing...'
      submitBtn.disabled = true

      // Submit form via AJAX
      fetch(subscriptionForm.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const summaryData = updatePrice()

          // Update modal with subscription details
          modalPlan.textContent = summaryData.plan
          modalMealTypes.textContent = summaryData.mealTypes.join(", ")
          modalDeliveryDays.textContent = summaryData.deliveryDays.join(", ")
          modalTotal.textContent = summaryData.total

          // Show success modal
          successModal.style.display = "block"
          document.body.style.overflow = "hidden"
        } else {
          // Handle validation errors
          if (data.errors) {
            let errorMessage = 'Please fix the following errors:\n'
            for (let field in data.errors) {
              errorMessage += '- ' + data.errors[field].join('\n- ') + '\n'
            }
            alert(errorMessage)
          } else {
            alert(data.message || 'An error occurred while creating subscription')
          }
        }
      })
      .catch(error => {
        console.error('Error:', error)
        alert('An error occurred while creating subscription')
      })
      .finally(() => {
        // Reset loading state
        submitBtn.textContent = originalText
        submitBtn.disabled = false
      })
    }
  })

  // Close modal when clicking the close button or outside the modal
  closeSuccessModal.addEventListener("click", () => {
    successModal.style.display = "none"
    document.body.style.overflow = "auto"
    subscriptionForm.reset()
    updatePrice()
  })

  closeModalX.addEventListener("click", () => {
    successModal.style.display = "none"
    document.body.style.overflow = "auto"
  })

  window.addEventListener("click", (event) => {
    if (event.target === successModal) {
      successModal.style.display = "none"
      document.body.style.overflow = "auto"
    }
  })

  // FAQ Accordion
  const faqItems = document.querySelectorAll(".faq-item")

  faqItems.forEach((item) => {
    const question = item.querySelector(".faq-question")

    question.addEventListener("click", () => {
      // Close all other items
      faqItems.forEach((otherItem) => {
        if (otherItem !== item && otherItem.classList.contains("active")) {
          otherItem.classList.remove("active")
        }
      })

      // Toggle current item
      item.classList.toggle("active")
    })
  })

  // Initialize price summary
  updatePrice()
})
