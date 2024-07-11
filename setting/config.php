<?php
$config = [
    'db_host' => 'localhost',
    'db_username' => 'your_username',
    'db_password' => 'your_password',
    'db_name' => 'your_database',
    'update_interval' => 86400 
];

function connectDB($config) {
    try {
        $conn = new mysqli($config['db_host'], $config['db_username'], $config['db_password'], $config['db_name']);
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    } catch (Exception $e) {
        error_log("Database connection error: " . $e->getMessage());
        die("A database error occurred. Please try again later.");
    }
}
function createTable($conn, $tableName, $tableSQL) {
    try {
        if ($conn->query($tableSQL) !== TRUE) {
            throw new Exception("Error creating {$tableName} table: " . $conn->error);
        }
        echo "{$tableName} table created or already exists.<br>";
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo "An error occurred while creating the {$tableName} table. Please check the error log.<br>";
    }
}
function insertDefaultSettings($conn) {
    $defaultSettings = [
        ['bad_words', 'badword1,badword2,...'],
        ['ads_list', 'ad1,ad2,...']
    ];

    $stmt = $conn->prepare("INSERT INTO settings (key, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = VALUES(value)");
    
    foreach ($defaultSettings as $setting) {
        $stmt->bind_param("ss", $setting[0], $setting[1]);
        if (!$stmt->execute()) {
            error_log("Error inserting default setting: " . $stmt->error);
        }
    }
    
    $stmt->close();
    echo "Default settings updated.<br>";
}
function performDailyUpdate($conn, $config) {
    $lastUpdateKey = 'last_update_timestamp';
    $currentTime = time();

    $stmt = $conn->prepare("SELECT value FROM settings WHERE key = ?");
    $stmt->bind_param("s", $lastUpdateKey);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastUpdate = intval($row['value']);
        if (($currentTime - $lastUpdate) >= $config['update_interval']) {
            updateDatabase($conn);
            updateLastUpdateTimestamp($conn, $lastUpdateKey, $currentTime);
        }
    } else {
        updateDatabase($conn);
        updateLastUpdateTimestamp($conn, $lastUpdateKey, $currentTime);
    }

    $stmt->close();
}

function updateDatabase($conn) {
    echo "Performing daily database update...<br>";
    $conn->query("DELETE FROM reports WHERE timestamp < DATE_SUB(NOW(), INTERVAL 30 DAY)");
}

function updateLastUpdateTimestamp($conn, $key, $timestamp) {
    $stmt = $conn->prepare("INSERT INTO settings (key, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
    $stmt->bind_param("sss", $key, $timestamp, $timestamp);
    $stmt->execute();
    $stmt->close();
}
try {
    $conn = connectDB($config);

    $reportsTableSQL = "CREATE TABLE IF NOT EXISTS reports (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED,
        reason VARCHAR(255),
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    createTable($conn, 'Reports', $reportsTableSQL);

    $settingsTableSQL = "CREATE TABLE IF NOT EXISTS settings (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `key` VARCHAR(255) UNIQUE,
        value TEXT
    )";
    createTable($conn, 'Settings', $settingsTableSQL);

    insertDefaultSettings($conn);

    performDailyUpdate($conn, $config);

    $conn->close();
    echo "Script executed successfully.";
} catch (Exception $e) {
    error_log("Unexpected error: " . $e->getMessage());
    echo "An unexpected error occurred. Please check the error log.";
}
?>