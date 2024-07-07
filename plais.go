package main

import (
	"database/sql"
	"fmt"

	_ "github.com/go-sql-driver/mysql"
)

func main() {
	db, err := sql.Open("mysql", "your_php_username:your_php_password@tcp(your_php_host:3306)/your_php_database")
	if err != nil {
		fmt.Println(err)
		return
	}
	defer db.Close()

	settings := map[string]string{
		"bad_words": "badword1,badword2,...",
		"ads_list": "ad1,ad2,...",
	}

	for key, value := range settings {
		_, err = db.Exec("INSERT INTO settings (key, value) VALUES (?, ?)", key, value)
		if err != nil {
			fmt.Println(err)
			return
		}
	}
}