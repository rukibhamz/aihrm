# Database Backup Strategy

## Automated Backup Configuration

### For Production (Linux/Unix)

**1. Create backup script:**
```bash
#!/bin/bash
# File: /home/scripts/backup-aihrm-db.sh

# Configuration
DB_NAME="aihrm"
DB_USER="your_db_user"
DB_PASS="your_db_password"
BACKUP_DIR="/home/backups/aihrm"
DATE=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=30

# Create backup directory if it doesn't exist
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/aihrm_$DATE.sql.gz

# Delete backups older than retention period
find $BACKUP_DIR -name "aihrm_*.sql.gz" -mtime +$RETENTION_DAYS -delete

# Log backup
echo "$(date): Backup completed - aihrm_$DATE.sql.gz" >> $BACKUP_DIR/backup.log
```

**2. Make script executable:**
```bash
chmod +x /home/scripts/backup-aihrm-db.sh
```

**3. Add to crontab (daily at 2 AM):**
```bash
crontab -e
# Add this line:
0 2 * * * /home/scripts/backup-aihrm-db.sh
```

---

### For Windows (XAMPP)

**1. Create backup script:**
```batch
@echo off
REM File: C:\backup-scripts\backup-aihrm.bat

SET DB_NAME=aihrm
SET DB_USER=root
SET DB_PASS=
SET BACKUP_DIR=C:\aihrm-backups
SET MYSQL_PATH=C:\xampp\mysql\bin
SET DATE=%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%
SET DATE=%DATE: =0%

REM Create backup directory
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

REM Backup database
"%MYSQL_PATH%\mysqldump.exe" -u%DB_USER% -p%DB_PASS% %DB_NAME% > "%BACKUP_DIR%\aihrm_%DATE%.sql"

REM Compress with 7-Zip (if installed)
if exist "C:\Program Files\7-Zip\7z.exe" (
    "C:\Program Files\7-Zip\7z.exe" a -tgzip "%BACKUP_DIR%\aihrm_%DATE%.sql.gz" "%BACKUP_DIR%\aihrm_%DATE%.sql"
    del "%BACKUP_DIR%\aihrm_%DATE%.sql"
)

echo %date% %time%: Backup completed >> "%BACKUP_DIR%\backup.log"
```

**2. Schedule with Task Scheduler:**
- Open Task Scheduler
- Create Basic Task
- Name: "AIHRM Database Backup"
- Trigger: Daily at 2:00 AM
- Action: Start a program
- Program: `C:\backup-scripts\backup-aihrm.bat`

---

### Laravel Artisan Command (Cross-Platform)

**Create backup command:**
```bash
php artisan make:command BackupDatabase
```

**File: `app/Console/Commands/BackupDatabase.php`:**
```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup the database';

    public function handle()
    {
        $filename = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        
        // Create backups directory
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        // Backup command
        $command = sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $path
        );

        exec($command);

        $this->info('Database backup created: ' . $filename);
        
        // Delete old backups (keep last 30 days)
        $this->deleteOldBackups();
    }

    private function deleteOldBackups()
    {
        $files = glob(storage_path('app/backups/backup-*.sql'));
        $now = time();

        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 30 * 24 * 60 * 60) {
                    unlink($file);
                }
            }
        }
    }
}
```

**Schedule in `app/Console/Kernel.php`:**
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('db:backup')->daily()->at('02:00');
}
```

---

## Cloud Backup (Recommended for Production)

### AWS S3 Backup
```bash
# Install AWS CLI
# Configure: aws configure

# Add to backup script:
aws s3 cp $BACKUP_DIR/aihrm_$DATE.sql.gz s3://your-bucket/aihrm-backups/
```

### Google Cloud Storage
```bash
# Install gsutil
# Configure: gcloud init

# Add to backup script:
gsutil cp $BACKUP_DIR/aihrm_$DATE.sql.gz gs://your-bucket/aihrm-backups/
```

---

## Restore Procedure

### From Backup File
```bash
# Decompress
gunzip aihrm_20250124_020000.sql.gz

# Restore
mysql -u root -p aihrm < aihrm_20250124_020000.sql
```

### Laravel Command
```bash
php artisan db:restore backup-2025-01-24-02-00-00.sql
```

---

## Backup Checklist

- [ ] Automated daily backups configured
- [ ] Backups stored in secure location
- [ ] Off-site/cloud backup enabled
- [ ] Backup retention policy set (30 days)
- [ ] Restore procedure tested
- [ ] Backup monitoring/alerts configured
- [ ] Backup logs reviewed regularly

---

## Testing Backups

**Monthly Test:**
1. Download latest backup
2. Restore to test database
3. Verify data integrity
4. Document results
