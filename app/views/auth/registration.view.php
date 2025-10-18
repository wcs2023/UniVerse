<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Join UniVerse - Registration</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/registration.css">
  <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body>
  <?php include __DIR__ . '/nav_withback.php'; ?>

  <main class="registration-main">
    <div class="registration-container">
      <div class="registration-header">
        <h1>Join UniVerse</h1>
        <p>Create your account to start your journey</p>
      </div>

      <!-- Progress Indicator -->
      <div class="progress-indicator">
        <div class="progress-step active" data-step="1">
          <div class="step-circle">1</div>
          <span>Role</span>
        </div>
        <div class="progress-step" data-step="2">
          <div class="step-circle">2</div>
          <span>Account</span>
        </div>
        <div class="progress-step" data-step="3">
          <div class="step-circle">3</div>
          <span>Profile</span>
        </div>
      </div>

      <form id="registrationForm" method="POST" action="<?= BASE_URL ?>/registration" novalidate>
        
        <?php if (isset($error)): ?>
          <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>
        
        <!-- Step 1: Role Selection -->
        <div class="form-step active" id="step1">
          <h2>Choose Your Role</h2>
          <p class="step-description">Select the option that best describes you</p>
          
          <div class="role-selection">
            <div class="role-option" data-role="undergraduate">
              <div class="role-icon">🎓</div>
              <h3>Undergraduate Student</h3>
              <p>Current university student seeking opportunities and career guidance</p>
            </div>
            
            <div class="role-option" data-role="school_leaver">
              <div class="role-icon">📚</div>
              <h3>School Leaver</h3>
              <p>Recent graduate exploring university and career paths</p>
            </div>
            
            <div class="role-option" data-role="alumni">
              <div class="role-icon">🎖️</div>
              <h3>Alumni</h3>
              <p>University graduate ready to share experience and explore opportunities</p>
            </div>
            
            <div class="role-option" data-role="company">
              <div class="role-icon">🏢</div>
              <h3>Company</h3>
              <p>Organization looking to recruit talent and post opportunities</p>
            </div>
          </div>

          <input type="hidden" name="user_type" id="selectedRole" required>
          
          <div class="terms-section">
            <label class="checkbox-label">
              <input type="checkbox" id="agreeTerms" name="agree_terms" required>
              <span class="checkmark"></span>
              I agree to the <a href="<?= BASE_URL ?>/termsofservice" class="link">Terms of Service</a> and <a href="<?= BASE_URL ?>/privacypolicy" class="link">Privacy Policy</a>
            </label>
          </div>
        </div>

        <!-- Step 2: Basic Account Information -->
        <div class="form-step" id="step2">
          <h2>Account Information</h2>
          <p class="step-description">Essential details for your UniVerse account</p>
          
          <div class="form-grid">
            <div class="form-group">
              <label for="firstName" class="required">First Name</label>
              <input type="text" id="firstName" name="first_name" required autocomplete="given-name">
              <div class="field-error"></div>
            </div>
            
            <div class="form-group">
              <label for="lastName" class="required">Last Name</label>
              <input type="text" id="lastName" name="last_name" required autocomplete="family-name">
              <div class="field-error"></div>
            </div>
            
            <div class="form-group full-width">
              <label for="email" class="required">Email Address</label>
              <input type="email" id="email" name="email" required autocomplete="email">
              <div class="field-error"></div>
              <div class="field-hint">Used for account notifications and login</div>
            </div>
            
            <div class="form-group full-width">
              <label for="username" class="required">Username</label>
              <input type="text" id="username" name="username" required autocomplete="username" 
                     minlength="3" maxlength="20" pattern="[a-zA-Z0-9_]+">
              <div class="field-error"></div>
              <div class="field-hint">3-20 characters, letters, numbers, and underscores only</div>
            </div>
            
            <div class="form-group">
              <label for="password" class="required">Password</label>
              <div class="password-field">
                <input type="password" id="password" name="password" required 
                       autocomplete="new-password" minlength="8">
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                  <span class="toggle-icon">👁️</span>
                </button>
              </div>
              <div class="password-strength">
                <div class="strength-bar">
                  <div class="strength-fill"></div>
                </div>
                <span class="strength-text">Password strength</span>
              </div>
              <div class="field-error"></div>
            </div>
            
            <div class="form-group">
              <label for="confirmPassword" class="required">Confirm Password</label>
              <div class="password-field">
                <input type="password" id="confirmPassword" name="confirmPassword" required autocomplete="new-password">
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                  <span class="toggle-icon">👁️</span>
                </button>
              </div>
              <div class="password-match"></div>
              <div class="field-error"></div>
            </div>
            
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" autocomplete="tel">
              <div class="field-error"></div>
              <div class="field-hint">Optional - for account recovery</div>
            </div>
            
            <div class="form-group">
              <label for="gender">Gender</label>
              <select id="gender" name="gender" autocomplete="sex">
                <option value="">Select (Optional)</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
                <option value="prefer_not_to_say">Prefer not to say</option>
              </select>
            </div>
            
            <div class="form-group full-width">
              <label for="dateOfBirth">Date of Birth</label>
              <input type="date" id="dateOfBirth" name="date_of_birth" autocomplete="bday" max="2010-12-31">
              <div class="field-error"></div>
              <div class="field-hint">Must be at least 13 years old</div>
            </div>
          </div>
        </div>

        <!-- Step 3: Role-Specific Profile -->
        <div class="form-step" id="step3">
          <!-- Undergraduate Profile -->
          <div class="role-profile" id="undergraduateProfile">
            <h2>Academic Information</h2>
            <p class="step-description">Tell us about your university journey</p>
            
            <div class="form-grid">
              <div class="form-group full-width">
                <label for="universityName" class="required">University Name</label>
                <input type="text" id="universityName" name="university_name" required>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="faculty" class="required">Faculty</label>
                <input type="text" id="faculty" name="faculty" placeholder="e.g., Engineering, Arts, Science" required>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="degreeProgram" class="required">Degree Program</label>
                <input type="text" id="degreeProgram" name="degree_program" placeholder="e.g., Computer Science, Business" required>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="academicYear" class="required">Academic Year</label>
                <select id="academicYear" name="academic_year" required>
                  <option value="">Select Year</option>
                  <option value="1st year">1st Year</option>
                  <option value="2nd year">2nd Year</option>
                  <option value="3rd year">3rd Year</option>
                  <option value="4th year">4th Year</option>
                </select>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="graduationYear" class="required">Expected Graduation</label>
                <select id="graduationYear" name="expected_graduation_year" required>
                  <option value="">Select Year</option>
                  <option value="2024">2024</option>
                  <option value="2025">2025</option>
                  <option value="2026">2026</option>
                  <option value="2027">2027</option>
                  <option value="2028">2028</option>
                  <option value="2029">2029</option>
                  <option value="2030">2030</option>
                </select>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group full-width">
                <label for="skillsInterests">Skills & Interests</label>
                <textarea id="skillsInterests" name="skills_interests" rows="3" 
                          placeholder="Tell us about your skills, interests, and what you're passionate about..."></textarea>
                <div class="field-hint">This helps us match you with relevant opportunities</div>
              </div>
            </div>
          </div>

          <!-- School Leaver Profile -->
          <div class="role-profile" id="schoolLeaverProfile">
            <h2>Educational Background</h2>
            <p class="step-description">Tell us about your educational journey</p>
            
            <div class="form-grid">
              <div class="form-group full-width">
                <label for="qualification" class="required">Highest Qualification</label>
                <select id="qualification" name="highest_qualification" required>
                  <option value="">Select Qualification</option>
                  <option value="A/L">Advanced Level (A/L)</option>
                  <option value="O/L">Ordinary Level (O/L)</option>
                  <option value="vocational">Vocational Course</option>
                  <option value="diploma">Diploma</option>
                  <option value="other">Other</option>
                </select>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group full-width">
                <label for="interestedFields">Career Interests</label>
                <textarea id="interestedFields" name="interested_fields" rows="3" 
                          placeholder="What fields or careers interest you? e.g., IT, Engineering, Business, Arts..."></textarea>
                <div class="field-hint">Help us understand your career aspirations</div>
              </div>
              
              <div class="form-group full-width">
                <label for="currentSkills">Skills & Experience</label>
                <textarea id="currentSkills" name="skills" rows="3" 
                          placeholder="Any skills, hobbies, or experience you'd like to share..."></textarea>
                <div class="field-hint">Include any relevant skills or experiences</div>
              </div>
            </div>
          </div>

          <!-- Company Profile -->
          <div class="role-profile" id="companyProfile" style="display: none;">
            <h2>Company Information</h2>
            <p class="step-description">Tell us about your organization</p>
            
            <div class="form-grid">
              <div class="form-group">
                <label for="companyName" class="required">Company Name</label>
                <input type="text" id="companyName" name="company_name" required>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="companySize" class="required">Company Size</label>
                <select id="companySize" name="company_size" required>
                  <option value="">Select Size</option>
                  <option value="startup">Startup (1-10 employees)</option>
                  <option value="small">Small (11-50 employees)</option>
                  <option value="medium">Medium (51-200 employees)</option>
                  <option value="large">Large (201-1000 employees)</option>
                  <option value="enterprise">Enterprise (1000+ employees)</option>
                </select>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group full-width">
                <label for="industry" class="required">Industry</label>
                <input type="text" id="industry" name="industry" placeholder="e.g., Technology, Healthcare, Finance" required>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="website">Website</label>
                <input type="url" id="website" name="website" placeholder="https://company.com">
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="foundedYear">Founded Year</label>
                <input type="number" id="foundedYear" name="founded_year" min="1800" max="2025">
                <div class="field-error"></div>
              </div>
              
              <div class="form-group full-width">
                <label for="companyDescription">Company Description</label>
                <textarea id="companyDescription" name="description" rows="3" 
                          placeholder="Brief description of your company and what you do..."></textarea>
                <div class="field-hint">Help candidates understand your company</div>
              </div>
              
              <div class="form-group">
                <label for="contactPersonName" class="required">Contact Person</label>
                <input type="text" id="contactPersonName" name="contact_person_name" placeholder="Full name" required>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="contactEmail" class="required">Contact Email</label>
                <input type="email" id="contactEmail" name="contact_email" placeholder="contact@company.com" required>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group full-width">
                <label for="contactPhone">Contact Phone</label>
                <input type="tel" id="contactPhone" name="contact_phone" placeholder="+1 (555) 123-4567">
                <div class="field-error"></div>
              </div>
            </div>
          </div>

          <!-- Alumni Profile -->
          <div class="role-profile" id="alumniProfile">
            <h2>Alumni Information</h2>
            <p class="step-description">Tell us about your educational background and achievements</p>
            
            <div class="form-grid">
              <div class="form-group full-width">
                <label for="alumniUniversityName" class="required">University Name</label>
                <input type="text" id="alumniUniversityName" name="university_name" 
                       placeholder="e.g., University of Sri Lanka" required>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="alumniDegreeProgram" class="required">Degree Program</label>
                <input type="text" id="alumniDegreeProgram" name="degree_program" 
                       placeholder="e.g., Computer Science, Engineering" required>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="alumniGraduationYear" class="required">Graduation Year</label>
                <select id="alumniGraduationYear" name="graduation_year" required>
                  <option value="">Select Year</option>
                  <?php for($year = 2025; $year >= 1970; $year--): ?>
                    <option value="<?= $year ?>"><?= $year ?></option>
                  <?php endfor; ?>
                </select>
                <div class="field-error"></div>
              </div>
              
              <!-- Optional additional degree fields -->
              <div class="form-group full-width">
                <h4 style="margin: 1.5rem 0 1rem 0; color: var(--text-dark);">Additional Degrees (Optional)</h4>
                <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 1rem;">
                  Add any additional qualifications you have obtained
                </p>
              </div>
              
              <div class="form-group">
                <label for="additionalDegree1">Second Degree Program</label>
                <input type="text" id="additionalDegree1" name="additional_degree_1" 
                       placeholder="e.g., MBA, Masters in Engineering (Optional)">
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="additionalUniversity1">University/Institution</label>
                <input type="text" id="additionalUniversity1" name="additional_university_1" 
                       placeholder="University name (Optional)">
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="additionalGradYear1">Graduation Year</label>
                <select id="additionalGradYear1" name="additional_grad_year_1">
                  <option value="">Select Year (Optional)</option>
                  <?php for($year = 2025; $year >= 1970; $year--): ?>
                    <option value="<?= $year ?>"><?= $year ?></option>
                  <?php endfor; ?>
                </select>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="additionalDegree2">Third Degree Program</label>
                <input type="text" id="additionalDegree2" name="additional_degree_2" 
                       placeholder="e.g., PhD, Professional Certification (Optional)">
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="additionalUniversity2">University/Institution</label>
                <input type="text" id="additionalUniversity2" name="additional_university_2" 
                       placeholder="University name (Optional)">
                <div class="field-error"></div>
              </div>
              
              <div class="form-group">
                <label for="additionalGradYear2">Graduation Year</label>
                <select id="additionalGradYear2" name="additional_grad_year_2">
                  <option value="">Select Year (Optional)</option>
                  <?php for($year = 2025; $year >= 1970; $year--): ?>
                    <option value="<?= $year ?>"><?= $year ?></option>
                  <?php endfor; ?>
                </select>
                <div class="field-error"></div>
              </div>
              
              <div class="form-group full-width">
                <label for="alumniSkillsExperience">Skills & Experience</label>
                <textarea id="alumniSkillsExperience" name="skills_experience" rows="3" 
                          placeholder="Brief overview of your professional skills and experience..."></textarea>
                <div class="field-hint">This will help other users connect with you based on your expertise</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
          <button type="button" id="backBtn" class="btn btn-secondary" style="display: none;">
            ← Back
          </button>
          <button type="button" id="nextBtn" class="btn btn-primary" disabled>
            Next →
          </button>
          <button type="submit" id="submitBtn" class="btn btn-primary" style="display: none;">
            Create Account
          </button>
        </div>
      </form>

      <div class="auth-footer">
        <p>Already have an account? <a href="<?= BASE_URL ?>/login" class="link">Sign In</a></p>
      </div>
    </div>
  </main>

  <?php include __DIR__ . '/../layout/footer.php'; ?>
  
  <script src="<?= BASE_URL ?>/js/registration.js"></script>
</body>
</html>
