// Nepal Standard Time (GMT+5:45) Clock & Dual Numeral Engine
const nepaliNumerals = ["१", "२", "३", "४", "५", "६", "७", "८", "९", "१०", "११", "१२"];
const englishNumerals = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"];

const nepaliDays = ["आइतबार", "सोमबार", "मङ्गलबार", "बुधबार", "बिहीबार", "शुक्रबार", "शनिबार"];
const nepaliMonths = ["बैशाख", "जेठ", "असार", "साउन", "भदौ", "असोज", "कार्तिक", "मंसिर", "पुस", "माघ", "फागुन", "चैत"];

let isNepaliNumerals = true;
let activeTimezone = "npt"; // 'npt' (Asia/Kathmandu) or 'local'

const hourHand = document.getElementById("hour-hand");
const minuteHand = document.getElementById("minute-hand");
const secondHand = document.getElementById("second-hand");
const digitalClock = document.getElementById("digital-clock");
const digitalAmPm = document.getElementById("digital-ampm");
const dateStringEl = document.getElementById("date-string");
const btnNumNep = document.getElementById("btn-num-nep");
const btnNumEng = document.getElementById("btn-num-eng");
const tabNpt = document.getElementById("tab-npt");
const tabLocal = document.getElementById("tab-local");
const numeralElements = document.querySelectorAll(".clock-dial label .numeral");

function updateNumerals() {
  numeralElements.forEach((el, index) => {
    el.textContent = isNepaliNumerals ? nepaliNumerals[index] : englishNumerals[index];
    el.style.fontFamily = isNepaliNumerals ? "var(--font-nepali)" : "var(--font-sans)";
  });
}

function getTimeForZone() {
  const now = new Date();
  if (activeTimezone === "npt") {
    // Format to Asia/Kathmandu
    const nptString = now.toLocaleString("en-US", { timeZone: "Asia/Kathmandu" });
    return new Date(nptString);
  }
  return now;
}

function updateClock() {
  const targetDate = getTimeForZone();
  const ms = targetDate.getMilliseconds();
  const seconds = targetDate.getSeconds() + (ms / 1000);
  const minutes = targetDate.getMinutes() + (seconds / 60);
  const hours = (targetDate.getHours() % 12) + (minutes / 60);

  const secDeg = (seconds / 60) * 360;
  const minDeg = (minutes / 60) * 360;
  const hrDeg = (hours / 12) * 360;

  secondHand.style.transform = `rotate(${secDeg}deg)`;
  minuteHand.style.transform = `rotate(${minDeg}deg)`;
  hourHand.style.transform = `rotate(${hrDeg}deg)`;

  // Digital Display
  const rawHours = targetDate.getHours();
  const displayHours = rawHours % 12 || 12;
  const displayMinutes = String(targetDate.getMinutes()).padStart(2, "0");
  const displaySeconds = String(targetDate.getSeconds()).padStart(2, "0");
  const ampm = rawHours >= 12 ? "PM" : "AM";

  digitalClock.textContent = `${String(displayHours).padStart(2, "0")}:${displayMinutes}:${displaySeconds}`;
  digitalAmPm.textContent = ampm;

  // Date & Day Display
  const dayName = nepaliDays[targetDate.getDay()];
  const engDayName = targetDate.toLocaleDateString("en-US", { weekday: "long" });
  const engDate = targetDate.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" });

  if (activeTimezone === "npt") {
    dateStringEl.textContent = `${dayName} (${engDayName}) • ${engDate} • NPT (GMT+5:45)`;
  } else {
    dateStringEl.textContent = `${engDayName} • ${engDate} • Local Device Time`;
  }
}

// Numerals Toggle
btnNumNep.addEventListener("click", () => {
  if (!isNepaliNumerals) {
    isNepaliNumerals = true;
    btnNumNep.classList.add("active");
    btnNumEng.classList.remove("active");
    updateNumerals();
  }
});

btnNumEng.addEventListener("click", () => {
  if (isNepaliNumerals) {
    isNepaliNumerals = false;
    btnNumEng.classList.add("active");
    btnNumNep.classList.remove("active");
    updateNumerals();
  }
});

// Timezone mode tabs
tabNpt.addEventListener("click", () => {
  if (activeTimezone !== "npt") {
    activeTimezone = "npt";
    tabNpt.classList.add("active");
    tabLocal.classList.remove("active");
    updateClock();
  }
});

tabLocal.addEventListener("click", () => {
  if (activeTimezone !== "local") {
    activeTimezone = "local";
    tabLocal.classList.add("active");
    tabNpt.classList.remove("active");
    updateClock();
  }
});

// Initialize
updateNumerals();
setInterval(updateClock, 50);
updateClock();
