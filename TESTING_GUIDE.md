# Testing Guide - AI HR Management System

## Pre-Installation Checklist

1. **XAMPP Running**
   - Apache: Started
   - MySQL: Started

2. **Create Database**
   - Open: http://localhost/phpmyadmin
   - Click "New"
   - Database name: `aihrm`
   - Collation: `utf8mb4_unicode_ci`
   - Click "Create"

## Installation Test

### Step 1: Access Installer
- URL: `http://localhost/aihrm/install.php`
- Expected: Installation wizard appears

### Step 2: Database Configuration
- Fill in:
  - Host: `127.0.0.1`
  - Database: `aihrm`
  - Username: `root`
  - Password: (leave blank if default XAMPP)
- Click "Next"
- Expected: "Database connected and configured!" message

### Step 3: Auto-Migration
- Click "Run Migrations"
- Expected:
  - ✓ Database tables created
  - ✓ Sample data seeded
  - ✓ Roles and permissions configured
- Time: ~10-30 seconds

### Step 4: Admin Setup
- Default credentials shown:
  - Email: admin@aihrm.com
  - Password: password
- Click "Finish Installation"
- Expected: "Installation Complete!" message

### Step 5: First Login
- URL: `http://localhost/aihrm/platform/public`
- Login with:
  - Email: `admin@aihrm.com`
  - Password: `password`
- Expected: Dashboard appears

## Feature Testing

### Test 1: Employee Creation (Admin/HR)
1. Navigate to **Employees** → **Add Employee**
2. Fill in:
   - First Name: John
   - Last Name: Doe
   - Email: john.doe@test.com
   - Department: Engineering
   - Designation: Software Engineer
3. Click "Create Employee"
4. Expected: Success message, redirects to employee list

### Test 2: Leave Request (Employee)
1. Navigate to **Leave** → **Request Leave**
2. Fill in:
   - Leave Type: Annual Leave
   - Start Date: (tomorrow)
   - End Date: (day after tomorrow)
   - Reason: Personal
3. Click "Submit Request"
4. Expected: Success message, status shows "Pending"

### Test 3: Leave Approval (Manager/HR)
1. Navigate to **Approvals**
2. Find the pending leave request
3. Click "Approve"
4. Expected: Status changes to "Approved"

### Test 4: Financial Request (Employee)
1. Navigate to **Finance** → **New Request**
2. Fill in:
   - Category: Travel
   - Amount: 5000
   - Description: Conference travel
3. Click "Submit Request"
4. Expected: Success message, status shows "Pending"

### Test 5: Multi-Level Financial Approval
1. **As Manager**: Navigate to **Finance** → **Approvals**
   - Click "Approve (Mgr)"
   - Expected: Status → "Approved Manager"
2. **As Finance**: Navigate to **Finance** → **Approvals**
   - Click "Approve (Fin)"
   - Expected: Status → "Approved Finance"
3. **As Finance**: Click "Mark Paid"
   - Expected: Status → "Paid"

## Permission Testing

### Test 6: Role-Based Access
1. **Create Employee User**:
   - Add employee with email: employee@test.com
   - Default password: password
2. **Logout and Login as Employee**
3. **Verify Restrictions**:
   - ✓ Can view own leaves
   - ✓ Can create leave request
   - ✓ Can create financial request
   - ✗ Cannot see "Employees" menu
   - ✗ Cannot see "Approvals" menu

## Common Issues & Solutions

### Issue: "Connection failed"
- **Solution**: Check database name, username, password
- Verify MySQL is running in XAMPP

### Issue: "Class not found"
- **Solution**: Run `composer install` in laravel_core folder
- Check vendor folder exists

### Issue: 403 Forbidden
- **Solution**: User doesn't have permission
- Verify role assignment in database

### Issue: Blank page after login
- **Solution**: Check storage/logs/laravel.log
- Run `php artisan config:clear`

## Quick Commands (If Needed)

```bash
cd c:\xampp\htdocs\aihrm\laravel_core

# Clear cache
c:\xampp\php\php.exe artisan config:clear
c:\xampp\php\php.exe artisan cache:clear

# Re-run migrations (WARNING: Deletes data)
c:\xampp\php\php.exe artisan migrate:fresh --seed

# Start dev server (alternative to XAMPP)
c:\xampp\php\php.exe artisan serve
# Then visit: http://localhost:8000
```

## Success Criteria

✅ Installation completes in < 2 minutes
✅ All 5 roles created (Admin, HR, Manager, Finance, Employee)
✅ Sample data visible (Departments, Leave Types)
✅ Can create employee
✅ Can submit and approve leave
✅ Can submit and approve financial request (multi-level)
✅ Permissions work correctly

## Next Steps After Testing

If all tests pass:
1. Change admin password
2. Create real departments
3. Add real employees
4. Configure email settings (optional)
5. Set up backups

Good luck with testing! 🚀
