// Cyber Password Vault & Entropy Analyzer
const charSets = {
  upper: "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
  lower: "abcdefghijklmnopqrstuvwxyz",
  numbers: "0123456789",
  symbols: "!@#$%^&*()_+-=[]{}|;:,.<>?",
  ambiguous: /[il1Lo0O]/g
};

// Elements
const passwordDisplay = document.getElementById("password-display");
const regenerateBtn = document.getElementById("regenerate-btn");
const copyBtn = document.getElementById("copy-btn");
const mainGenerateBtn = document.getElementById("main-generate-btn");
const lengthSlider = document.getElementById("length-slider");
const lengthDisplay = document.getElementById("length-display");
const chkUpper = document.getElementById("chk-upper");
const chkLower = document.getElementById("chk-lower");
const chkNumbers = document.getElementById("chk-numbers");
const chkSymbols = document.getElementById("chk-symbols");
const chkExcludeSimilar = document.getElementById("chk-exclude-similar");
const strengthFill = document.getElementById("strength-fill");
const entropyText = document.getElementById("entropy-text");
const crackTime = document.getElementById("crack-time");
const copyToast = document.getElementById("copy-toast");

function getSecureRandomInt(max) {
  const array = new Uint32Array(1);
  window.crypto.getRandomValues(array);
  return array[0] % max;
}

function generatePassword() {
  const length = parseInt(lengthSlider.value, 10);
  let availablePool = "";
  let guaranteedChars = [];

  if (chkUpper.checked) {
    availablePool += charSets.upper;
    guaranteedChars.push(charSets.upper[getSecureRandomInt(charSets.upper.length)]);
  }
  if (chkLower.checked) {
    availablePool += charSets.lower;
    guaranteedChars.push(charSets.lower[getSecureRandomInt(charSets.lower.length)]);
  }
  if (chkNumbers.checked) {
    availablePool += charSets.numbers;
    guaranteedChars.push(charSets.numbers[getSecureRandomInt(charSets.numbers.length)]);
  }
  if (chkSymbols.checked) {
    availablePool += charSets.symbols;
    guaranteedChars.push(charSets.symbols[getSecureRandomInt(charSets.symbols.length)]);
  }

  if (chkExcludeSimilar.checked) {
    availablePool = availablePool.replace(charSets.ambiguous, "");
    guaranteedChars = guaranteedChars.map(c => c.replace(charSets.ambiguous, "x"));
  }

  // Fallback if nothing is checked
  if (!availablePool) {
    availablePool = charSets.lower;
    chkLower.checked = true;
  }

  let generated = [...guaranteedChars];
  for (let i = generated.length; i < length; i++) {
    const randomIdx = getSecureRandomInt(availablePool.length);
    generated.push(availablePool[randomIdx]);
  }

  // Shuffle the password array using Fisher-Yates
  for (let i = generated.length - 1; i > 0; i--) {
    const j = getSecureRandomInt(i + 1);
    [generated[i], generated[j]] = [generated[j], generated[i]];
  }

  const finalPassword = generated.join("");
  passwordDisplay.value = finalPassword;
  calculateStrength(finalPassword, availablePool.length);
}

function calculateStrength(pwd, poolSize) {
  const len = pwd.length;
  // Entropy in bits = length * log2(poolSize)
  const entropy = len * Math.log2(poolSize || 26);

  let label = "Very Weak";
  let color = "#f43f5e";
  let percent = 20;
  let timeStr = "Instantly";

  if (entropy < 35) {
    label = "Weak";
    color = "#f43f5e";
    percent = 25;
    timeStr = "~2 Seconds";
  } else if (entropy < 55) {
    label = "Moderate";
    color = "#f59e0b";
    percent = 50;
    timeStr = "~3 Days";
  } else if (entropy < 80) {
    label = "Strong";
    color = "#10b981";
    percent = 80;
    timeStr = "~800 Years";
  } else {
    label = "Unbreakable";
    color = "#00d4ff";
    percent = 100;
    timeStr = "Trillions of Years";
  }

  entropyText.innerHTML = `Strength: <strong style="color: ${color}">${label}</strong> (${Math.round(entropy)} bits)`;
  crackTime.textContent = `Crack Time: ${timeStr}`;
  strengthFill.style.width = `${percent}%`;
  strengthFill.style.background = color;
  strengthFill.style.boxShadow = `0 0 10px ${color}66`;
}

function copyPassword() {
  const text = passwordDisplay.value;
  if (!text) return;

  navigator.clipboard.writeText(text).then(() => {
    copyToast.classList.add("show");
    setTimeout(() => {
      copyToast.classList.remove("show");
    }, 2000);
  }).catch(() => {
    // Fallback selection
    passwordDisplay.select();
    document.execCommand("copy");
    copyToast.classList.add("show");
    setTimeout(() => {
      copyToast.classList.remove("show");
    }, 2000);
  });
}

// Event Listeners
lengthSlider.addEventListener("input", (e) => {
  lengthDisplay.textContent = e.target.value;
  generatePassword();
});

[chkUpper, chkLower, chkNumbers, chkSymbols, chkExcludeSimilar].forEach(chk => {
  chk.addEventListener("change", generatePassword);
});

regenerateBtn.addEventListener("click", generatePassword);
mainGenerateBtn.addEventListener("click", generatePassword);
copyBtn.addEventListener("click", copyPassword);

document.addEventListener("keydown", (e) => {
  if (e.code === "Space" && e.target.tagName !== "INPUT") {
    e.preventDefault();
    generatePassword();
  }
});

// Initial generation
generatePassword();
