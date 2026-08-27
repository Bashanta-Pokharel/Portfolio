// Audio Synthesizer for UI feedback (zero external audio files needed)
const audioCtx = (function() {
  let ctx = null;
  return function() {
    if (!ctx) {
      const AudioContext = window.AudioContext || window.webkitAudioContext;
      if (AudioContext) ctx = new AudioContext();
    }
    return ctx;
  };
})();

let soundEnabled = true;

function playBeep(freq = 600, duration = 0.08, type = 'sine') {
  if (!soundEnabled) return;
  try {
    const ctx = audioCtx();
    if (!ctx) return;
    if (ctx.state === 'suspended') ctx.resume();

    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    osc.type = type;
    osc.frequency.setValueAtTime(freq, ctx.currentTime);
    gain.gain.setValueAtTime(0.12, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + duration);
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.start();
    osc.stop(ctx.currentTime + duration);
  } catch (e) {
    // Silent fail if audio blocked by browser policy
  }
}

function playCompletionAlarm() {
  if (!soundEnabled) return;
  [0, 150, 300, 450, 600].forEach((delay, index) => {
    setTimeout(() => {
      playBeep(index % 2 === 0 ? 880 : 1100, 0.12, 'triangle');
    }, delay);
  });
}

// Sound toggle
const soundToggle = document.getElementById('sound-toggle');
if (soundToggle) {
  soundToggle.addEventListener('click', () => {
    soundEnabled = !soundEnabled;
    soundToggle.classList.toggle('active', soundEnabled);
    soundToggle.innerHTML = soundEnabled 
      ? '<i class="fa-solid fa-volume-high"></i>' 
      : '<i class="fa-solid fa-volume-xmark"></i>';
    if (soundEnabled) playBeep(700, 0.05);
  });
}

// ----------------------------------------------------
// Tab Navigation (Stopwatch vs Countdown Timer)
// ----------------------------------------------------
let currentMode = 'stopwatch';
const tabStopwatch = document.getElementById('tab-stopwatch');
const tabTimer = document.getElementById('tab-timer');
const stopwatchView = document.getElementById('stopwatch-view');
const timerView = document.getElementById('timer-view');

tabStopwatch.addEventListener('click', () => switchMode('stopwatch'));
tabTimer.addEventListener('click', () => switchMode('timer'));

function switchMode(mode) {
  currentMode = mode;
  playBeep(520, 0.04);
  if (mode === 'stopwatch') {
    tabStopwatch.classList.add('active');
    tabTimer.classList.remove('active');
    stopwatchView.classList.remove('hidden');
    timerView.classList.add('hidden');
  } else {
    tabTimer.classList.add('active');
    tabStopwatch.classList.remove('active');
    timerView.classList.remove('hidden');
    stopwatchView.classList.add('hidden');
  }
}

// ----------------------------------------------------
// STOPWATCH LOGIC
// ----------------------------------------------------
let swRunning = false;
let swStartTime = 0;
let swElapsedTime = 0;
let swTimerId = null;
let laps = [];

const swMin = document.getElementById('sw-min');
const swSec = document.getElementById('sw-sec');
const swMs = document.getElementById('sw-ms');
const swStatus = document.getElementById('sw-status');
const swStartBtn = document.getElementById('sw-start-btn');
const swResetBtn = document.getElementById('sw-reset-btn');
const swLapBtn = document.getElementById('sw-lap-btn');
const lapsList = document.getElementById('laps-list');
const stopwatchCircle = document.getElementById('stopwatch-circle');

const CIRCUMFERENCE = 2 * Math.PI * 126; // ~791.68px

function formatTimeParts(ms) {
  const totalSeconds = Math.floor(ms / 1000);
  const minutes = Math.floor(totalSeconds / 60);
  const seconds = totalSeconds % 60;
  const hundredths = Math.floor((ms % 1000) / 10);

  return {
    m: String(minutes).padStart(2, '0'),
    s: String(seconds).padStart(2, '0'),
    ms: String(hundredths).padStart(2, '0')
  };
}

function updateStopwatchUI(ms) {
  const parts = formatTimeParts(ms);
  swMin.textContent = parts.m;
  swSec.textContent = parts.s;
  swMs.textContent = parts.ms;

  // Ring rotates once every 60 seconds
  const secondsFraction = (ms % 60000) / 60000;
  stopwatchCircle.style.strokeDashoffset = CIRCUMFERENCE - (secondsFraction * CIRCUMFERENCE);
}

function startStopwatch() {
  swRunning = true;
  swStartTime = performance.now() - swElapsedTime;
  
  swStartBtn.innerHTML = '<i class="fa-solid fa-pause"></i> Pause';
  swStartBtn.classList.add('btn-pause');
  swResetBtn.disabled = false;
  swLapBtn.disabled = false;
  swStatus.textContent = 'RUNNING';
  swStatus.className = 'status-badge running';
  
  playBeep(650, 0.06);

  function step(timestamp) {
    if (!swRunning) return;
    swElapsedTime = performance.now() - swStartTime;
    updateStopwatchUI(swElapsedTime);
    swTimerId = requestAnimationFrame(step);
  }
  swTimerId = requestAnimationFrame(step);
}

function pauseStopwatch() {
  swRunning = false;
  cancelAnimationFrame(swTimerId);
  
  swStartBtn.innerHTML = '<i class="fa-solid fa-play"></i> Resume';
  swStartBtn.classList.remove('btn-pause');
  swStatus.textContent = 'PAUSED';
  swStatus.className = 'status-badge paused';
  
  playBeep(450, 0.06);
}

function toggleStopwatch() {
  if (swRunning) {
    pauseStopwatch();
  } else {
    startStopwatch();
  }
}

function resetStopwatch() {
  swRunning = false;
  cancelAnimationFrame(swTimerId);
  swElapsedTime = 0;
  laps = [];
  
  updateStopwatchUI(0);
  stopwatchCircle.style.strokeDashoffset = CIRCUMFERENCE;
  
  swStartBtn.innerHTML = '<i class="fa-solid fa-play"></i> Start';
  swStartBtn.classList.remove('btn-pause');
  swResetBtn.disabled = true;
  swLapBtn.disabled = true;
  swStatus.textContent = 'READY';
  swStatus.className = 'status-badge';
  
  lapsList.innerHTML = '<li class="empty-laps">No laps recorded yet. Press "Lap" while running.</li>';
  playBeep(350, 0.08);
}

function addLap() {
  if (!swRunning && swElapsedTime === 0) return;
  
  const currentTotal = swElapsedTime;
  const previousLapTotal = laps.length > 0 ? laps[0].overallMs : 0;
  const lapSplit = currentTotal - previousLapTotal;
  const lapNumber = laps.length + 1;

  laps.unshift({
    num: lapNumber,
    splitMs: lapSplit,
    overallMs: currentTotal
  });

  renderLaps();
  playBeep(800, 0.05);
}

function renderLaps() {
  if (laps.length === 0) {
    lapsList.innerHTML = '<li class="empty-laps">No laps recorded yet. Press "Lap" while running.</li>';
    return;
  }

  // Determine fastest and slowest
  let minSplit = Infinity;
  let maxSplit = -Infinity;

  if (laps.length >= 2) {
    laps.forEach(l => {
      if (l.splitMs < minSplit) minSplit = l.splitMs;
      if (l.splitMs > maxSplit) maxSplit = l.splitMs;
    });
  }

  lapsList.innerHTML = laps.map(lap => {
    let tagClass = '';
    if (laps.length >= 2) {
      if (lap.splitMs === minSplit) tagClass = 'fastest';
      else if (lap.splitMs === maxSplit) tagClass = 'slowest';
    }

    const split = formatTimeParts(lap.splitMs);
    const overall = formatTimeParts(lap.overallMs);

    return `
      <li class="${tagClass}">
        <span>#${String(lap.num).padStart(2, '0')}</span>
        <span>+${split.m}:${split.s}.${split.ms}</span>
        <span>${overall.m}:${overall.s}.${overall.ms}</span>
      </li>
    `;
  }).join('');
}

swStartBtn.addEventListener('click', toggleStopwatch);
swResetBtn.addEventListener('click', resetStopwatch);
swLapBtn.addEventListener('click', addLap);

// ----------------------------------------------------
// COUNTDOWN TIMER LOGIC
// ----------------------------------------------------
let cdRunning = false;
let cdTotalDurationSec = 300; // 5 mins default
let cdRemainingSec = 300;
let cdInterval = null;

const inputHours = document.getElementById('input-hours');
const inputMinutes = document.getElementById('input-minutes');
const inputSeconds = document.getElementById('input-seconds');
const cdHrs = document.getElementById('cd-hrs');
const cdMin = document.getElementById('cd-min');
const cdSec = document.getElementById('cd-sec');
const cdStatus = document.getElementById('cd-status');
const cdStartBtn = document.getElementById('cd-start-btn');
const cdResetBtn = document.getElementById('cd-reset-btn');
const timerCircle = document.getElementById('timer-circle');
const timerInputs = document.getElementById('timer-inputs');

function getInputsTotalSeconds() {
  const h = parseInt(inputHours.value, 10) || 0;
  const m = parseInt(inputMinutes.value, 10) || 0;
  const s = parseInt(inputSeconds.value, 10) || 0;
  return (h * 3600) + (m * 60) + s;
}

function updateCountdownUI(seconds) {
  const h = Math.floor(seconds / 3600);
  const m = Math.floor((seconds % 3600) / 60);
  const s = seconds % 60;

  cdHrs.textContent = String(h).padStart(2, '0');
  cdMin.textContent = String(m).padStart(2, '0');
  cdSec.textContent = String(s).padStart(2, '0');

  if (cdTotalDurationSec > 0) {
    const fraction = seconds / cdTotalDurationSec;
    timerCircle.style.strokeDashoffset = CIRCUMFERENCE - (fraction * CIRCUMFERENCE);
  }
}

function syncInputsWithRemaining() {
  const total = getInputsTotalSeconds();
  if (total > 0 && !cdRunning) {
    cdTotalDurationSec = total;
    cdRemainingSec = total;
    updateCountdownUI(cdRemainingSec);
  }
}

[inputHours, inputMinutes, inputSeconds].forEach(input => {
  input.addEventListener('input', syncInputsWithRemaining);
});

// Quick Presets
document.querySelectorAll('.preset-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const addSeconds = parseInt(btn.dataset.sec, 10);
    const current = getInputsTotalSeconds();
    const newTotal = current + addSeconds;

    const h = Math.floor(newTotal / 3600);
    const m = Math.floor((newTotal % 3600) / 60);
    const s = newTotal % 60;

    inputHours.value = h;
    inputMinutes.value = m;
    inputSeconds.value = s;

    cdTotalDurationSec = newTotal;
    cdRemainingSec = newTotal;
    updateCountdownUI(cdRemainingSec);
    playBeep(600, 0.04);
  });
});

function startCountdown() {
  if (cdRemainingSec <= 0) {
    cdRemainingSec = getInputsTotalSeconds();
    cdTotalDurationSec = cdRemainingSec;
  }
  if (cdRemainingSec <= 0) return;

  cdRunning = true;
  cdStartBtn.innerHTML = '<i class="fa-solid fa-pause"></i> Pause';
  cdStartBtn.classList.add('btn-pause');
  cdStatus.textContent = 'RUNNING';
  cdStatus.className = 'status-badge running';
  timerInputs.style.opacity = '0.5';

  playBeep(650, 0.06);

  cdInterval = setInterval(() => {
    if (cdRemainingSec > 0) {
      cdRemainingSec--;
      updateCountdownUI(cdRemainingSec);

      if (cdRemainingSec <= 5 && cdRemainingSec > 0) {
        playBeep(700, 0.05);
      }

      if (cdRemainingSec === 0) {
        clearInterval(cdInterval);
        cdRunning = false;
        cdStartBtn.innerHTML = '<i class="fa-solid fa-play"></i> Start';
        cdStartBtn.classList.remove('btn-pause');
        cdStatus.textContent = 'COMPLETED!';
        cdStatus.className = 'status-badge paused';
        timerInputs.style.opacity = '1';
        playCompletionAlarm();
      }
    }
  }, 1000);
}

function pauseCountdown() {
  cdRunning = false;
  clearInterval(cdInterval);
  cdStartBtn.innerHTML = '<i class="fa-solid fa-play"></i> Resume';
  cdStartBtn.classList.remove('btn-pause');
  cdStatus.textContent = 'PAUSED';
  cdStatus.className = 'status-badge paused';
  playBeep(450, 0.06);
}

function toggleCountdown() {
  if (cdRunning) {
    pauseCountdown();
  } else {
    startCountdown();
  }
}

function resetCountdown() {
  cdRunning = false;
  clearInterval(cdInterval);
  const total = getInputsTotalSeconds() || 300;
  cdTotalDurationSec = total;
  cdRemainingSec = total;
  updateCountdownUI(cdRemainingSec);
  
  cdStartBtn.innerHTML = '<i class="fa-solid fa-play"></i> Start';
  cdStartBtn.classList.remove('btn-pause');
  cdStatus.textContent = 'SET TIME';
  cdStatus.className = 'status-badge';
  timerInputs.style.opacity = '1';
  playBeep(350, 0.08);
}

cdStartBtn.addEventListener('click', toggleCountdown);
cdResetBtn.addEventListener('click', resetCountdown);

// Initial countdown setup
updateCountdownUI(300);

// ----------------------------------------------------
// Global Keyboard Shortcuts
// ----------------------------------------------------
document.addEventListener('keydown', (e) => {
  // Ignore typing inside inputs
  if (e.target.tagName === 'INPUT') return;

  if (e.code === 'Space') {
    e.preventDefault();
    if (currentMode === 'stopwatch') toggleStopwatch();
    else toggleCountdown();
  } else if (e.key === 'l' || e.key === 'L') {
    if (currentMode === 'stopwatch') addLap();
  } else if (e.key === 'r' || e.key === 'R') {
    if (currentMode === 'stopwatch') resetStopwatch();
    else resetCountdown();
  }
});
