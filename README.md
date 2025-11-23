# AI HR Management System (AIHRM)

![Version](https://img.shields.io/badge/version-1.0.0--MVP-blue)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-purple)
![License](https://img.shields.io/badge/license-MIT-green)

A comprehensive, self-hosted HR Management System built with Laravel, designed for small to medium enterprises. Features a minimalist black & white UI and includes AI-powered capabilities for enhanced HR operations.

## 🎯 Features

### Core Modules (MVP)

#### 👥 Employee Management
- Complete employee profiles with personal information
- Department and designation management
- Document upload and management
- Organizational structure tracking
- Employee status management

#### 🏖️ Leave Management
- Multiple leave types (Annual, Sick, Maternity, Paternity, Casual)
- Leave request submission with date validation
- Approval workflow (Pending → Approved/Rejected)
- Leave balance tracking
- Leave history and reporting

#### 💰 Financial Requests
- Expense claim submission
- Multiple request categories (Travel, Supplies, Training, etc.)
- Receipt/invoice upload support
- Multi-level approval workflow (Manager → Finance)
- Payment status tracking

#### 🔐 Authentication & Security
- Laravel Breeze authentication
- Role-based access control (Admin, HR, Manager, Employee)
- Secure password hashing
- CSRF protection
- Session management

## 🎨 Design Philosophy

- **Minimalist Black & White UI**: Clean, professional interface without gradients
- **High Contrast**: Ensures readability and accessibility
- **Consistent Design Language**: Uniform components across all modules
- **Mobile Responsive**: Works seamlessly on desktop, tablet, and mobile devices

## 🚀 Installation

### Prerequisites

- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Node.js & NPM
- Web server (Apache/Nginx) or XAMPP/WAMP

### Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/rukibhamz/aihrm.git
   cd aihrm/platform
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   npm run build
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Create database**
   - Open phpMyAdmin or MySQL CLI
   - Create a database named `aihrm`
   ```sql
   CREATE DATABASE aihrm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

6. **Update .env file**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=aihrm
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

7. **Run migrations and seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

8. **Create storage link**
   ```bash
   php artisan storage:link
   ```

9. **Start development server**
   ```bash
   php artisan serve
   ```

10. **Access the application**
    - URL: http://localhost:8000
    - Default Admin: admin@aihrm.com
    - Password: password

## 📁 Project Structure

```
aihrm/
├── laravel_core/           # Main Laravel application
│   ├── app/
│   │   ├── Http/
│   │   │   └── Controllers/
│   │   │       ├── EmployeeController.php
│   │   │       ├── LeaveController.php
│   │   │       └── FinancialRequestController.php
│   │   └── Models/
│   │       ├── Employee.php
│   │       ├── Department.php
│   │       ├── LeaveRequest.php
│   │       ├── LeaveType.php
│   │       └── FinancialRequest.php
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── resources/
│   │   └── views/
│   │       ├── employees/
│   │       ├── leaves/
│   │       ├── finance/
│   │       └── layouts/
│   └── routes/
│       └── web.php
├── setup.php               # Nextcloud-style installer
├── install.php             # Installation wizard
└── README.md
```

## 🔧 Configuration

### Email Configuration

Update your `.env` file with SMTP settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@aihrm.com
MAIL_FROM_NAME="${APP_NAME}"
```

### File Storage

The application uses Laravel's storage system. Uploaded files are stored in:
- Employee photos: `storage/app/public/employee_photos/`
- Financial attachments: `storage/app/public/financial_attachments/`

## 👤 Default Users

After seeding, the following user is created:

| Email | Password | Role |
|-------|----------|------|
| admin@aihrm.com | password | Admin |

**⚠️ Important:** Change the default password immediately after first login.

## 📊 Database Schema

### Key Tables

- `users` - User accounts and authentication
- `employees` - Employee profiles
- `departments` - Department structure
- `designations` - Job titles and grades
- `leave_types` - Leave type configurations
- `leave_requests` - Leave applications
- `leave_balances` - Leave balance tracking
- `financial_requests` - Expense claims
- `financial_request_categories` - Expense categories
- `documents` - Employee document storage

## 🛠️ Development

### Running Tests

```bash
php artisan test
```

### Code Style

```bash
./vendor/bin/pint
```

### Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 📝 Usage Guide

### Adding an Employee

1. Navigate to **Employees** → **Add Employee**
2. Fill in employee details (First Name, Last Name, Email, etc.)
3. Select Department and Designation
4. Optionally upload a profile photo
5. Click **Create Employee**
6. Default password is "password" - employee should change on first login

### Requesting Leave

1. Navigate to **Leave** → **Request Leave**
2. Select Leave Type
3. Choose Start and End dates
4. Enter reason for leave
5. Click **Submit Request**
6. Wait for manager approval

### Submitting Financial Request

1. Navigate to **Finance** → **New Request**
2. Select Category (Travel, Supplies, etc.)
3. Enter Amount and Description
4. Upload receipt/invoice (optional)
5. Click **Submit Request**
6. Track approval status

## 🚧 Roadmap

### Phase 2: Logic & Polish (Weeks 3-4)
- [ ] Real-time dashboard widgets
- [ ] Email notifications for approvals
- [ ] Leave balance calculation
- [ ] Approval workflows
- [ ] Role-based permissions (Spatie)

### Phase 3: Production Readiness (Week 5)
- [ ] Security hardening
- [ ] Performance optimization
- [ ] Backup strategy
- [ ] Production deployment guide

### Phase 4: Advanced Features
- [ ] Performance management & appraisals
- [ ] Payroll processing
- [ ] Recruitment & applicant tracking
- [ ] AI resume screening
- [ ] Attendance tracking
- [ ] Training management

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- UI powered by [Tailwind CSS](https://tailwindcss.com)
- Authentication via [Laravel Breeze](https://laravel.com/docs/starter-kits)

## 📞 Support

For support, email support@aihrm.com or open an issue on GitHub.

## 🔒 Security

If you discover any security-related issues, please email security@aihrm.com instead of using the issue tracker.

---

**Made with ❤️ for HR Teams**
