/**
 * Day 1 - Problem 1: Basic I/O, Data Types, and Memory Footprint
 * Language: C (C99/C11)
 * Topic: Syntax, Variables, Format Specifiers
 */

#include <stdio.h>

int main(void) {
    printf("========================================\n");
    printf(" Welcome to C Programming Basics (Day 1)\n");
    printf("========================================\n");

    // Variable definitions
    int integerVar = 42;
    float floatVar = 3.14159f;
    double doubleVar = 123456.789;
    char charVar = 'A';

    // Output with format specifiers
    printf("Integer: %d (Size: %zu bytes)\n", integerVar, sizeof(integerVar));
    printf("Float:   %.4f (Size: %zu bytes)\n", floatVar, sizeof(floatVar));
    printf("Double:  %.3lf (Size: %zu bytes)\n", doubleVar, sizeof(doubleVar));
    printf("Char:    '%c' (ASCII: %d, Size: %zu byte)\n", charVar, (int)charVar, sizeof(charVar));

    return 0;
}
