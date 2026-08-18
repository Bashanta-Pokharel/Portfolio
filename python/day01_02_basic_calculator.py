"""
Day 1 - Problem 2: Basic Arithmetic Calculator in Python
"""

def calculate(a: float, b: float, op: str):
    if op == "+": return a + b
    elif op == "-": return a - b
    elif op == "*": return a * b
    elif op == "/": return a / b if b != 0 else "Error: Div by 0"
    return "Invalid operator"

print("10 + 5 =", calculate(10, 5, "+"))
print("20 / 4 =", calculate(20, 4, "/"))
