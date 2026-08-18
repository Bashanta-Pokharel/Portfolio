"""
Day 1 - Problem 2: Interactive Arithmetic Calculator with Conditionals
Language: Python 3
Topic: Control Flow & Functions
"""

def calculate(num1: float, num2: float, operator: str) -> float | str:
    """Performs arithmetic operation based on operator string."""
    if operator == '+':
        return num1 + num2
    elif operator == '-':
        return num1 - num2
    elif operator == '*':
        return num1 * num2
    elif operator == '/':
        if num2 == 0:
            return "Error: Division by zero is undefined!"
        return num1 / num2
    elif operator == '%':
        if num2 == 0:
            return "Error: Modulo by zero is undefined!"
        return num1 % num2
    elif operator == '^':
        return num1 ** num2
    else:
        return f"Error: Unsupported operator '{operator}'"

def demo():
    print("=" * 40)
    print(" Day 1: Simple Calculator Demo ")
    print("=" * 40)
    
    test_cases = [
        (10, 5, '+'),
        (20, 8, '-'),
        (7, 6, '*'),
        (50, 4, '/'),
        (10, 0, '/'),
        (2, 5, '^')
    ]
    
    for n1, n2, op in test_cases:
        res = calculate(n1, n2, op)
        print(f"Calculation: {n1} {op} {n2}  -->  {res}")

if __name__ == "__main__":
    demo()
