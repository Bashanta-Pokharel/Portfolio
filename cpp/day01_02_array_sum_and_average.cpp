/**
 * Day 1 - Problem 2: Array Sum, Average, Min, and Max
 * Language: C++17/20
 * Topic: STL Algorithms & Iteration
 */

#include <iostream>
#include <vector>
#include <algorithm>
#include <numeric>
#include <iomanip>

int main() {
    std::vector<double> numbers = {14.5, 23.0, 7.8, 99.2, 45.1, 18.9, 62.4};

    std::cout << "========================================\n";
    std::cout << " Day 1: C++ Array Analytics Demo \n";
    std::cout << "========================================\n";

    double sum = std::accumulate(numbers.begin(), numbers.end(), 0.0);
    double average = sum / static_cast<double>(numbers.size());
    auto minElement = std::min_element(numbers.begin(), numbers.end());
    auto maxElement = std::max_element(numbers.begin(), numbers.end());

    std::cout << std::fixed << std::setprecision(2);
    std::cout << "Elements count: " << numbers.size() << "\n";
    std::cout << "Total Sum:      " << sum << "\n";
    std::cout << "Average:        " << average << "\n";
    if (minElement != numbers.end()) std::cout << "Minimum Value:  " << *minElement << "\n";
    if (maxElement != numbers.end()) std::cout << "Maximum Value:  " << *maxElement << "\n";

    return 0;
}
