-- Insert forum categories for both students and alumni
INSERT INTO forum_categories (slug, name, description, sort_order, created_at) VALUES
-- Student Categories (1-15)
('university-selection', 'University Selection', 'Discuss different universities, programs, and admission requirements', 1, NOW()),
('career-planning', 'Career Planning', 'Share career advice, job market insights, and professional development tips', 2, NOW()),
('study-tips-advice', 'Study Tips & Advice', 'Exchange study methods, exam preparation strategies, and academic support', 3, NOW()),
('scholarships-financial-aid', 'Scholarships & Financial Aid', 'Information about scholarships, grants, bursaries, and funding opportunities', 4, NOW()),
('subject-selection', 'Subject Selection', 'Get advice on choosing A/L subjects, stream selection, and subject combinations', 5, NOW()),
('exam-preparation', 'Exam Preparation', 'Discuss exam strategies, past papers, revision techniques, and stress management', 6, NOW()),
('international-studies', 'International Studies', 'Information about studying abroad, international universities, and visa processes', 7, NOW()),
('student-life-wellbeing', 'Student Life & Wellbeing', 'Discuss student lifestyle, mental health, stress management, and work-life balance', 8, NOW()),
('degree-programs-courses', 'Degree Programs & Courses', 'Explore different degree programs, course content, and career prospects', 9, NOW()),
('technology-innovation', 'Technology & Innovation', 'Discuss tech careers, coding, IT programs, and emerging technologies', 10, NOW()),
('arts-humanities', 'Arts & Humanities', 'Explore opportunities in arts, literature, languages, and social sciences', 11, NOW()),
('science-engineering', 'Science & Engineering', 'Discuss STEM fields, engineering programs, and scientific research opportunities', 12, NOW()),
('business-management', 'Business & Management', 'Explore business degrees, entrepreneurship, and management career paths', 13, NOW()),
('general-discussion', 'General Discussion', 'Open discussions about student life, experiences, and general topics', 14, NOW()),
('other', 'Other', 'Topics that don\'t fit into other categories - miscellaneous discussions', 15, NOW()),

-- Alumni Categories (16-24)
('career-guidance', 'Career Guidance', 'Share career advice and professional insights with students', 16, NOW()),
('industry-insights', 'Industry Insights', 'Discuss current industry trends and opportunities', 17, NOW()),
('mentorship', 'Mentorship Opportunities', 'Connect with students and offer mentorship', 18, NOW()),
('alumni-network', 'Alumni Networking', 'Connect with fellow alumni and share experiences', 19, NOW()),
('professional-development', 'Professional Development', 'Discuss skills development and career advancement', 20, NOW()),
('job-opportunities', 'Job Opportunities', 'Share and discuss job openings and career opportunities', 21, NOW()),
('university-life', 'University Life & Advice', 'Share insights about university experience', 22, NOW()),
('alumni-general', 'Alumni General Discussion', 'Open discussion for alumni community', 23, NOW()),
('alumni-other', 'Alumni Other', 'Miscellaneous topics for alumni', 24, NOW());