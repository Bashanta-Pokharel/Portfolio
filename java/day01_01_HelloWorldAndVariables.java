/**
 * Day 1 - Problem 1: Basic Java Class Structure, Data Types & Printing
 * Language: Java 17+
 * Topic: Class Structure, Variables, Primitive Types
 */

public class day01_01_HelloWorldAndVariables {
    public static void main(String[] args) {
        System.out.println("========================================");
        System.out.println(" Welcome to Java Basics (Day 1) ");
        System.out.println("========================================");

        // Primitive variables
        byte smallNum = 100;
        int standardInt = 45000;
        long largeNum = 9876543210L;
        double piValue = 3.1415926535;
        char grade = 'A';
        boolean isJavaAwesome = true;
        String greeting = "Hello, World of Java!";

        System.out.println("Greeting: " + greeting);
        System.out.println("Byte value: " + smallNum);
        System.out.println("Integer value: " + standardInt);
        System.out.println("Long value: " + largeNum);
        System.out.println("Double value: " + piValue);
        System.out.println("Char value: " + grade);
        System.out.println("Boolean value: " + isJavaAwesome);
    }
}
