package main

import (
    "database/sql"
    "fmt"
    "log"
    "os"

    _ "github.com/go-sql-driver/mysql"
)

func main() {
    logFile, err := os.OpenFile("app.log", os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
    if err != nil {
        log.Fatalf("Failed to open log file: %v", err)
    }
    defer logFile.Close()
    log.SetOutput(logFile)
    db, err := sql.Open("mysql", "your_php_username:your_php_password@tcp(your_php_host:3306)/your_php_database")
    if err != nil {
        log.Fatalf("Failed to open database connection: %v", err)
    }
    defer db.Close()

    err = db.Ping()
    if err != nil {
        log.Fatalf("Failed to ping database: %v", err)
    }

    settings := map[string]string{
        "bad_words": "badword1,badword2,...",
        "ads_list":  "ad1,ad2,...",
    }

    tx, err := db.Begin()
    if err != nil {
        log.Fatalf("Failed to start transaction: %v", err)
    }

    for key, value := range settings {
        _, err = tx.Exec("INSERT INTO settings (key, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?", key, value, value)
        if err != nil {
            tx.Rollback()
            log.Fatalf("Failed to insert/update setting %s: %v", key, err)
        }
    }

    err = tx.Commit()
    if err != nil {
        log.Fatalf("Failed to commit transaction: %v", err)
    }

    fmt.Println("Settings updated successfully")
}