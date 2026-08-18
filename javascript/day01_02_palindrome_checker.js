/**
 * Day 1 - Problem 2: Palindrome Checker with String Manipulation
 * Language: JavaScript (ES6+)
 * Topic: Strings, Two-Pointer Technique & Regex
 */

/**
 * Checks whether a given string is a palindrome (ignoring casing, spaces, and punctuation).
 * @param {string} str - Input string to verify.
 * @returns {boolean}
 */
function isPalindrome(str) {
  // Normalize string: convert to lower case and remove non-alphanumeric characters
  const cleanStr = str.toLowerCase().replace(/[^a-z0-9]/g, "");

  let left = 0;
  let right = cleanStr.length - 1;

  while (left < right) {
    if (cleanStr[left] !== cleanStr[right]) {
      return false;
    }
    left++;
    right--;
  }
  return true;
}

function runTests() {
  console.log("========================================");
  console.log(" Day 1: Palindrome Checker Demonstration ");
  console.log("========================================");

  const testStrings = [
    "racecar",
    "A man, a plan, a canal: Panama",
    "hello world",
    "Was it a car or a cat I saw?",
    "No 'x' in Nixon",
    "12321",
    "12345"
  ];

  testStrings.forEach((text) => {
    const result = isPalindrome(text);
    console.log(`"${text}" -> ${result ? "✅ Palindrome" : "❌ Not a Palindrome"}`);
  });
}

runTests();
