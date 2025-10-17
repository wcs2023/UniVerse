# Database Setup Instructions

## Error: Unknown database 'my_db'

This error occurs because the database hasn't been created yet. Follow the steps below to set up your database.

## Option 1: Using phpMyAdmin (Recommended for beginners)

1. **Start XAMPP** and ensure MySQL is running
2. **Open phpMyAdmin** in your browser: http://localhost/phpmyadmin
3. **Create the database:**
   - Click on "New" in the left sidebar
   - Enter database name: `my_db`
   - Select collation: `utf8mb4_unicode_ci`
   - Click "Create"
4. **Import the schema:**
   - Select the `my_db` database from the left sidebar
   - Click on the "Import" tab at the top
   - Click "Choose File" and navigate to:
     `C:\xampp\htdocs\UniVerse\app\database\mentorship_complete_schema.sql`
   - Click "Go" at the bottom
   - Wait for success message
5. **Import sample data (Optional):**
   - Still in the Import tab
   - Choose file: `mentorship_dummy_data.sql`
   - Click "Go"

## Option 2: Using MySQL Command Line

1. **Open Command Prompt** or PowerShell
2. **Navigate to MySQL bin directory:**
   ```powershell
   cd C:\xampp\mysql\bin
   ```
3. **Login to MySQL:**
   ```powershell
   .\mysql.exe -u root -p
   ```
   (Press Enter when prompted for password if you haven't set one)
4. **Create the database:**
   ```sql
   CREATE DATABASE IF NOT EXISTS my_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE my_db;
   ```
5. **Import the schema:**
   ```sql
   SOURCE C:/xampp/htdocs/UniVerse/app/database/mentorship_complete_schema.sql
   ```
6. **Import sample data (Optional):**
   ```sql
   SOURCE C:/xampp/htdocs/UniVerse/app/database/mentorship_dummy_data.sql
   ```
7. **Exit MySQL:**
   ```sql
   EXIT;
   ```

## Option 3: Quick PowerShell Script

Run this command in PowerShell (from the project root):

```powershell
cd C:\xampp\mysql\bin
.\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS my_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
.\mysql.exe -u root my_db < C:\xampp\htdocs\UniVerse\app\database\mentorship_complete_schema.sql
```

## Verify Setup

After setup, verify the database was created correctly:

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click on `my_db` in the left sidebar
3. You should see these tables:
   - Users
   - Mentor_Profiles
   - Mentee_Profiles
   - Mentorship_Requests
   - Proposed_Slots
   - Mentorship_Sessions
   - Feedback
   - Notifications
   - Messages

## Troubleshooting

### Error: Access denied
- Make sure XAMPP MySQL is running
- Default credentials are: username=`root`, password=`` (empty)

### Error: File not found
- Check that the SQL file path is correct
- Use forward slashes (/) or double backslashes (\\\\) in paths

### Error: Table already exists
- This is OK - it means tables were already created
- You can drop and recreate: 
  - In phpMyAdmin, select `my_db`, click "Operations" tab, scroll down and click "Drop the database"
  - Then repeat the setup steps

## Database Configuration

The database configuration is set in: `app/core/config.php`

Current settings:
```php
define('DBHOST', 'localhost');
define('DBNAME', 'my_db');
define('DBUSER', 'root');
define('DBPASS', '');
```

If you want to use a different database name, update `DBNAME` in the config file.

## Next Steps

After setting up the database:
1. Refresh your browser page
2. The "Unknown database" error should be resolved
3. You can start using the application!
