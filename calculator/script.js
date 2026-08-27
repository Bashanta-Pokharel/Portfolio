// Modern Neo-Calculator Logic
let currentInput = "0";
let expression = "";
let isEvaluated = false;
let historyLog = [];

const currentEl = document.getElementById("calc-current");
const expressionEl = document.getElementById("calc-expression");
const historyList = document.getElementById("history-list");
const historyToggle = document.getElementById("history-toggle");
const historyPanel = document.getElementById("history-panel");
const clearHistoryBtn = document.getElementById("clear-history");

// Audio clicks
const audioCtx = (function() {
  let ctx = null;
  return function() {
    if (!ctx) {
      const AudioCtx = window.AudioContext || window.webkitAudioContext;
      if (AudioCtx) ctx = new AudioCtx();
    }
    return ctx;
  };
})();

function playClick(freq = 750, duration = 0.03) {
  try {
    const ctx = audioCtx();
    if (!ctx) return;
    if (ctx.state === 'suspended') ctx.resume();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    osc.frequency.setValueAtTime(freq, ctx.currentTime);
    gain.gain.setValueAtTime(0.08, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + duration);
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.start();
    osc.stop(ctx.currentTime + duration);
  } catch (e) {}
}

function updateDisplay() {
  currentEl.textContent = currentInput || "0";
  expressionEl.textContent = expression || "0";
}

function inputNumber(num) {
  playClick(600);
  if (isEvaluated) {
    currentInput = num === "." ? "0." : num;
    expression = "";
    isEvaluated = false;
  } else {
    if (num === ".") {
      if (currentInput.includes(".")) return;
      currentInput = currentInput + ".";
    } else {
      currentInput = currentInput === "0" ? num : currentInput + num;
    }
  }
  updateDisplay();
}

function inputOperator(op) {
  playClick(850);
  if (isEvaluated) {
    expression = currentInput + " " + op + " ";
    currentInput = "0";
    isEvaluated = false;
  } else {
    if (currentInput === "0" && expression !== "") {
      // Replace last operator
      expression = expression.slice(0, -3) + " " + op + " ";
    } else {
      expression += currentInput + " " + op + " ";
      currentInput = "0";
    }
  }
  updateDisplay();
}

function handleFunction(func) {
  playClick(900);
  if (func === "clear") {
    currentInput = "0";
    expression = "";
    isEvaluated = false;
  } else if (func === "delete") {
    if (isEvaluated) {
      expression = "";
      isEvaluated = false;
    }
    currentInput = currentInput.length > 1 ? currentInput.slice(0, -1) : "0";
  } else if (func === "negate") {
    if (currentInput !== "0") {
      currentInput = currentInput.startsWith("-") ? currentInput.slice(1) : "-" + currentInput;
    }
  } else if (func === "percent") {
    const val = parseFloat(currentInput);
    if (!isNaN(val)) {
      currentInput = String(val / 100);
    }
  } else if (func === "sqrt") {
    const val = parseFloat(currentInput);
    if (!isNaN(val) && val >= 0) {
      const res = Math.sqrt(val);
      recordHistory(`√(${currentInput})`, res);
      currentInput = String(Number(res.toFixed(8)));
      isEvaluated = true;
    }
  } else if (func === "power") {
    const val = parseFloat(currentInput);
    if (!isNaN(val)) {
      const res = Math.pow(val, 2);
      recordHistory(`sqr(${currentInput})`, res);
      currentInput = String(Number(res.toFixed(8)));
      isEvaluated = true;
    }
  } else if (func === "bracket") {
    // Simple bracket toggling
    if (expression.includes("(") && !expression.includes(")")) {
      expression += currentInput + " )";
      currentInput = "0";
    } else {
      expression += "( ";
    }
  }
  updateDisplay();
}

function evaluate() {
  playClick(1050, 0.05);
  let fullExpr = expression + currentInput;
  if (!fullExpr || fullExpr === "0") return;

  try {
    // Sanitize mathematical expression safely
    const sanitized = fullExpr
      .replace(/×/g, "*")
      .replace(/÷/g, "/")
      .replace(/−/g, "-")
      .replace(/[^0-9+\-*/().\s]/g, "");

    // Safe mathematical evaluation using Function constructor for pure numbers
    const result = Function(`'use strict'; return (${sanitized})`)();

    if (!isFinite(result) || isNaN(result)) {
      currentInput = "Error";
    } else {
      const formattedResult = Number(result.toFixed(8));
      recordHistory(fullExpr, formattedResult);
      expression = fullExpr + " =";
      currentInput = String(formattedResult);
      isEvaluated = true;
    }
  } catch (err) {
    currentInput = "Error";
  }
  updateDisplay();
}

function recordHistory(expr, res) {
  historyLog.unshift({ expr, res });
  renderHistory();
}

function renderHistory() {
  if (historyLog.length === 0) {
    historyList.innerHTML = '<div class="empty-history">No calculation history yet.</div>';
    return;
  }

  historyList.innerHTML = historyLog.map((item, idx) => `
    <div class="history-item" data-idx="${idx}">
      <div class="history-expr">${item.expr} =</div>
      <div class="history-res">${item.res}</div>
    </div>
  `).join("");

  document.querySelectorAll(".history-item").forEach(item => {
    item.addEventListener("click", () => {
      const idx = parseInt(item.dataset.idx, 10);
      currentInput = String(historyLog[idx].res);
      isEvaluated = true;
      updateDisplay();
      playClick(700);
    });
  });
}

// Clear history
clearHistoryBtn.addEventListener("click", () => {
  historyLog = [];
  renderHistory();
  playClick(400);
});

// History Toggle for Mobile
if (historyToggle) {
  historyToggle.addEventListener("click", () => {
    historyPanel.classList.toggle("show-mobile");
    historyToggle.classList.toggle("active");
    playClick(600);
  });
}

// Button click events
document.querySelector(".calc-grid").addEventListener("click", (e) => {
  const btn = e.target.closest(".btn");
  if (!btn) return;

  const val = btn.dataset.val;
  const action = btn.dataset.action;

  if (val !== undefined && action === undefined) {
    inputNumber(val);
  } else if (action === "op") {
    inputOperator(val);
  } else if (action === "equals") {
    evaluate();
  } else if (action) {
    handleFunction(action);
  }
});

// Keyboard support
document.addEventListener("keydown", (e) => {
  if (e.key >= "0" && e.key <= "9") {
    inputNumber(e.key);
  } else if (e.key === ".") {
    inputNumber(".");
  } else if (e.key === "+" || e.key === "-" || e.key === "*" || e.key === "/") {
    inputOperator(e.key);
  } else if (e.key === "Enter" || e.key === "=") {
    e.preventDefault();
    evaluate();
  } else if (e.key === "Backspace") {
    handleFunction("delete");
  } else if (e.key === "Escape") {
    handleFunction("clear");
  } else if (e.key === "%") {
    handleFunction("percent");
  }
});

// Initialize display
updateDisplay();
