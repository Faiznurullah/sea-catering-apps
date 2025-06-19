document.addEventListener("DOMContentLoaded", () => {
  // Plan Tabs
  const planTabs = document.querySelectorAll(".plan-tab")
  const planContents = document.querySelectorAll(".plan-content")

  planTabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      // Remove active class from all tabs and contents
      planTabs.forEach((t) => t.classList.remove("active"))
      planContents.forEach((c) => c.classList.remove("active"))

      // Add active class to clicked tab and corresponding content
      tab.classList.add("active")
      const planId = tab.getAttribute("data-plan")
      document.getElementById(`${planId}-plan`).classList.add("active")
    })
  })

  // Modal Functionality
  const modal = document.getElementById("plan-details-modal")
  const closeModal = document.querySelector(".close-modal")
  const seeDetailsButtons = document.querySelectorAll(".see-details")
  const modalPlanContents = document.querySelectorAll(".modal-plan-content")

  seeDetailsButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const planId = button.getAttribute("data-plan")

      // Hide all modal plan contents
      modalPlanContents.forEach((content) => {
        content.style.display = "none"
      })

      // Show the selected plan content
      document.getElementById(`modal-${planId}`).style.display = "block"

      // Show the modal
      modal.style.display = "block"

      // Prevent scrolling on the body
      document.body.style.overflow = "hidden"
    })
  })

  closeModal.addEventListener("click", () => {
    modal.style.display = "none"
    document.body.style.overflow = "auto"
  })

  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.style.display = "none"
      document.body.style.overflow = "auto"
    }
  })

  // Testimonial Form Submission
  const testimonialForm = document.getElementById("testimonial-form")

  if (testimonialForm) {
    testimonialForm.addEventListener("submit", (event) => {
      event.preventDefault()

      const name = document.getElementById("customer-name").value
      const review = document.getElementById("review-message").value
      const rating = document.querySelector('input[name="rating"]:checked').value

      // In a real application, this would send data to a server
      // For now, we'll just show an alert and reset the form
      alert(`Thank you for your review, ${name}! Your feedback has been submitted.`)
      testimonialForm.reset()

      // Create a new testimonial card (this would normally come from the server)
      const newTestimonial = {
        name: name,
        text: review,
        rating: Number.parseInt(rating),
      }

      // Add the new testimonial to the slider (in a real app, this would be handled differently)
      addNewTestimonial(newTestimonial)
    })
  }

  function addNewTestimonial(testimonial) {
    const testimonialSlider = document.querySelector(".testimonial-slider")
    const dotsContainer = document.querySelector(".testimonial-dots")

    if (!testimonialSlider || !dotsContainer) return

    // Create new testimonial card
    const newCard = document.createElement("div")
    newCard.className = "testimonial-card"

    // Create rating stars
    const ratingDiv = document.createElement("div")
    ratingDiv.className = "rating"
    let stars = ""
    for (let i = 0; i < testimonial.rating; i++) {
      stars += "★"
    }
    for (let i = testimonial.rating; i < 5; i++) {
      stars += "☆"
    }
    ratingDiv.textContent = stars

    // Create testimonial text
    const textP = document.createElement("p")
    textP.className = "testimonial-text"
    textP.textContent = `"${testimonial.text}"`

    // Create customer name
    const nameP = document.createElement("p")
    nameP.className = "customer-name"
    nameP.textContent = `- ${testimonial.name}`

    // Append elements to card
    newCard.appendChild(ratingDiv)
    newCard.appendChild(textP)
    newCard.appendChild(nameP)

    // Add card to slider
    testimonialSlider.appendChild(newCard)

    // Add new dot
    const newDot = document.createElement("span")
    newDot.className = "dot"
    dotsContainer.appendChild(newDot)

    // Update dots click events
    updateDots()

    // Show success message
    const successMessage = document.createElement("div")
    successMessage.className = "success-message"
    successMessage.textContent = "Your testimonial has been added!"
    testimonialForm.parentNode.insertBefore(successMessage, testimonialForm.nextSibling)

    // Remove success message after 3 seconds
    setTimeout(() => {
      successMessage.remove()
    }, 3000)
  }

  function updateDots() {
    const dots = document.querySelectorAll(".dot")
    const testimonials = document.querySelectorAll(".testimonial-card")

    dots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        testimonials.forEach((testimonial) => testimonial.classList.remove("active"))
        dots.forEach((d) => d.classList.remove("active"))

        if (testimonials[index]) {
          testimonials[index].classList.add("active")
          dot.classList.add("active")
        }
      })
    })
  }
})
