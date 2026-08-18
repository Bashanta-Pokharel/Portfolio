"""
Day 1 - Problem 1: Basics, Data Types, and User Input
Language: Python 3
Topic: Introduction & Fundamentals
"""

def main():
    print("=" * 40)
    print(" Welcome to Python Basics (Day 1) ")
    print("=" * 40)
    
    # 1. Variables and Data Types
    name: str = "Developer"
    age: int = 25
    height: float = 5.9
    is_learning: bool = True
    
    print(f"Name: {name} (Type: {type(name).__name__})")
    print(f"Age: {age} (Type: {type(age).__name__})")
    print(f"Height: {height}m (Type: {type(height).__name__})")
    print(f"Learning active: {is_learning} (Type: {type(is_learning).__name__})")
    
    # 2. Basic Arithmetic Operations
    a = 15
    b = 4
    print("\n--- Basic Arithmetic ---")
    print(f"{a} + {b} = {a + b}")
    print(f"{a} - {b} = {a - b}")
    print(f"{a} * {b} = {a * b}")
    print(f"{a} / {b} = {a / b:.2f}")
    print(f"{a} // {b} = {a // b} (Integer Division)")
    print(f"{a} % {b} = {a % b} (Modulo / Remainder)")
    print(f"{a} ** {b} = {a ** b} (Exponentiation)")

if __name__ == "__main__":
    main()
