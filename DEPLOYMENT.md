# 🚀 Production Deployment & Maintenance Guide

This guide outlines the steps to ensure **AIHRM** is secure, performant, and reliable in a production environment.

## 🛡️ 1. Security Hardening
- **SSL/HTTPS**: Ensure your web server (Nginx/Apache) has a valid SSL certificate (e.g., Let's Encrypt).
- **Environment Variables**: Set `APP_ENV=production` and `APP_DEBUG=false` in your `.env` file.
- **Security Headers**: The system includes a `SecurityHeaders` middleware that automatically enforces CSP, HSTS, and XSS protection.
- **Rate Limiting**: API and Auth routes are protected by a custom throttle (`CustomThrottleRequests`).

## 💾 2. Automated Backups
I have implemented a custom backup command to protect your data.
- **Manual Backup**:
  ```bash
  php artisan app:system-backup
  ```
- **Scheduled Backup**: Add this to your server's crontab (e.g., daily at 2 AM):
  ```cron
  0 2 * * * cd /path/to/aihrm/platform && php artisan app:system-backup >> /dev/null 2>&1
  ```
Backups are stored in `platform/storage/app/backups/`.

## 💸 3. Advanced Payroll
- **PDF Payslips**: Employees can now download their monthly payslips as professional black & white PDFs directly from their dashboard.
- **DomPDF**: Ensure the `storage/fonts` directory is writable for PDF generation.

## ⚡ 4. Optimization
Run these commands after every deployment:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

## 📈 5. Maintenance
- **Audit Logs**: Regularly check the "Audit Logs" in the Admin panel to monitor system changes.
- **AI Token Usage**: Monitor your Gemini API usage to ensure no interruptions in AI services.

---
**AIHRM - Empowering Human Resources with Intelligence**
