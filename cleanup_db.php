<?php
try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=aihrm', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if tables exist before dropping
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (in_array('shifts', $tables)) {
        $db->exec('DROP TABLE shifts');
        echo "Dropped shifts table.\n";
    }
    
    if (in_array('office_locations', $tables)) {
        $db->exec('DROP TABLE office_locations');
        echo "Dropped office_locations table.\n";
    }
    
    // Also check if columns were added to users table
    $columns = $db->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_COLUMN);
    if (in_array('shift_id', $columns)) {
        $db->exec('ALTER TABLE users DROP FOREIGN KEY users_shift_id_foreign');
        $db->exec('ALTER TABLE users DROP COLUMN shift_id');
        echo "Dropped shift_id column from users.\n";
    }
    if (in_array('office_location_id', $columns)) {
        $db->exec('ALTER TABLE users DROP FOREIGN KEY users_office_location_id_foreign');
        $db->exec('ALTER TABLE users DROP COLUMN office_location_id');
        echo "Dropped office_location_id column from users.\n";
    }

    echo "Cleanup complete.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
