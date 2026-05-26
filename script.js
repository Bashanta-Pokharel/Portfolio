const root = document.documentElement;
const loader = document.querySelector(".page-loader");
const themeToggle = document.querySelector(".theme-toggle");
const navToggle = document.querySelector(".nav-toggle");
const navLinks = document.querySelector(".nav-links");
const typingText = document.querySelector(".typing-text");
const backToTop = document.querySelector(".back-to-top");
const cookieConsent = document.querySelector("#cookie-consent");
const acceptCookies = document.querySelector("#accept-cookies");
const visitorCount = document.querySelector("#visitor-count");
const availabilityLabel = document.querySelector("#availability-label");
const toast = document.querySelector("#toast");
const contactForm = document.querySelector("#contact-form");
const formStatus = document.querySelector("#form-status");

const roles = [
  "Backend Developer",
  "BCA Student",
  "Problem Solver",
  "Full Stack Learner",
  "Database Enthusiast"
];

// Lightweight typing effect for the hero role line.
let roleIndex = 0;
let charIndex = 0;
let isDeleting = false;

window.addEventListener("load", () => {
  loader?.classList.add("is-hidden");
});

document.querySelector("#year").textContent = new Date().getFullYear();

const savedTheme = localStorage.getItem("theme") || "dark";
root.setAttribute("data-theme", savedTheme);
updateThemeIcon(savedTheme);

themeToggle?.addEventListener("click", () => {
  const nextTheme = root.getAttribute("data-theme") === "dark" ? "light" : "dark";
  root.setAttribute("data-theme", nextTheme);
  localStorage.setItem("theme", nextTheme);
  updateThemeIcon(nextTheme);
});

function updateThemeIcon(theme) {
  const icon = themeToggle?.querySelector("i");
  if (!icon) return;
  icon.className = theme === "dark" ? "fa-solid fa-moon" : "fa-solid fa-sun";
}

navToggle?.addEventListener("click", () => {
  const isOpen = navLinks.classList.toggle("is-open");
  document.body.classList.toggle("menu-open", isOpen);
  navToggle.setAttribute("aria-expanded", String(isOpen));
  navToggle.querySelector("i").className = isOpen ? "fa-solid fa-xmark" : "fa-solid fa-bars";
});

navLinks?.querySelectorAll("a").forEach((link) => {
  link.addEventListener("click", () => {
    navLinks.classList.remove("is-open");
    document.body.classList.remove("menu-open");
    navToggle?.setAttribute("aria-expanded", "false");
    const icon = navToggle?.querySelector("i");
    if (icon) icon.className = "fa-solid fa-bars";
  });
});

function typeRole() {
  if (!typingText) return;
  const currentRole = roles[roleIndex];
  typingText.textContent = currentRole.slice(0, charIndex);

  if (!isDeleting && charIndex < currentRole.length) {
    charIndex += 1;
    setTimeout(typeRole, 80);
    return;
  }

  if (!isDeleting && charIndex === currentRole.length) {
    isDeleting = true;
    setTimeout(typeRole, 1400);
    return;
  }

  if (isDeleting && charIndex > 0) {
    charIndex -= 1;
    setTimeout(typeRole, 42);
    return;
  }

  isDeleting = false;
  roleIndex = (roleIndex + 1) % roles.length;
  setTimeout(typeRole, 260);
}

typeRole();

const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add("is-visible");
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.14 });

// Local storage is used only for client-side personalization.
document.querySelectorAll(".reveal").forEach((element) => revealObserver.observe(element));

async function setVisitorCount() {
  if (!visitorCount) return;

  try {
    const response = await fetch("https://countapi.mileshilliard.com/api/v1/hit/bashanta_pokharel_portfolio_visits");
    const data = await response.json();
    visitorCount.textContent = Number(data.value || 0).toLocaleString();
  } catch (error) {
    visitorCount.textContent = "--";
  }
}

setVisitorCount();

window.addEventListener("scroll", () => {
  backToTop?.classList.toggle("is-visible", window.scrollY > 620);
});

backToTop?.addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});

function setAvailability() {
  if (!availabilityLabel) return;
  const hour = Number(new Intl.DateTimeFormat("en-US", {
    timeZone: "Asia/Kathmandu",
    hour: "numeric",
    hour12: false
  }).format(new Date()));
  const isAvailable = hour >= 9 && hour < 21;
  availabilityLabel.textContent = isAvailable ? "Available today" : "Replies soon";
  availabilityLabel.style.borderColor = isAvailable ? "rgba(34, 197, 94, 0.38)" : "rgba(245, 158, 11, 0.42)";
}

setAvailability();

if (localStorage.getItem("cookieOk") !== "true") {
  cookieConsent?.classList.add("is-visible");
}

acceptCookies?.addEventListener("click", () => {
  localStorage.setItem("cookieOk", "true");
  cookieConsent?.classList.remove("is-visible");
});

function showToast(message) {
  if (!toast) return;
  toast.textContent = message;
  toast.classList.add("is-visible");
  window.clearTimeout(showToast.timer);
  showToast.timer = window.setTimeout(() => toast.classList.remove("is-visible"), 3200);
}

document.querySelectorAll(".demo-link, .article-card").forEach((link) => {
  link.addEventListener("click", (event) => {
    if (link.getAttribute("href") === "#") {
      event.preventDefault();
      showToast("This link is ready to connect after deployment.");
    }
  });
});

contactForm?.addEventListener("submit", async (event) => {
  event.preventDefault();

  const accessKey = contactForm.querySelector('input[name="access_key"]')?.value;
  if (accessKey === "YOUR_WEB3FORMS_ACCESS_KEY") {
    formStatus.textContent = "Add your Web3Forms access key before publishing the contact form.";
    showToast("Replace YOUR_WEB3FORMS_ACCESS_KEY in index.html.");
    return;
  }

  formStatus.textContent = "Sending message...";

  try {
    const response = await fetch(contactForm.action, {
      method: "POST",
      body: new FormData(contactForm),
      headers: { "Accept": "application/json" }
    });

    const contentType = response.headers.get("content-type") || "";
    const result = contentType.includes("application/json")
      ? await response.json()
      : { message: await response.text() };

    if (response.status === 404) {
      throw new Error("contact.php was not found. Start the PHP server from this portfolio folder.");
    }

    if (!response.ok || result.success === false) {
      throw new Error(result.message || "Unable to send message right now.");
    }

    formStatus.textContent = result.message || "Thank you. Your message has been sent.";
    contactForm.reset();
  } catch (error) {
    formStatus.textContent = error.message || "Please run the PHP server or email directly.";
  }
});
