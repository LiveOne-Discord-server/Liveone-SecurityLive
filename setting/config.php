<?php
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS reports (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    reason VARCHAR(255),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Reports table created successfully";
} else {
    echo "Error creating reports table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS settings (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key VARCHAR(255),
    value VARCHAR(255)
)";

if ($conn->query($sql) === TRUE) {
    echo "Settings table created successfully";
} else {
    echo "Error creating settings table: " . $conn->error;
}

$sql = "INSERT INTO settings (key, value) VALUES ('bad_words', 'badword1,badword2,...'), ('ads_list', 'ad1,ad2,...')";
if ($conn->query($sql) === TRUE) {
    echo "Default settings inserted successfully";
} else {
    echo "Error inserting default settings: " . $conn->error;
}

$conn->close();
?>