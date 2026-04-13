// Registration Form Handler
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const form = document.getElementById('registrationForm');
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    const nextBtn = document.getElementById('nextBtn');
    const backBtn = document.getElementById('backBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    // Role selection elements
    const roleOptions = document.querySelectorAll('.role-option');
    const selectedRoleInput = document.getElementById('selectedRole');
    const agreeTermsCheckbox = document.getElementById('agreeTerms');
    
    // Profile sections
    const roleProfiles = document.querySelectorAll('.role-profile');
    
    // Current step tracking
    let currentStep = 1;
    const totalSteps = 3;
    
    // Initialize form
    init();
    
    function init() {
        setupRoleSelection();
        setupPasswordHandling();
        setupFormValidation();
        setupNavigation();
        updateStepVisibility();
        updateNavigationButtons();
    }
    
    // Role Selection Setup
    function setupRoleSelection() {
        roleOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove selection from all options
                roleOptions.forEach(opt => opt.classList.remove('selected'));
                
                // Add selection to clicked option
                this.classList.add('selected');
                
                // Set hidden input value
                const role = this.dataset.role;
                selectedRoleInput.value = role;
                
                // Show appropriate profile section for step 3
                showRoleProfile(role);
                
                // Update next button state
                updateNavigationButtons();
            });
        });
        
        // Terms checkbox handling
        agreeTermsCheckbox.addEventListener('change', function() {
            updateNavigationButtons();
        });
    }
    
    // Show role-specific profile section
    function showRoleProfile(role) {
        roleProfiles.forEach(profile => {
            profile.style.display = 'none';
            profile.classList.remove('active');
            
            // Disable required validation for hidden profiles
            const requiredFields = profile.querySelectorAll('input[required], select[required], textarea[required]');
            requiredFields.forEach(field => {
                field.setAttribute('data-was-required', 'true');
                field.removeAttribute('required');
            });
        });
        
        // Show the selected role profile
        const targetProfile = document.getElementById(`${role}Profile`);
        if (targetProfile) {
            targetProfile.style.display = 'block';
            targetProfile.classList.add('active');
            
            // Re-enable required validation for active profile
            const requiredFields = targetProfile.querySelectorAll('[data-was-required="true"]');
            requiredFields.forEach(field => {
                field.setAttribute('required', 'required');
            });
        }
    }
    
    // Password handling setup
    function setupPasswordHandling() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const passwordToggles = document.querySelectorAll('.password-toggle');
        const strengthBar = document.querySelector('.strength-fill');
        const strengthText = document.querySelector('.strength-text');
        const passwordMatch = document.querySelector('.password-match');
        
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                checkPasswordMatch();
            });
        }
        
        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        }
        
        // Setup password toggle for each password field
        passwordToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                togglePasswordVisibility(this);
            });
        });
        
        function checkPasswordStrength(password) {
            if (!strengthBar || !strengthText) return;
            
            let strength = 0;
            let text = '';
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            switch (strength) {
                case 0:
                case 1:
                    strengthBar.className = 'strength-fill weak';
                    strengthText.className = 'strength-text weak';
                    text = 'Weak';
                    break;
                case 2:
                    strengthBar.className = 'strength-fill fair';
                    strengthText.className = 'strength-text fair';
                    text = 'Fair';
                    break;
                case 3:
                case 4:
                    strengthBar.className = 'strength-fill good';
                    strengthText.className = 'strength-text good';
                    text = 'Good';
                    break;
                case 5:
                    strengthBar.className = 'strength-fill strong';
                    strengthText.className = 'strength-text strong';
                    text = 'Strong';
                    break;
            }
            
            strengthText.textContent = text;
        }
        
        function checkPasswordMatch() {
            if (!passwordInput || !confirmPasswordInput || !passwordMatch) return;
            
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (confirmPassword === '') {
                passwordMatch.style.display = 'none';
                return;
            }
            
            passwordMatch.style.display = 'block';
            
            if (password === confirmPassword) {
                passwordMatch.className = 'password-match match';
                passwordMatch.textContent = '✓ Passwords match';
            } else {
                passwordMatch.className = 'password-match no-match';
                passwordMatch.textContent = '✗ Passwords do not match';
            }
        }
        
        function togglePasswordVisibility(toggle) {
            const input = toggle.previousElementSibling;
            const icon = toggle.querySelector('.toggle-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = '🙈';
            } else {
                input.type = 'password';
                icon.textContent = '👁️';
            }
        }
    }
    
    // Form validation setup
    function setupFormValidation() {
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
                updateNavigationButtons();
            });
        });
    }
    
    function validateField(field) {
        const formGroup = field.closest('.form-group');
        const errorElement = formGroup.querySelector('.field-error');
        let isValid = true;
        let errorMessage = '';
        
        // Clear previous errors
        clearFieldError(field);
        
        // Check if field is required and empty
        if (field.hasAttribute('required') && !field.value.trim()) {
            errorMessage = `${getFieldLabel(field)} is required`;
            isValid = false;
        }
        // Email validation
        else if (field.type === 'email' && field.value) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(field.value)) {
                errorMessage = 'Please enter a valid email address';
                isValid = false;
            }
        }
        // Password validation
        else if (field.type === 'password' && field.value) {
            if (field.value.length < 8) {
                errorMessage = 'Password must be at least 8 characters long';
                isValid = false;
            }
        }
        // Username validation
        else if (field.name === 'username' && field.value) {
            if (field.value.length < 3) {
                errorMessage = 'Username must be at least 3 characters long';
                isValid = false;
            }
            if (!/^[a-zA-Z0-9_]+$/.test(field.value)) {
                errorMessage = 'Username can only contain letters, numbers, and underscores';
                isValid = false;
            }
        }
        // Phone number validation (Sri Lankan numbers only)
        else if (field.type === 'tel' && field.value) {
            const phonePattern = /^\+94\d{9}$/;
            if (!phonePattern.test(field.value)) {
                errorMessage = 'Phone number must be in format +94xxxxxxxxx (e.g., +94771234567)';
                isValid = false;
            }
        }
        
        if (!isValid) {
            showFieldError(field, errorMessage);
        }
        
        return isValid;
    }
    
    function showFieldError(field, message) {
        const formGroup = field.closest('.form-group');
        const errorElement = formGroup.querySelector('.field-error');
        
        field.classList.add('error');
        field.classList.remove('success');
        
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }
    }
    
    function clearFieldError(field) {
        const formGroup = field.closest('.form-group');
        const errorElement = formGroup.querySelector('.field-error');
        
        field.classList.remove('error');
        
        if (errorElement) {
            errorElement.textContent = '';
            errorElement.classList.remove('show');
        }
        
        if (field.value.trim()) {
            field.classList.add('success');
        } else {
            field.classList.remove('success');
        }
    }
    
    function getFieldLabel(field) {
        const label = field.closest('.form-group').querySelector('label');
        return label ? label.textContent.replace(' *', '') : 'Field';
    }
    
    // Navigation setup
    function setupNavigation() {
        nextBtn.addEventListener('click', function() {
            if (validateCurrentStep()) {
                goToStep(currentStep + 1);
            }
        });
        
        backBtn.addEventListener('click', function() {
            goToStep(currentStep - 1);
        });
        
        // Allow clicking on progress steps to navigate (if step is completed)
        progressSteps.forEach((step, index) => {
            step.addEventListener('click', function() {
                const stepNumber = index + 1;
                if (stepNumber < currentStep) {
                    goToStep(stepNumber);
                }
            });
        });
    }
    
    function goToStep(stepNumber) {
        if (stepNumber < 1 || stepNumber > totalSteps) return;
        
        // Hide current step
        steps[currentStep - 1].classList.remove('active');
        progressSteps[currentStep - 1].classList.remove('active');
        
        // Show new step
        currentStep = stepNumber;
        steps[currentStep - 1].classList.add('active');
        progressSteps[currentStep - 1].classList.add('active');
        
        // Mark previous steps as completed
        progressSteps.forEach((step, index) => {
            if (index < currentStep - 1) {
                step.classList.add('completed');
            } else if (index === currentStep - 1) {
                step.classList.remove('completed');
                step.classList.add('active');
            } else {
                step.classList.remove('completed', 'active');
            }
        });
        
        updateNavigationButtons();
        
        // Scroll to top of form
        document.querySelector('.registration-container').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
    
    function validateCurrentStep() {
        const currentStepElement = steps[currentStep - 1];
        let isValid = true;
        
        if (currentStep === 1) {
            // Validate role selection and terms
            if (!selectedRoleInput.value) {
                alert('Please select your role');
                isValid = false;
            } else if (!agreeTermsCheckbox.checked) {
                alert('Please agree to the terms and conditions');
                isValid = false;
            }
        } else {
            // Validate all required fields in current step
            const requiredFields = currentStepElement.querySelectorAll('input[required], select[required], textarea[required]');
            
            requiredFields.forEach(field => {
                if (!validateField(field)) {
                    isValid = false;
                }
            });
            
            // Special validation for password confirmation
            if (currentStep === 2) {
                const passwordInput = document.getElementById('password');
                const confirmPasswordInput = document.getElementById('confirmPassword');
                
                if (passwordInput && confirmPasswordInput) {
                    if (passwordInput.value !== confirmPasswordInput.value) {
                        showFieldError(confirmPasswordInput, 'Passwords do not match');
                        isValid = false;
                    }
                }
            }
        }
        
        return isValid;
    }
    
    function updateNavigationButtons() {
        // Back button
        if (currentStep === 1) {
            backBtn.style.display = 'none';
        } else {
            backBtn.style.display = 'block';
        }
        
        // Next/Submit button
        if (currentStep === totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'block';
        } else {
            nextBtn.style.display = 'block';
            submitBtn.style.display = 'none';
            
            // Enable/disable next button based on current step validation
            const canProceed = canProceedFromCurrentStep();
            nextBtn.disabled = !canProceed;
        }
    }
    
    function canProceedFromCurrentStep() {
        if (currentStep === 1) {
            return selectedRoleInput.value && agreeTermsCheckbox.checked;
        } else if (currentStep === 2) {
            const requiredFields = steps[currentStep - 1].querySelectorAll('input[required], select[required], textarea[required]');
            return Array.from(requiredFields).every(field => field.value.trim() !== '');
        } else if (currentStep === 3) {
            const activeProfile = document.querySelector('.role-profile.active');
            if (activeProfile) {
                const requiredFields = activeProfile.querySelectorAll('input[required], select[required], textarea[required]');
                return Array.from(requiredFields).every(field => field.value.trim() !== '');
            }
            return false; // No active profile means can't proceed
        }
        return true;
    }
    
    function updateStepVisibility() {
        steps.forEach((step, index) => {
            if (index === currentStep - 1) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });
        
        progressSteps.forEach((step, index) => {
            if (index === currentStep - 1) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateCurrentStep()) {
            // Add loading state
            submitBtn.innerHTML = '<span class="loading-spinner"></span>Creating Account...';
            submitBtn.disabled = true;
            
            // Submit form (you can add AJAX here if needed)
            setTimeout(() => {
                this.submit();
            }, 500);
        }
    });
    
    // Real-time validation for better UX
    document.addEventListener('input', function(e) {
        if (e.target.matches('input, select, textarea')) {
            updateNavigationButtons();
        }
    });
    
    document.addEventListener('change', function(e) {
        if (e.target.matches('input[type="checkbox"], input[type="radio"], select')) {
            updateNavigationButtons();
        }
    });
});
