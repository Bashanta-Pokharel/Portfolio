// Day 1 - Problem 1: Go Packages, Variables, and Structs
// Language: Go 1.20+
// Topic: Basic Types, Formats, and Struct Declarations

package main

import (
	"fmt"
	"time"
)

type Developer struct {
	Name      string
	Language  string
	StartDate time.Time
}

func main() {
	fmt.Println("========================================")
	fmt.Println(" Welcome to Go Programming (Day 1) ")
	fmt.Println("========================================")

	var message string = "Learning Go from scratch!"
	daysCount := 1
	rating := 4.9

	fmt.Printf("Status: %s\n", message)
	fmt.Printf("Day: %d | Rating: %.1f/5.0\n\n", daysCount, rating)

	dev := Developer{
		Name:      "Bashanta",
		Language:  "Go (Golang)",
		StartDate: time.Now(),
	}

	fmt.Printf("Developer Profile: %+v\n", dev)
}
