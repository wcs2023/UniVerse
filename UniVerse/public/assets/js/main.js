// Main JavaScript file for UniVerse platform
// Function to scroll to top on page load/refresh
window.onload = function() {
  window.scrollTo(0, 0);
}

// Also scroll to top when navigating to a new page
document.addEventListener('DOMContentLoaded', () => {
  history.scrollRestoration = 'manual';
  window.scrollTo(0, 0);

  // Initialize all functionality
  initScrollProgress()
  initScrollAnimations()
  initParallaxEffect()
  initButtonEffects()
  initMobileMenu()
  initSmoothScrolling()
  initDropdowns()
  initLoadingStates()
})

// Scroll Progress Bar
function initScrollProgress() {
  function updateScrollProgress() {
    const scrollTop = window.pageYOffset
    const docHeight = document.body.scrollHeight - window.innerHeight
    const scrollPercent = (scrollTop / docHeight) * 100
    const progressBar = document.getElementById("scroll-progress")
    if (progressBar) {
      progressBar.style.width = Math.min(scrollPercent, 100) + "%"
    }
  }

  window.addEventListener("scroll", updateScrollProgress)
  updateScrollProgress() // Initial call
}

// Scroll Animations using Intersection Observer
function initScrollAnimations() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("animate")
      }
    })
  }, observerOptions)

  // Observe all animation elements
  const animateElements = document.querySelectorAll(".fade-in, .slide-in-left, .slide-in-right, .scale-in")
  animateElements.forEach((el) => {
    observer.observe(el)
  })
}

// Parallax Effect for Hero Section
function initParallaxEffect() {
  let ticking = false

  function updateParallax() {
    const scrolled = window.pageYOffset
    const heroImage = document.querySelector(".hero-image")
    if (heroImage) {
      heroImage.style.transform = `translateY(${scrolled * 0.2}px)`
    }
    ticking = false
  }

  function requestTick() {
    if (!ticking) {
      requestAnimationFrame(updateParallax)
      ticking = true
    }
  }

  window.addEventListener("scroll", requestTick)
}

// Button Effects and Interactions
function initButtonEffects() {
  // Primary button click effects
  const primaryBtns = document.querySelectorAll(".btn-primary")
  primaryBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      if (!btn.disabled) {
        btn.style.transform = "scale(0.95)"
        setTimeout(() => {
          btn.style.transform = ""
        }, 150)
      }
    })
  })

  // Learn More button smooth scroll
  const learnMoreBtn = document.getElementById("learn-more-btn")
  if (learnMoreBtn) {
    learnMoreBtn.addEventListener("click", (e) => {
      e.preventDefault()
      const featuresSection = document.querySelector("#features")
      if (featuresSection) {
        featuresSection.scrollIntoView({
          behavior: "smooth",
          block: "start",
        })
      }
    })
  }

  // All button hover effects
  const allBtns = document.querySelectorAll(".btn")
  allBtns.forEach((btn) => {
    btn.addEventListener("mouseenter", () => {
      if (!btn.disabled) {
        btn.style.transform = "translateY(-2px)"
      }
    })

    btn.addEventListener("mouseleave", () => {
      if (!btn.disabled) {
        btn.style.transform = ""
      }
    })
  })
}

// Mobile Menu Toggle
function initMobileMenu() {
  const mobileMenuBtn = document.getElementById("mobile-menu-btn")
  const navMenu = document.getElementById("nav-menu")

  if (mobileMenuBtn && navMenu) {
    mobileMenuBtn.addEventListener("click", () => {
      navMenu.classList.toggle("active")
      mobileMenuBtn.classList.toggle("active")

      // Prevent body scroll when menu is open
      if (navMenu.classList.contains("active")) {
        document.body.style.overflow = "hidden"
      } else {
        document.body.style.overflow = ""
      }
    })

    // Close menu when clicking outside
    document.addEventListener("click", (e) => {
      if (!mobileMenuBtn.contains(e.target) && !navMenu.contains(e.target)) {
        navMenu.classList.remove("active")
        mobileMenuBtn.classList.remove("active")
        document.body.style.overflow = ""
      }
    })

    // Close menu on window resize
    window.addEventListener("resize", () => {
      if (window.innerWidth > 768) {
        navMenu.classList.remove("active")
        mobileMenuBtn.classList.remove("active")
        document.body.style.overflow = ""
      }
    })
  }
}

// Smooth Scrolling for Anchor Links
function initSmoothScrolling() {
  const anchorLinks = document.querySelectorAll('a[href^="#"]')

  anchorLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      const targetId = link.getAttribute("href")
      if (targetId === "#") return

      const targetSection = document.querySelector(targetId)

      if (targetSection) {
        e.preventDefault()
        const headerHeight = document.querySelector(".header").offsetHeight
        const targetPosition = targetSection.offsetTop - headerHeight - 20

        window.scrollTo({
          top: targetPosition,
          behavior: "smooth",
        })
      }
    })
  })
}

// Dropdown Menu Interactions
function initDropdowns() {
  const dropdowns = document.querySelectorAll(".dropdown")

  dropdowns.forEach((dropdown) => {
    const menu = dropdown.querySelector(".dropdown-menu")
    let timeoutId

    dropdown.addEventListener("mouseenter", () => {
      clearTimeout(timeoutId)
      if (menu) {
        menu.style.opacity = "1"
        menu.style.visibility = "visible"
        menu.style.transform = "translateY(0)"
      }
    })

    dropdown.addEventListener("mouseleave", () => {
      timeoutId = setTimeout(() => {
        if (menu) {
          menu.style.opacity = "0"
          menu.style.visibility = "hidden"
          menu.style.transform = "translateY(-10px)"
        }
      }, 100)
    })
  })
}

// Loading States Management
function initLoadingStates() {
  // Add loading state to buttons during form submission
  const forms = document.querySelectorAll("form")

  forms.forEach((form) => {
    form.addEventListener("submit", () => {
      const submitBtn = form.querySelector('button[type="submit"]')
      if (submitBtn) {
        submitBtn.classList.add("loading")
        submitBtn.disabled = true
      }
    })
  })
}

// Hero Image Hover Effects
document.addEventListener("DOMContentLoaded", () => {
  const heroMainImage = document.querySelector(".hero-main-image")

  if (heroMainImage) {
    heroMainImage.addEventListener("mouseenter", () => {
      heroMainImage.style.transform = "translateY(-10px)"
      heroMainImage.style.boxShadow = "0 30px 60px rgba(107, 70, 193, 0.2)"
    })

    heroMainImage.addEventListener("mouseleave", () => {
      heroMainImage.style.transform = ""
      heroMainImage.style.boxShadow = "0 20px 40px rgba(107, 70, 193, 0.15)"
    })
  }
})

// Feature Card Hover Effects
document.addEventListener("DOMContentLoaded", () => {
  const featureCards = document.querySelectorAll(".feature-card")

  featureCards.forEach((card) => {
    card.addEventListener("mouseenter", () => {
      const icon = card.querySelector(".feature-icon")
      if (icon) {
        icon.style.transform = "scale(1.1) rotate(5deg)"
      }
    })

    card.addEventListener("mouseleave", () => {
      const icon = card.querySelector(".feature-icon")
      if (icon) {
        icon.style.transform = ""
      }
    })
  })
})

// Audience Card Hover Effects
document.addEventListener("DOMContentLoaded", () => {
  const audienceCards = document.querySelectorAll(".audience-card")

  audienceCards.forEach((card) => {
    card.addEventListener("mouseenter", () => {
      const icon = card.querySelector(".audience-icon")
      if (icon) {
        icon.style.transform = "scale(1.1)"
      }
    })

    card.addEventListener("mouseleave", () => {
      const icon = card.querySelector(".audience-icon")
      if (icon) {
        icon.style.transform = ""
      }
    })
  })
})

// Utility Functions
function showMessage(element, message, type = "success") {
  if (!element) return

  element.textContent = message
  element.className = `form-message ${type}`
  element.style.display = "block"

  // Auto hide after 5 seconds
  setTimeout(() => {
    element.style.display = "none"
  }, 5000)
}

function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

function validatePhone(phone) {
  const phoneRegex = /^[+]?[0-9\s\-$$$$]{10,}$/
  return phoneRegex.test(phone)
}

// Handle window resize
window.addEventListener("resize", () => {
  // Reset any transforms on resize for mobile
  const heroImage = document.querySelector(".hero-image")
  if (heroImage && window.innerWidth <= 768) {
    heroImage.style.transform = ""
  }
})

// Add loading animation on page load
window.addEventListener("load", () => {
  document.body.classList.add("loaded")

  // Hide any loading spinners
  const spinners = document.querySelectorAll(".spinner")
  spinners.forEach((spinner) => {
    spinner.style.display = "none"
  })
})

// Error handling for images
document.addEventListener("DOMContentLoaded", () => {
  const images = document.querySelectorAll("img")

  images.forEach((img) => {
    img.addEventListener("error", function () {
      // Replace with placeholder if image fails to load
      this.src = "/placeholder.svg?height=200&width=200&text=Image+Not+Found"
    })
  })
})

// Keyboard navigation support
document.addEventListener("keydown", (e) => {
  // Close mobile menu with Escape key
  if (e.key === "Escape") {
    const navMenu = document.getElementById("nav-menu")
    const mobileMenuBtn = document.getElementById("mobile-menu-btn")

    if (navMenu && navMenu.classList.contains("active")) {
      navMenu.classList.remove("active")
      mobileMenuBtn.classList.remove("active")
      document.body.style.overflow = ""
    }
  }
})

// Performance optimization: Debounce scroll events
function debounce(func, wait) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

// Export functions for use in other files
window.UniVerse = {
  showMessage,
  validateEmail,
  validatePhone,
  debounce,
}
