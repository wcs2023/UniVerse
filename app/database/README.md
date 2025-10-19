# 🗄️ UniVerse Database Setup

## Quick Start (Choose One Method)

### Method 1: phpMyAdmin (Easiest) ⭐
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click **Import** tab
3. Choose file: `00_complete_schema.sql`
4. Click **Go** at the bottom
5. ✅ Done! (Optionally import `01_sample_data.sql` for test accounts)

### Method 2: Command Line
```powershell
# Navigate to database folder
cd C:\xampp\htdocs\UniVerse\app\database

# Import schema
Get-Content "00_complete_schema.sql" -Raw | & "C:\xampp\mysql\bin\mysql.exe" -u root

# Optional: Import sample data
Get-Content "01_sample_data.sql" -Raw | & "C:\xampp\mysql\bin\mysql.exe" -u root
```

### Method 3: One-Line Setup
```powershell
cd C:\xampp\htdocs\UniVerse\app\database; Get-Content "00_complete_schema.sql" -Raw | & "C:\xampp\mysql\bin\mysql.exe" -u root; Get-Content "01_sample_data.sql" -Raw | & "C:\xampp\mysql\bin\mysql.exe" -u root
```

---

## 📦 What's Included

### File 1: `00_complete_schema.sql` 
**Complete database structure** - All 16 tables:
- ✅ Users & Authentication
- ✅ Alumni Profiles (with Experience & Skills)
- ✅ Undergraduate Profiles
- ✅ Articles System
- ✅ Mentorship System (Requests, Sessions, Slots)
- ✅ Notifications & Messages
- ✅ Feedback System

### File 2: `01_sample_data.sql` (Optional)
**Test data** for immediate testing:
- 3 user accounts (Alumni, Student, Admin)
- 1 complete alumni profile with skills and experience
- 3 sample articles (2 published, 1 draft)
- 1 mentorship request
- Sample notifications

---

## 🔐 Test Accounts

If you imported `01_sample_data.sql`:

| Role | Email | Password | Description |
|------|-------|----------|-------------|
| Alumni | test.alumni@example.com | password | John Doe - Senior Engineer |
| Student | test.student@example.com | password | Jane Smith - CS Student |
| Admin | admin@universe.com | password | Admin User |

---

## 📊 Database Structure

```
Users (Central authentication)
├── Alumni (Extended profile)
│   ├── AlumniExperience (Work history)
│   └── AlumniSkills (Skills list)
├── Undergraduates (Student profile)
└── Articles (Alumni articles)

Mentorship System
├── Mentorships (Simple requests)
├── MentorshipTimeSlots (Time slots)
├── Mentor_Profiles (Extended mentor data)
├── Mentee_Profiles (Extended mentee data)
├── Mentorship_Requests (Advanced requests)
├── Proposed_Slots (Time slot proposals)
├── Mentorship_Sessions (Confirmed sessions)
└── Feedback (Session feedback)

Communication
├── Notifications (System notifications)
└── Messages (Direct messaging)
```

---

## ✨ Benefits of New Structure

| Before | After |
|--------|-------|
| 5 separate SQL files | 2 organized files |
| Manual column additions | Everything in one place |
| Possible conflicts | No duplicate definitions |
| Complex setup | One-click import |
| Hard to maintain | Single source of truth |

---

## 🔧 Troubleshooting

### Error: "Table already exists"
```sql
-- Drop the database and start fresh
DROP DATABASE my_db;
```
Then import `00_complete_schema.sql` again.

### Error: "Access denied"
Make sure XAMPP MySQL is running and you're using the correct credentials (default: root with no password).

### Error: "Database not found"
The schema file creates the database automatically. Just import it directly.

---

## 🎯 Next Steps

1. ✅ Import `00_complete_schema.sql`
2. ✅ Import `01_sample_data.sql` (optional)
3. ✅ Login with test accounts
4. ✅ Start building features!

---
