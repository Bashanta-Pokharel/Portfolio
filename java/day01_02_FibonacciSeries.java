/**
 * Day 1 - Problem 2: Generating Fibonacci Sequence (Iterative & Dynamic)
 * Language: Java 17+
 * Topic: Loops, Arrays & Logic Building
 */

public class day01_02_FibonacciSeries {
    
    /**
     * Generates and returns an array of the first n Fibonacci numbers.
     */
    public static long[] generateFibonacci(int n) {
        if (n <= 0) return new long[0];
        long[] fib = new long[n];
        fib[0] = 0;
        if (n > 1) {
            fib[1] = 1;
            for (int i = 2; i < n; i++) {
                fib[i] = fib[i - 1] + fib[i - 2];
            }
        }
        return fib;
    }

    public static void main(String[] args) {
        int terms = 15;
        System.out.println("========================================");
        System.out.println(" Day 1: First " + terms + " Fibonacci Numbers (Java)");
        System.out.println("========================================");

        long[] results = generateFibonacci(terms);
        for (int i = 0; i < results.length; i++) {
            System.out.printf("F(%2d) = %d%n", i, results[i]);
        }
    }
}
