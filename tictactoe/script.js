// Neon Cyber Tic-Tac-Toe Game Engine with Minimax AI
let board = Array(9).fill(null);
let currentPlayer = "X";
let isGameActive = true;
let vsAI = true;
let scores = { X: 0, O: 0, ties: 0 };

const WINNING_COMBOS = [
  [0, 1, 2], [3, 4, 5], [6, 7, 8], // Rows
  [0, 3, 6], [1, 4, 7], [2, 5, 8], // Cols
  [0, 4, 8], [2, 4, 6]             // Diagonals
];

// DOM Elements
const cells = document.querySelectorAll(".cell");
const turnStatus = document.getElementById("turn-status");
const scoreXEl = document.getElementById("score-x");
const scoreOEl = document.getElementById("score-o");
const scoreTiesEl = document.getElementById("score-ties");
const oPlayerLabel = document.getElementById("o-player-label");
const modeAI = document.getElementById("mode-ai");
const modePvP = document.getElementById("mode-pvp");
const restartBtn = document.getElementById("restart-btn");
const resetScoresBtn = document.getElementById("reset-scores-btn");

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

function playSound(freq, duration = 0.05, type = 'sine') {
  try {
    const ctx = audioCtx();
    if (!ctx) return;
    if (ctx.state === 'suspended') ctx.resume();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    osc.type = type;
    osc.frequency.setValueAtTime(freq, ctx.currentTime);
    gain.gain.setValueAtTime(0.08, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + duration);
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.start();
    osc.stop(ctx.currentTime + duration);
  } catch (e) {}
}

function handleCellClick(e) {
  const cell = e.target;
  const index = parseInt(cell.dataset.index, 10);

  if (board[index] !== null || !isGameActive) return;

  makeMove(index, currentPlayer);

  if (isGameActive && vsAI && currentPlayer === "O") {
    // AI turn
    turnStatus.textContent = "AI is thinking...";
    setTimeout(() => {
      if (!isGameActive) return;
      const bestMove = getBestMove();
      makeMove(bestMove, "O");
    }, 280);
  }
}

function makeMove(index, player) {
  board[index] = player;
  const cell = cells[index];
  cell.textContent = player;
  cell.classList.add(player.toLowerCase(), "taken");
  playSound(player === "X" ? 650 : 480, 0.06);

  const winCombo = checkWin(board, player);
  if (winCombo) {
    endGame(player, winCombo);
  } else if (board.every(cell => cell !== null)) {
    endGame("tie");
  } else {
    currentPlayer = player === "X" ? "O" : "X";
    updateTurnStatus();
  }
}

function updateTurnStatus() {
  const name = currentPlayer === "X" ? "Player X" : (vsAI ? "AI (O)" : "Player O");
  const highlightClass = currentPlayer === "X" ? "highlight-x" : "highlight-o";
  turnStatus.innerHTML = `<span class="${highlightClass}">${name}</span>'s Turn`;
}

function checkWin(b, player) {
  for (let combo of WINNING_COMBOS) {
    if (combo.every(idx => b[idx] === player)) {
      return combo;
    }
  }
  return null;
}

function endGame(winner, combo = null) {
  isGameActive = false;
  if (winner === "tie") {
    scores.ties++;
    scoreTiesEl.textContent = scores.ties;
    turnStatus.innerHTML = "<span style='color: #f59e0b;'>It's a Tie Game!</span>";
    playSound(350, 0.15);
  } else {
    scores[winner]++;
    if (winner === "X") scoreXEl.textContent = scores.X;
    else scoreOEl.textContent = scores.O;

    const winnerName = winner === "X" ? "Player X" : (vsAI ? "AI" : "Player O");
    const color = winner === "X" ? "#00d4ff" : "#f43f5e";
    turnStatus.innerHTML = `<strong style="color: ${color};">🎉 ${winnerName} Wins!</strong>`;

    if (combo) {
      combo.forEach(idx => cells[idx].classList.add("winner"));
    }

    [0, 100, 200].forEach((delay, i) => {
      setTimeout(() => playSound(800 + (i * 150), 0.1), delay);
    });
  }
}

// ----------------------------------------------------
// Minimax Smart AI Algorithm
// ----------------------------------------------------
function getBestMove() {
  // Try to find winning or blocking moves
  let bestScore = -Infinity;
  let move = -1;

  for (let i = 0; i < 9; i++) {
    if (board[i] === null) {
      board[i] = "O";
      let score = minimax(board, 0, false);
      board[i] = null;
      if (score > bestScore) {
        bestScore = score;
        move = i;
      }
    }
  }
  return move;
}

function minimax(b, depth, isMaximizing) {
  if (checkWin(b, "O")) return 10 - depth;
  if (checkWin(b, "X")) return depth - 10;
  if (b.every(c => c !== null)) return 0;

  if (isMaximizing) {
    let maxScore = -Infinity;
    for (let i = 0; i < 9; i++) {
      if (b[i] === null) {
        b[i] = "O";
        maxScore = Math.max(maxScore, minimax(b, depth + 1, false));
        b[i] = null;
      }
    }
    return maxScore;
  } else {
    let minScore = Infinity;
    for (let i = 0; i < 9; i++) {
      if (b[i] === null) {
        b[i] = "X";
        minScore = Math.min(minScore, minimax(b, depth + 1, true));
        b[i] = null;
      }
    }
    return minScore;
  }
}

// Restart & Reset
function restartRound() {
  board = Array(9).fill(null);
  currentPlayer = "X";
  isGameActive = true;
  cells.forEach(cell => {
    cell.textContent = "";
    cell.className = "cell";
  });
  updateTurnStatus();
  playSound(500, 0.05);
}

function resetScores() {
  scores = { X: 0, O: 0, ties: 0 };
  scoreXEl.textContent = "0";
  scoreOEl.textContent = "0";
  scoreTiesEl.textContent = "0";
  restartRound();
}

// Event Listeners
cells.forEach(cell => cell.addEventListener("click", handleCellClick));
restartBtn.addEventListener("click", restartRound);
resetScoresBtn.addEventListener("click", resetScores);

modeAI.addEventListener("click", () => {
  if (!vsAI) {
    vsAI = true;
    modeAI.classList.add("active");
    modePvP.classList.remove("active");
    oPlayerLabel.textContent = "AI (O)";
    restartRound();
  }
});

modePvP.addEventListener("click", () => {
  if (vsAI) {
    vsAI = false;
    modePvP.classList.add("active");
    modeAI.classList.remove("active");
    oPlayerLabel.textContent = "Player O";
    restartRound();
  }
});
