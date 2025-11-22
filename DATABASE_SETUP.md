# Database Setup Instructions

Before running migrations, you need to:

1. **Create a MySQL database** named `aihrm`:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Click "New" to create a database
   - Name it: `aihrm`
   - Collation: `utf8mb4_unicode_ci`

2. **Configure .env file**:
   The `.env` file is already created. Make sure these settings are correct:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=aihrm
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Run migrations**:
   ```bash
   cd c:\xampp\htdocs\aihrm\laravel_core
   c:\xampp\php\php.exe artisan migrate:fresh --seed
   ```

This will create all tables and populate them with sample data.
