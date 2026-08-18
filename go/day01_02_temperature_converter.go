// Day 1 - Problem 2: Multi-scale Temperature Converter
// Language: Go 1.20+
// Topic: Functions, Return Values, and Float Arithmetic

package main

import "fmt"

func celsiusToFahrenheit(c float64) float64 {
	return (c * 9.0 / 5.0) + 32.0
}

func celsiusToKelvin(c float64) float64 {
	return c + 273.15
}

func convertAll(celsius float64) (fahrenheit float64, kelvin float64) {
	return celsiusToFahrenheit(celsius), celsiusToKelvin(celsius)
}

func main() {
	sampleTemps := []float64{-40.0, 0.0, 25.0, 37.0, 100.0}

	fmt.Println("========================================")
	fmt.Println(" Day 1: Temperature Converter in Go ")
	fmt.Println("========================================")

	for _, temp := range sampleTemps {
		f, k := convertAll(temp)
		fmt.Printf("Celsius: %6.1f°C  ->  Fahrenheit: %6.1f°F  |  Kelvin: %6.2fK\n", temp, f, k)
	}
}
