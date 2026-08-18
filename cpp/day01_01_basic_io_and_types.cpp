/**
 * Day 1 - Problem 1: Modern C++ I/O, Strings and Vector Basics
 * Language: C++17/20
 * Topic: Streams, Vector Container & Auto Type Deduction
 */

#include <iostream>
#include <string>
#include <vector>
#include <numeric>

int main() {
    std::cout << "========================================\n";
    std::cout << " Welcome to Modern C++ Basics (Day 1) \n";
    std::cout << "========================================\n";

    std::string developer = "Bashanta";
    auto versionYear = 2026;
    double experienceRating = 9.8;

    std::cout << "Developer: " << developer << "\n";
    std::cout << "Year: " << versionYear << "\n";
    std::cout << "Rating: " << experienceRating << " / 10.0\n\n";

    // STL Vector Demonstration
    std::vector<int> scores = {85, 92, 78, 96, 88};
    std::cout << "Scores: ";
    for (int score : scores) {
        std::cout << score << " ";
    }
    std::cout << "\n";

    return 0;
}
