#!/usr/bin/env python3
"""
Automated Daily Code Generator
Generates 2 educational code files every day across rotating programming languages
(Python, JavaScript, C, C++, Java, Go), tracks progress in DAILY_CODE_LOG.md,
and commits changes automatically.
"""

import os
import sys
import json
import subprocess
from datetime import datetime, date

WORKSPACE_DIR = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
STATE_FILE = os.path.join(WORKSPACE_DIR, "scripts", ".daily_state.json")
LOG_FILE = os.path.join(WORKSPACE_DIR, "DAILY_CODE_LOG.md")

LANGUAGES = ["python", "javascript", "c", "cpp", "java", "go"]

# Curriculum database progressing from Day 1 to Day 100+
CURRICULUM = [
    # Day 1: Hello World & Variables
    {
        "day": 1,
        "topic": "Fundamentals: Variables & Data Types",
        "codes": {
            "python": [
                ("day01_01_hello_variables.py", '"""\nDay 1 - Problem 1: Basic Variables & Types in Python\n"""\n\nname = "Developer"\nage = 25\nheight = 5.9\nis_learning = True\n\nprint(f"Name: {name} ({type(name).__name__})")\nprint(f"Age: {age} ({type(age).__name__})")\nprint(f"Height: {height} ({type(height).__name__})")\nprint(f"Learning: {is_learning} ({type(is_learning).__name__})")\n'),
                ("day01_02_basic_calculator.py", '"""\nDay 1 - Problem 2: Basic Arithmetic Calculator in Python\n"""\n\ndef calculate(a: float, b: float, op: str):\n    if op == "+": return a + b\n    elif op == "-": return a - b\n    elif op == "*": return a * b\n    elif op == "/": return a / b if b != 0 else "Error: Div by 0"\n    return "Invalid operator"\n\nprint("10 + 5 =", calculate(10, 5, "+"))\nprint("20 / 4 =", calculate(20, 4, "/"))\n')
            ],
            "javascript": [
                ("day01_01_basics_and_data_types.js", '// Day 1 - Problem 1: JS Basics & Primitive Types\nconst lang = "JavaScript";\nlet version = 2026;\nlet isAwesome = true;\n\nconsole.log(`Language: ${lang}, Year: ${version}, Active: ${isAwesome}`);\n'),
                ("day01_02_palindrome_checker.js", '// Day 1 - Problem 2: Palindrome Checker\nfunction isPalindrome(str) {\n  const clean = str.toLowerCase().replace(/[^a-z0-9]/g, "");\n  return clean === clean.split("").reverse().join("");\n}\n\nconsole.log("racecar ->", isPalindrome("racecar"));\nconsole.log("hello ->", isPalindrome("hello"));\n')
            ],
            "c": [
                ("day01_01_hello_variables.c", '#include <stdio.h>\n\nint main(void) {\n    int age = 25;\n    float score = 94.5f;\n    char grade = \'A\';\n    printf("Age: %d, Score: %.1f, Grade: %c\\n", age, score, grade);\n    return 0;\n}\n'),
                ("day01_02_even_odd_checker.c", '#include <stdio.h>\n#include <stdbool.h>\n\nbool isEven(int n) {\n    return (n % 2) == 0;\n}\n\nint main(void) {\n    int nums[] = {2, 7, 14, 21, 30};\n    for(int i=0; i<5; i++) {\n        printf("%d is %s\\n", nums[i], isEven(nums[i]) ? "Even" : "Odd");\n    }\n    return 0;\n}\n')
            ],
            "cpp": [
                ("day01_01_basic_io_and_types.cpp", '#include <iostream>\n#include <string>\n\nint main() {\n    std::string topic = "Modern C++ Basics";\n    int day = 1;\n    std::cout << topic << " - Day " << day << std::endl;\n    return 0;\n}\n'),
                ("day01_02_array_sum_and_average.cpp", '#include <iostream>\n#include <vector>\n#include <numeric>\n\nint main() {\n    std::vector<int> nums = {10, 20, 30, 40, 50};\n    int sum = std::accumulate(nums.begin(), nums.end(), 0);\n    double avg = static_cast<double>(sum) / nums.size();\n    std::cout << "Sum: " << sum << ", Avg: " << avg << std::endl;\n    return 0;\n}\n')
            ],
            "java": [
                ("day01_01_HelloWorldAndVariables.java", 'public class day01_01_HelloWorldAndVariables {\n    public static void main(String[] args) {\n        String name = "Java Developer";\n        int level = 1;\n        System.out.println("Welcome " + name + " - Level: " + level);\n    }\n}\n'),
                ("day01_02_FibonacciSeries.java", 'public class day01_02_FibonacciSeries {\n    public static void main(String[] args) {\n        int n = 10, a = 0, b = 1;\n        System.out.print("Fibonacci (" + n + " terms): ");\n        for (int i = 0; i < n; i++) {\n            System.out.print(a + " ");\n            int next = a + b;\n            a = b;\n            b = next;\n        }\n        System.out.println();\n    }\n}\n')
            ],
            "go": [
                ("day01_01_hello_types.go", 'package main\n\nimport "fmt"\n\nfunc main() {\n    msg := "Hello from Go Daily Practice"\n    fmt.Println(msg)\n}\n'),
                ("day01_02_temperature_converter.go", 'package main\n\nimport "fmt"\n\nfunc cToF(c float64) float64 {\n    return (c * 9.0 / 5.0) + 32.0\n}\n\nfunc main() {\n    c := 25.0\n    fmt.Printf("%.1f C = %.1f F\\n", c, cToF(c))\n}\n')
            ]
        }
    },
    # Day 2: Control Flow, Loops & Conditionals
    {
        "day": 2,
        "topic": "Loops & Iteration Algorithms",
        "codes": {
            "python": [
                ("day02_01_prime_number_checker.py", '"""\nDay 2 - Problem 1: Prime Number Checker\n"""\n\ndef is_prime(n: int) -> bool:\n    if n <= 1:\n        return False\n    if n <= 3:\n        return True\n    if n % 2 == 0 or n % 3 == 0:\n        return False\n    i = 5\n    while i * i <= n:\n        if n % i == 0 or n % (i + 2) == 0:\n            return False\n        i += 6\n    return True\n\nprimes = [x for x in range(1, 50) if is_prime(x)]\nprint("Primes under 50:", primes)\n'),
                ("day02_02_multiplication_table.py", '"""\nDay 2 - Problem 2: Formatted Multiplication Matrix Generator\n"""\n\ndef print_matrix(size: int = 10):\n    print(f"--- {size}x{size} Multiplication Matrix ---")\n    for row in range(1, size + 1):\n        line = " ".join(f"{row * col:4d}" for col in range(1, size + 1))\n        print(line)\n\nif __name__ == "__main__":\n    print_matrix(10)\n')
            ],
            "javascript": [
                ("day02_01_fizzbuzz_generator.js", '// Day 2 - Problem 1: FizzBuzz Classic Interview Problem\nfunction fizzBuzz(limit = 30) {\n  for (let i = 1; i <= limit; i++) {\n    let output = "";\n    if (i % 3 === 0) output += "Fizz";\n    if (i % 5 === 0) output += "Buzz";\n    console.log(output || i);\n  }\n}\nfizzBuzz(30);\n'),
                ("day02_02_array_stats_filter.js", '// Day 2 - Problem 2: Higher Order Array Methods (Filter, Map, Reduce)\nconst scores = [65, 88, 45, 92, 73, 58, 99, 81];\n\nconst passingScores = scores.filter(s => s >= 60);\nconst scaledScores = scores.map(s => Math.min(100, s + 5));\nconst averageScore = scores.reduce((acc, curr) => acc + curr, 0) / scores.length;\n\nconsole.log("Original:", scores);\nconsole.log("Passing (>60):", passingScores);\nconsole.log("Curved Scores (+5):", scaledScores);\nconsole.log("Average:", averageScore.toFixed(2));\n')
            ],
            "c": [
                ("day02_01_reverse_integer.c", '#include <stdio.h>\n\nlong long reverseNumber(long long n) {\n    long long rev = 0;\n    while (n != 0) {\n        rev = rev * 10 + (n % 10);\n        n /= 10;\n    }\n    return rev;\n}\n\nint main(void) {\n    long long num = 123456789;\n    printf("Original: %lld -> Reversed: %lld\\n", num, reverseNumber(num));\n    return 0;\n}\n'),
                ("day02_02_factorial_iterative.c", '#include <stdio.h>\n\nunsigned long long factorial(int n) {\n    unsigned long long result = 1;\n    for (int i = 2; i <= n; i++) {\n        result *= i;\n    }\n    return result;\n}\n\nint main(void) {\n    for (int i = 0; i <= 15; i++) {\n        printf("%2d! = %llu\\n", i, factorial(i));\n    }\n    return 0;\n}\n')
            ],
            "cpp": [
                ("day02_01_binary_search.cpp", '#include <iostream>\n#include <vector>\n\nint binarySearch(const std::vector<int>& arr, int target) {\n    int left = 0, right = static_cast<int>(arr.size()) - 1;\n    while (left <= right) {\n        int mid = left + (right - left) / 2;\n        if (arr[mid] == target) return mid;\n        if (arr[mid] < target) left = mid + 1;\n        else right = mid - 1;\n    }\n    return -1;\n}\n\nint main() {\n    std::vector<int> data = {2, 5, 8, 12, 16, 23, 38, 56, 72, 91};\n    int target = 23;\n    int idx = binarySearch(data, target);\n    std::cout << "Found " << target << " at index: " << idx << std::endl;\n    return 0;\n}\n'),
                ("day02_02_matrix_transpose.cpp", '#include <iostream>\n#include <vector>\n\nint main() {\n    std::vector<std::vector<int>> matrix = {\n        {1, 2, 3},\n        {4, 5, 6}\n    };\n    int r = matrix.size(), c = matrix[0].size();\n    std::vector<std::vector<int>> transposed(c, std::vector<int>(r));\n    for(int i=0; i<r; i++)\n        for(int j=0; j<c; j++)\n            transposed[j][i] = matrix[i][j];\n            \n    std::cout << "Transposed 2x3 to 3x2:\\n";\n    for(const auto& row : transposed) {\n        for(int val : row) std::cout << val << " ";\n        std::cout << "\\n";\n    }\n    return 0;\n}\n')
            ],
            "java": [
                ("day02_01_AnagramChecker.java", 'import java.util.Arrays;\n\npublic class day02_01_AnagramChecker {\n    public static boolean isAnagram(String s1, String s2) {\n        char[] a = s1.replaceAll("\\\\s+", "").toLowerCase().toCharArray();\n        char[] b = s2.replaceAll("\\\\s+", "").toLowerCase().toCharArray();\n        Arrays.sort(a);\n        Arrays.sort(b);\n        return Arrays.equals(a, b);\n    }\n\n    public static void main(String[] args) {\n        System.out.println("listen & silent: " + isAnagram("listen", "silent"));\n        System.out.println("hello & world: " + isAnagram("hello", "world"));\n    }\n}\n'),
                ("day02_02_SecondLargestElement.java", 'public class day02_02_SecondLargestElement {\n    public static int findSecondLargest(int[] arr) {\n        int largest = Integer.MIN_VALUE, second = Integer.MIN_VALUE;\n        for (int num : arr) {\n            if (num > largest) {\n                second = largest;\n                largest = num;\n            } else if (num > second && num != largest) {\n                second = num;\n            }\n        }\n        return second;\n    }\n\n    public static void main(String[] args) {\n        int[] numbers = {12, 35, 1, 10, 34, 1};\n        System.out.println("Second Largest: " + findSecondLargest(numbers));\n    }\n}\n')
            ],
            "go": [
                ("day02_01_slice_rotator.go", 'package main\n\nimport "fmt"\n\nfunc rotateSlice(nums []int, k int) []int {\n    n := len(nums)\n    k = k % n\n    res := make([]int, n)\n    copy(res, nums[n-k:])\n    copy(res[k:], nums[:n-k])\n    return res\n}\n\nfunc main() {\n    original := []int{1, 2, 3, 4, 5, 6, 7}\n    rotated := rotateSlice(original, 3)\n    fmt.Println("Original:", original)\n    fmt.Println("Rotated by 3:", rotated)\n}\n'),
                ("day02_02_word_frequency_map.go", 'package main\n\nimport (\n    "fmt"\n    "strings"\n)\n\nfunc countWords(text string) map[string]int {\n    words := strings.Fields(strings.ToLower(text))\n    freq := make(map[string]int)\n    for _, w := range words {\n        freq[w]++\n    }\n    return freq\n}\n\nfunc main() {\n    quote := "go is expressive concise clean and efficient go is fast"\n    freqs := countWords(quote)\n    fmt.Println("Word frequencies:", freqs)\n}\n')
            ]
        }
    },
    # Day 3: Strings & String Algorithms
    {
        "day": 3,
        "topic": "Strings & Pattern Search",
        "codes": {
            "python": [
                ("day03_01_vowel_and_consonant_counter.py", '"""\nDay 3 - Problem 1: Vowel and Consonant Counter in Strings\n"""\n\ndef analyze_string(text: str) -> dict:\n    vowels = set("aeiouAEIOU")\n    v_count = sum(1 for c in text if c in vowels)\n    c_count = sum(1 for c in text if c.isalpha() and c not in vowels)\n    digits = sum(1 for c in text if c.isdigit())\n    spaces = sum(1 for c in text if c.isspace())\n    return {"vowels": v_count, "consonants": c_count, "digits": digits, "spaces": spaces}\n\nresult = analyze_string("Learning Python 3 Daily in 2026!")\nprint("String Analysis:", result)\n'),
                ("day03_02_caesar_cipher.py", '"""\nDay 3 - Problem 2: Caesar Cipher Encoder / Decoder\n"""\n\ndef caesar_cipher(text: str, shift: int = 3) -> str:\n    result = []\n    for char in text:\n        if char.isalpha():\n            base = ord("A") if char.isupper() else ord("a")\n            shifted = chr((ord(char) - base + shift) % 26 + base)\n            result.append(shifted)\n        else:\n            result.append(char)\n    return "".join(result)\n\nsecret = caesar_cipher("Hello World!", 5)\nprint("Encrypted:", secret)\nprint("Decrypted:", caesar_cipher(secret, -5))\n')
            ],
            "javascript": [
                ("day03_01_capitalize_words.js", '// Day 3 - Problem 1: Title Case / Capitalize Words\nfunction toTitleCase(str) {\n  return str\n    .toLowerCase()\n    .split(" ")\n    .map(word => word.charAt(0).toUpperCase() + word.slice(1))\n    .join(" ");\n}\n\nconsole.log(toTitleCase("the quick brown fox jumps over the lazy dog"));\n'),
                ("day03_02_chunk_array.js", '// Day 3 - Problem 2: Chunk Array into Subarrays\nfunction chunkArray(arr, size) {\n  const chunks = [];\n  for (let i = 0; i < arr.length; i += size) {\n    chunks.push(arr.slice(i, i + size));\n  }\n  return chunks;\n}\n\nconsole.log(chunkArray([1, 2, 3, 4, 5, 6, 7, 8], 3));\n')
            ],
            "c": [
                ("day03_01_string_length_and_reverse.c", '#include <stdio.h>\n#include <string.h>\n\nvoid reverseString(char* str) {\n    int len = (int)strlen(str);\n    for (int i = 0; i < len / 2; i++) {\n        char temp = str[i];\n        str[i] = str[len - 1 - i];\n        str[len - 1 - i] = temp;\n    }\n}\n\nint main(void) {\n    char greeting[] = "Hello Antigravity!";\n    printf("Original: %s\\n", greeting);\n    reverseString(greeting);\n    printf("Reversed: %s\\n", greeting);\n    return 0;\n}\n'),
                ("day03_02_string_copy_custom.c", '#include <stdio.h>\n\nvoid customStrcpy(char* dest, const char* src) {\n    while ((*dest++ = *src++));\n}\n\nint main(void) {\n    char source[] = "Custom C Pointer Copy";\n    char destination[50];\n    customStrcpy(destination, source);\n    printf("Copied String: %s\\n", destination);\n    return 0;\n}\n')
            ],
            "cpp": [
                ("day03_01_string_compression.cpp", '#include <iostream>\n#include <string>\n\nstd::string compressString(const std::string& str) {\n    if (str.empty()) return "";\n    std::string res;\n    int count = 1;\n    for (size_t i = 1; i <= str.size(); i++) {\n        if (i < str.size() && str[i] == str[i - 1]) {\n            count++;\n        } else {\n            res += str[i - 1] + std::to_string(count);\n            count = 1;\n        }\n    }\n    return res.length() < str.length() ? res : str;\n}\n\nint main() {\n    std::cout << compressString("aabcccccaaa") << std::endl;\n    return 0;\n}\n'),
                ("day03_02_valid_parentheses.cpp", '#include <iostream>\n#include <stack>\n#include <string>\n\nbool isValid(const std::string& s) {\n    std::stack<char> st;\n    for (char c : s) {\n        if (c == \'(\') st.push(\')\');\n        else if (c == \'{\') st.push(\'}\');\n        else if (c == \'[\') st.push(\']\');\n        else if (st.empty() || st.top() != c) return false;\n        else st.pop();\n    }\n    return st.empty();\n}\n\nint main() {\n    std::cout << "()[]{}: " << (isValid("()[]{}") ? "Valid" : "Invalid") << std::endl;\n    std::cout << "([)]:   " << (isValid("([)]") ? "Valid" : "Invalid") << std::endl;\n    return 0;\n}\n')
            ],
            "java": [
                ("day03_01_ReverseWordsInString.java", 'public class day03_01_ReverseWordsInString {\n    public static String reverseWords(String s) {\n        String[] words = s.trim().split("\\\\s+");\n        StringBuilder sb = new StringBuilder();\n        for (int i = words.length - 1; i >= 0; i--) {\n            sb.append(words[i]);\n            if (i > 0) sb.append(" ");\n        }\n        return sb.toString();\n    }\n\n    public static void main(String[] args) {\n        String input = "the sky is blue";\n        System.out.println("Reversed words: \"" + reverseWords(input) + "\"");\n    }\n}\n'),
                ("day03_02_CountOccurrences.java", 'import java.util.HashMap;\nimport java.util.Map;\n\npublic class day03_02_CountOccurrences {\n    public static void main(String[] args) {\n        String text = "programming in java";\n        Map<Character, Integer> counts = new HashMap<>();\n        for (char c : text.toCharArray()) {\n            if (c != \' \') counts.put(c, counts.getOrDefault(c, 0) + 1);\n        }\n        System.out.println("Character counts: " + counts);\n    }\n}\n')
            ],
            "go": [
                ("day03_01_palindrome_runes.go", 'package main\n\nimport (\n    "fmt"\n    "unicode"\n)\n\nfunc isPalindrome(s string) bool {\n    var runes []rune\n    for _, r := range s {\n        if unicode.IsLetter(r) || unicode.IsDigit(r) {\n            runes = append(runes, unicode.ToLower(r))\n        }\n    }\n    for i, j := 0, len(runes)-1; i < j; i, j = i+1, j-1 {\n        if runes[i] != runes[j] {\n            return false\n        }\n    }\n    return true\n}\n\nfunc main() {\n    fmt.Println(isPalindrome("Was it a car or a cat I saw?"))\n}\n'),
                ("day03_02_string_builder_benchmark.go", 'package main\n\nimport (\n    "fmt"\n    "strings"\n)\n\nfunc main() {\n    var builder strings.Builder\n    for i := 1; i <= 5; i++ {\n        builder.WriteString(fmt.Sprintf("Item %d\\n", i))\n    }\n    fmt.Print(builder.String())\n}\n')
            ]
        }
    }
]

def load_state():
    if os.path.exists(STATE_FILE):
        try:
            with open(STATE_FILE, "r") as f:
                return json.load(f)
        except Exception:
            pass
    return {"current_day": 1, "lang_index": 0, "history": []}

def save_state(state):
    os.makedirs(os.path.dirname(STATE_FILE), exist_ok=True)
    with open(STATE_FILE, "w") as f:
        json.dump(state, f, indent=2)

def generate_today():
    today_str = date.today().isoformat()
    state = load_state()

    current_day = state.get("current_day", 1)
    lang_index = state.get("lang_index", 0)

    # Check if already ran today
    history = state.get("history", [])
    if history and history[-1].get("date") == today_str and len(sys.argv) == 1:
        print(f"✅ Code already generated for today ({today_str}). Use --force to generate again.")
        return

    # Choose language rotating every day
    lang = LANGUAGES[lang_index % len(LANGUAGES)]
    
    # Get curriculum item for current day (or fallback dynamic generator)
    curr_item = next((c for c in CURRICULUM if c["day"] == current_day), None)
    if not curr_item:
        curr_item = CURRICULUM[(current_day - 1) % len(CURRICULUM)]

    codes = curr_item["codes"].get(lang, [])
    if not codes:
        codes = curr_item["codes"]["python"]

    lang_dir = os.path.join(WORKSPACE_DIR, lang)
    os.makedirs(lang_dir, exist_ok=True)

    created_files = []
    for filename, code_content in codes:
        target_path = os.path.join(lang_dir, filename)
        with open(target_path, "w") as f:
            f.write(code_content)
        created_files.append((filename, target_path))
        print(f"📄 Created: {target_path}")

    # Append to DAILY_CODE_LOG.md
    with open(LOG_FILE, "a") as f:
        for filename, target_path in created_files:
            rel_link = f"[`{lang}/{filename}`](file://{target_path})"
            f.write(f"| **{today_str} (Day {current_day})** | {lang.capitalize()} | {curr_item['topic']} | {rel_link} |\n")

    # Update state
    state["current_day"] = current_day + 1
    state["lang_index"] = lang_index + 1
    state["history"].append({
        "date": today_str,
        "day": current_day,
        "language": lang,
        "files": [f[0] for f in created_files]
    })
    save_state(state)

    print(f"\n✨ Successfully generated 2 {lang.capitalize()} exercises for Day {current_day}!")

    # Auto commit to git if in a git repo
    try:
        subprocess.run(["git", "add", "."], cwd=WORKSPACE_DIR, check=False)
        subprocess.run(["git", "commit", "-m", f"feat(daily-code): add day {current_day} ({lang}) 2 code exercises"], cwd=WORKSPACE_DIR, check=False)
        print("📦 Automatically committed changes to Git!")
    except Exception as e:
        print(f"Git commit skipped: {e}")

if __name__ == "__main__":
    generate_today()
