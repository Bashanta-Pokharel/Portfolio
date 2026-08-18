/**
 * Day 1 - Problem 2: Even or Odd Checker (Modulo vs Bitwise AND)
 * Language: C (C99/C11)
 * Topic: Conditionals & Bitwise Operations
 */

#include <stdio.h>
#include <stdbool.h>

bool isEvenModulo(int num) {
    return (num % 2) == 0;
}

bool isEvenBitwise(int num) {
    // Fast check: last bit is 0 for even numbers, 1 for odd numbers
    return (num & 1) == 0;
}

int main(void) {
    int testNumbers[] = {0, 1, 2, 7, 14, 25, 100, -3, -8};
    size_t count = sizeof(testNumbers) / sizeof(testNumbers[0]);

    printf("========================================\n");
    printf(" Day 1: Even/Odd Check in C \n");
    printf("========================================\n");

    for (size_t i = 0; i < count; i++) {
        int n = testNumbers[i];
        printf("Number %4d -> %s (Bitwise check confirms: %s)\n",
               n,
               isEvenModulo(n) ? "EVEN" : "ODD ",
               isEvenBitwise(n) ? "EVEN" : "ODD ");
    }

    return 0;
}
