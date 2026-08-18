/**
 * Day 1 - Problem 1: JavaScript Variables, Primitive Types & Template Literals
 * Language: JavaScript (ES6+)
 * Topic: Syntax & Primitives
 */

function runBasics() {
  console.log("========================================");
  console.log(" Welcome to JavaScript Basics (Day 1) ");
  console.log("========================================");

  // 1. Variable Declarations
  const language = "JavaScript";
  let releaseYear = 1995;
  let isFlexible = true;
  let notAssigned;
  let emptyValue = null;

  console.log(`Language: ${language} (type: ${typeof language})`);
  console.log(`Release Year: ${releaseYear} (type: ${typeof releaseYear})`);
  console.log(`Is Flexible: ${isFlexible} (type: ${typeof isFlexible})`);
  console.log(`Unassigned: ${notAssigned} (type: ${typeof notAssigned})`);
  console.log(`Empty Value: ${emptyValue} (type: ${typeof emptyValue})`);

  // 2. Arrays and Objects
  const techStack = ["React", "Node.js", "Express", "MongoDB"];
  console.log("\n--- Sample Array ---");
  console.log(`Stack Items (${techStack.length}): ${techStack.join(", ")}`);

  const profile = {
    username: "coder101",
    role: "Fullstack Developer",
    skillsCount: techStack.length,
  };

  console.log("\n--- Object Representation ---");
  console.log(JSON.stringify(profile, null, 2));
}

runBasics();
