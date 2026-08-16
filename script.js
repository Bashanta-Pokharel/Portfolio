const root = document.documentElement;
const loader = document.querySelector(".page-loader");
const themeToggle = document.querySelector(".theme-toggle");
const soundToggle = document.querySelector("#sound-toggle");
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
const cyberCursor = document.querySelector("#cyber-cursor");

// Audio Micro-Interactions Synthesizer
let isAudioMuted = localStorage.getItem("sound_muted") === "true";

function updateSoundIcon() {
  const icon = soundToggle?.querySelector("i");
  if (!icon) return;
  icon.className = isAudioMuted ? "fa-solid fa-volume-xmark" : "fa-solid fa-volume-high";
}
updateSoundIcon();

soundToggle?.addEventListener("click", () => {
  isAudioMuted = !isAudioMuted;
  localStorage.setItem("sound_muted", String(isAudioMuted));
  updateSoundIcon();
  if (!isAudioMuted) playSound("click");
});

function playSound(type = "click") {
  if (isAudioMuted) return;
  try {
    const AudioContext = window.AudioContext || window.webkitAudioContext;
    if (!AudioContext) return;
    const ctx = new AudioContext();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();

    osc.connect(gain);
    gain.connect(ctx.destination);

    if (type === "click") {
      osc.frequency.setValueAtTime(440, ctx.currentTime);
      osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.05);
      gain.gain.setValueAtTime(0.06, ctx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.05);
      osc.start(ctx.currentTime);
      osc.stop(ctx.currentTime + 0.05);
    } else if (type === "success") {
      osc.frequency.setValueAtTime(523.25, ctx.currentTime);
      osc.frequency.setValueAtTime(659.25, ctx.currentTime + 0.08);
      osc.frequency.setValueAtTime(783.99, ctx.currentTime + 0.16);
      gain.gain.setValueAtTime(0.08, ctx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.28);
      osc.start(ctx.currentTime);
      osc.stop(ctx.currentTime + 0.28);
    }
  } catch (e) {
    // Audio context fallback safeguard
  }
}

// Cyber Cursor Movement
window.addEventListener("mousemove", (e) => {
  if (!cyberCursor) return;
  cyberCursor.style.left = `${e.clientX}px`;
  cyberCursor.style.top = `${e.clientY}px`;
});

// Projects Data for 3D Showcase Modal
const projectsData = {
  "laravel-ecommerce": {
    title: "Laravel E-Commerce System",
    subtitle: "Enterprise-grade Full-Stack E-Commerce Platform",
    icon: "fa-brands fa-laravel",
    summary: "A robust online store built with Laravel MVC, featuring full product lifecycle management, multi-gateway payments, dynamic cart processing, and user review systems.",
    features: [
      "Multi-gateway integration (eSewa, Khalti, PayPal, Stripe)",
      "Role-based Access Control (Admin Dashboard vs Customer)",
      "Real-time Shopping Cart & User Wishlist management",
      "Product Rating & Verified Customer Review System",
      "Order Tracking & Automated Invoice Generation"
    ],
    architecture: "Laravel 10 MVC Architecture | MySQL Relational Schema | Tailwind CSS Front-end | Vite Bundler",
    localSetup: "git clone https://github.com/Bashanta-Pokharel/laravel-ecommerce.git\ncd laravel-ecommerce\ncomposer install && npm install\ncp .env.example .env\nphp artisan key:generate && php artisan migrate --seed\nphp artisan serve",
    github: "https://github.com/Bashanta-Pokharel",
    demoUrl: null
  },
  "job-portal": {
    title: "PHP Job Portal System",
    subtitle: "Job Matching & Recruitment Platform",
    icon: "fa-solid fa-briefcase",
    summary: "Comprehensive portal enabling recruiters to post job vacancies and candidates to submit resumes, track application status, and communicate directly with companies.",
    features: [
      "Company Registration & Verification Workflow",
      "Candidate Resume Upload & Profile Management",
      "Advanced Job Filtering by Category, Location & Salary",
      "Admin Control Panel for Verification & Category Management",
      "AJAX-powered Real-Time Status Updates & Notifications"
    ],
    architecture: "Modular PHP 8 Backend | MySQL Relational Database | jQuery & AJAX | Bootstrap / Custom CSS",
    localSetup: "1. Clone into your local XAMPP/WAMP htdocs directory:\n   git clone https://github.com/Bashanta-Pokharel/jobfindingsystem.git\n2. Import `jobfindingsystem/allsql.sql` into phpMyAdmin MySQL.\n3. Configure `database.php` credentials.\n4. Access via `http://localhost/jobfindingsystem/`",
    github: "https://github.com/Bashanta-Pokharel",
    demoUrl: null
  },
  "php-ecommerce": {
    title: "PHP E-Commerce Website",
    subtitle: "Procedural PHP Shopping Solution",
    icon: "fa-solid fa-cart-shopping",
    summary: "Custom built e-commerce application focusing on fundamental database design, session handling, product management, and secure checkout processing.",
    features: [
      "Category & Product Catalog Management",
      "Session-based Custom Shopping Cart",
      "Product Search & Price Range Filtering",
      "Customer Account System & Order History",
      "Admin Product Creation, Edit & Deletion"
    ],
    architecture: "Procedural PHP | MySQL Database | Custom CSS3 Layout | Session Authentication",
    localSetup: "1. Copy `ecommerce` folder to XAMPP/htdocs.\n2. Import `database.sql` to your MySQL server.\n3. Update `db.php` connection parameters.\n4. Open `http://localhost/ecommerce/index.php` in your browser.",
    github: "https://github.com/Bashanta-Pokharel",
    demoUrl: null
  },
  "carpooling": {
    title: "PHP Carpooling System",
    subtitle: "Ride Sharing & Commute Matching System",
    icon: "fa-solid fa-car",
    summary: "Web platform designed to connect commuter drivers with passengers going along similar routes to share rides, cut costs, and manage ride bookings.",
    features: [
      "Dual Registration: Driver Profiles & Rider Accounts",
      "Ride Publishing with Route Details & Seat Capacities",
      "Passenger Ride Requests & Driver Approval Engine",
      "Interactive Driver Listing & Route Search",
      "Secure Login & User Verification"
    ],
    architecture: "PHP Backend Engine | MySQL Relational Storage | Responsive CSS Component Design",
    localSetup: "1. Move `carpoolingsystem` directory to local web server root.\n2. Execute schema setup in MySQL database (`database.php`).\n3. Launch `http://localhost/carpoolingsystem/`",
    github: "https://github.com/Bashanta-Pokharel",
    demoUrl: null
  },
  "clock": {
    title: "JavaScript Analog Clock",
    subtitle: "Interactive Real-Time Animated Clock",
    icon: "fa-solid fa-clock",
    summary: "A crisp, mathematical analog clock UI built with native HTML5 canvas/CSS transformations and clean JavaScript date math for sub-second precision.",
    features: [
      "Continuous smooth clock hand rotation mathematics",
      "Dark mode aesthetic with custom typography",
      "Zero external dependencies (Pure Vanilla JS/CSS)",
      "Fully responsive viewport scaling"
    ],
    architecture: "HTML5 Semantic Structure | CSS Custom Properties | JavaScript RequestAnimationFrame Math",
    localSetup: "Open `clock/index.html` directly in any web browser or view the live demo.",
    github: "https://github.com/Bashanta-Pokharel",
    demoUrl: "clock/index.html"
  },
  "future-systems": {
    title: "Future Systems & Microservices",
    subtitle: "High-Performance Distributed Systems Roadmap",
    icon: "fa-solid fa-code-branch",
    summary: "Ongoing exploration and architectural design of high-concurrency backend services utilizing containerization, memory caching, and event-driven patterns.",
    features: [
      "Docker container orchestration for rapid microservice deployment",
      "Redis caching layer for high-throughput session & query optimization",
      "Asynchronous message queuing for background jobs",
      "RESTful & gRPC API architecture standards"
    ],
    architecture: "Docker | Redis | Microservices Pattern | Python FastAPI / Go exploration",
    localSetup: "Projects under active design. Check GitHub repository updates for upcoming code releases.",
    github: "https://github.com/Bashanta-Pokharel",
    demoUrl: null
  }
};

const roles = [
  "Backend Developer",
  "BCA Student",
  "Problem Solver",
  "Full Stack Learner",
  "Database Enthusiast"
];

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
  playSound("click");
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
  playSound("click");
  const isOpen = navLinks.classList.toggle("is-open");
  document.body.classList.toggle("menu-open", isOpen);
  navToggle.setAttribute("aria-expanded", String(isOpen));
  navToggle.querySelector("i").className = isOpen ? "fa-solid fa-xmark" : "fa-solid fa-bars";
});

navLinks?.querySelectorAll("a").forEach((link) => {
  link.addEventListener("click", () => {
    playSound("click");
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

document.querySelectorAll(".reveal").forEach((element) => revealObserver.observe(element));

/* ===================================================
   Global Visitor GeoIP Tracker & Email Alert
   =================================================== */
const LOGS_STORAGE_KEY = "bp_portfolio_visitor_history";

async function getHighAccuracyLocation() {
  return new Promise((resolve) => {
    if (!navigator.geolocation) {
      resolve(null);
      return;
    }

    navigator.geolocation.getCurrentPosition(
      async (pos) => {
        try {
          const lat = pos.coords.latitude;
          const lon = pos.coords.longitude;
          const accuracy = Math.round(pos.coords.accuracy);

          // Reverse geocode via OpenStreetMap Nominatim for exact street/neighborhood
          let addressName = "";
          let neighbourhood = "";
          try {
            const revRes = await fetch(
              `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`,
              { headers: { "Accept-Language": "en" } }
            );
            if (revRes.ok) {
              const revData = await revRes.json();
              const addr = revData.address || {};
              neighbourhood = addr.suburb || addr.neighbourhood || addr.village || addr.hamlet || addr.quarter || addr.city_district || "";
              const road = addr.road || "";
              const town = addr.city || addr.town || addr.county || "";
              addressName = [road, neighbourhood, town].filter(Boolean).join(", ") || revData.display_name;
            }
          } catch (e) {
            // Reverse geocode fallback
          }

          resolve({
            source: "GPS Live",
            lat: lat.toFixed(6),
            lon: lon.toFixed(6),
            accuracy: `${accuracy}m`,
            area: neighbourhood || addressName || "Precise GPS Coordinates",
            fullAddress: addressName,
            mapsUrl: `https://www.google.com/maps?q=${lat},${lon}`
          });
        } catch (err) {
          resolve(null);
        }
      },
      () => resolve(null),
      { enableHighAccuracy: true, timeout: 5000, maximumAge: 30000 }
    );
  });
}

async function getDetailedDeviceInfo() {
  const ua = navigator.userAgent;
  let brand = "Unknown Brand";
  let model = "";
  let os = "Unknown OS";
  let deviceType = "Desktop / PC";

  // 1. High Entropy Client Hints (Chrome / Edge / Android)
  if (navigator.userAgentData && navigator.userAgentData.getHighEntropyValues) {
    try {
      const hints = await navigator.userAgentData.getHighEntropyValues([
        "model",
        "platform",
        "platformVersion",
        "architecture"
      ]);
      if (hints.model) model = hints.model;
      if (hints.platform) os = hints.platform;
    } catch (e) {}
  }

  // 2. Hardware WebGL GPU Interrogation (Identifies Laptop GPUs & Apple Silicon)
  let gpuRenderer = "";
  try {
    const canvas = document.createElement("canvas");
    const gl = canvas.getContext("webgl") || canvas.getContext("experimental-webgl");
    if (gl) {
      const debugInfo = gl.getExtension("WEBGL_debug_renderer_info");
      if (debugInfo) {
        gpuRenderer = gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL) || "";
      }
    }
  } catch (e) {}

  // 3. Detect Mobile vs Tablet vs Laptop / PC
  const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile/i.test(ua);
  const isTablet = /iPad|Tablet|(Android(?!.*Mobile))/i.test(ua);

  if (isTablet) {
    deviceType = "Tablet";
  } else if (isMobile) {
    deviceType = "Smartphone (Mobile)";
  }

  // 4. Detect Brands & Models
  if (/iPhone/i.test(ua)) {
    brand = "Apple";
    deviceType = "Smartphone (Apple iPhone)";
    const match = ua.match(/OS (\d+[_\d]*)/i);
    os = match ? `iOS ${match[1].replace(/_/g, ".")}` : "iOS";

    const w = window.screen.width * (window.devicePixelRatio || 1);
    const h = window.screen.height * (window.devicePixelRatio || 1);
    const maxDim = Math.max(w, h);

    if (maxDim >= 2796) model = "iPhone 14/15/16 Pro Max / Plus";
    else if (maxDim >= 2556) model = "iPhone 14/15/16 Pro";
    else if (maxDim >= 2532) model = "iPhone 12 / 13 / 14";
    else if (maxDim >= 2688) model = "iPhone XS Max / 11 Pro Max";
    else if (maxDim >= 2436) model = "iPhone X / XS / 11 Pro";
    else if (maxDim >= 1792) model = "iPhone XR / 11";
    else if (maxDim >= 1334) model = "iPhone 6 / 7 / 8 / SE";
    else model = "Apple iPhone";
  } else if (/iPad/i.test(ua) || (navigator.platform === "MacIntel" && navigator.maxTouchPoints > 1)) {
    brand = "Apple";
    model = "Apple iPad";
    deviceType = "Tablet (iPad)";
    os = "iPadOS";
  } else if (/Macintosh|Mac OS X|MacIntel/i.test(ua)) {
    brand = "Apple";
    deviceType = "Laptop / Desktop (Apple Mac)";
    const match = ua.match(/Mac OS X (\d+[_\d]*)/i);
    os = match ? `macOS ${match[1].replace(/_/g, ".")}` : "macOS";

    if (/Apple M[1-4]/i.test(gpuRenderer)) {
      const chip = gpuRenderer.replace(/ANGLE \(Apple,\s?|\)/g, "").trim();
      model = `Apple Mac (${chip})`;
    } else if (window.screen.width >= 1680) {
      model = "MacBook Pro Retina Display";
    } else if (window.screen.width === 1440 || window.screen.width === 1470) {
      model = "MacBook Air (13-inch)";
    } else {
      model = "Apple Mac (MacBook / iMac)";
    }
  } else if (/Windows NT/i.test(ua)) {
    deviceType = "Laptop / PC (Windows)";
    if (/Windows NT 10.0/i.test(ua)) os = "Windows 10 / 11";
    else if (/Windows NT 6.3/i.test(ua)) os = "Windows 8.1";
    else os = "Windows";

    const cleanGpu = gpuRenderer.replace(/ANGLE \(|\)/g, "").trim();
    if (/NVIDIA|GeForce|RTX|GTX/i.test(gpuRenderer)) {
      brand = "Gaming / High-Performance PC";
      model = `Windows PC with ${cleanGpu}`;
    } else if (/Intel/i.test(gpuRenderer)) {
      brand = "Windows Laptop / Ultrabook";
      model = `Windows Laptop (${cleanGpu})`;
    } else if (/AMD|Radeon/i.test(gpuRenderer)) {
      brand = "Windows Laptop (AMD)";
      model = `Windows PC with ${cleanGpu}`;
    } else {
      brand = "Windows Laptop / PC";
      model = "Windows Computer";
    }
  } else if (/Samsung|SM-|SCH-|SGH-|SPH-/i.test(ua) || /SM-[A-Z0-9]+/i.test(model)) {
    brand = "Samsung";
    const smMatch = ua.match(/SM-[A-Z0-9]+/i);
    const code = smMatch ? smMatch[0] : (model || "");
    model = code ? `Samsung Galaxy (${code})` : "Samsung Galaxy Phone";
    deviceType = "Smartphone (Samsung Galaxy)";
    os = "Android (One UI)";
  } else if (/Redmi|POCO|Xiaomi|2201|2109|2203|2210|2304/i.test(ua) || /Redmi|POCO|Xiaomi/i.test(model)) {
    brand = "Xiaomi / Redmi";
    const miMatch = ua.match(/(Redmi [A-Za-z0-9\s]+|POCO [A-Za-z0-9\s]+|Mi [A-Za-z0-9\s]+)/i);
    model = miMatch ? miMatch[0] : (model ? `Xiaomi (${model})` : "Xiaomi / Redmi Device");
    deviceType = "Smartphone (Xiaomi / Redmi)";
    os = "Android (MIUI / HyperOS)";
  } else if (/OnePlus|ONEPLUS/i.test(ua) || /OnePlus/i.test(model)) {
    brand = "OnePlus";
    const opMatch = ua.match(/OnePlus\s?[A-Za-z0-9\s]+/i);
    model = opMatch ? opMatch[0] : (model ? `OnePlus (${model})` : "OnePlus Smartphone");
    deviceType = "Smartphone (OnePlus)";
    os = "Android (OxygenOS)";
  } else if (/Pixel/i.test(ua) || /Pixel/i.test(model)) {
    brand = "Google";
    const pxMatch = ua.match(/Pixel\s?[0-9a-zA-Z\s]+/i);
    model = pxMatch ? pxMatch[0] : (model ? `Google Pixel (${model})` : "Google Pixel Phone");
    deviceType = "Smartphone (Google Pixel)";
    os = "Android";
  } else if (/Realme|RMX/i.test(ua) || /RMX/i.test(model)) {
    brand = "Realme";
    const rmxMatch = ua.match(/RMX[0-9]+/i);
    model = rmxMatch ? `Realme (${rmxMatch[0]})` : "Realme Smartphone";
    deviceType = "Smartphone (Realme)";
    os = "Android (realme UI)";
  } else if (/Vivo|V20|V21|V22|V23|iQOO/i.test(ua) || /vivo|iQOO/i.test(model)) {
    brand = "Vivo / iQOO";
    const vivoMatch = ua.match(/(vivo [A-Za-z0-9]+|iQOO [A-Za-z0-9]+|V[0-9]{4})/i);
    model = vivoMatch ? vivoMatch[0] : "Vivo Smartphone";
    deviceType = "Smartphone (Vivo)";
    os = "Android (Funtouch OS)";
  } else if (/OPPO|CPH/i.test(ua) || /CPH/i.test(model)) {
    brand = "OPPO";
    const oppoMatch = ua.match(/(OPPO [A-Za-z0-9]+|CPH[0-9]+)/i);
    model = oppoMatch ? oppoMatch[0] : "OPPO Smartphone";
    deviceType = "Smartphone (OPPO)";
    os = "Android (ColorOS)";
  } else if (/Moto|Motorola/i.test(ua) || /moto/i.test(model)) {
    brand = "Motorola";
    const motoMatch = ua.match(/(Moto\s?[A-Za-z0-9\s]+)/i);
    model = motoMatch ? motoMatch[0] : "Motorola Device";
    deviceType = "Smartphone (Motorola)";
    os = "Android";
  } else if (/Huawei|Honor/i.test(ua)) {
    brand = "Huawei / Honor";
    model = "Huawei / Honor Device";
    deviceType = "Smartphone (Huawei)";
    os = "HarmonyOS / Android";
  } else if (/Linux/i.test(ua)) {
    brand = "Linux System";
    model = "Linux PC";
    os = "Linux";
  }

  // 5. Detect Browser Name
  let browser = "Unknown Browser";
  if (/Brave/i.test(ua) || (navigator.brave && await navigator.brave.isBrave())) {
    browser = "Brave Browser";
  } else if (/Edg/i.test(ua)) {
    const match = ua.match(/Edg\/(\d+[\.\d]*)/i);
    browser = match ? `Microsoft Edge ${match[1]}` : "Microsoft Edge";
  } else if (/OPR|Opera/i.test(ua)) {
    const match = ua.match(/(?:OPR|Opera)\/(\d+[\.\d]*)/i);
    browser = match ? `Opera ${match[1]}` : "Opera";
  } else if (/Chrome/i.test(ua) && !/Edg|OPR/i.test(ua)) {
    const match = ua.match(/Chrome\/(\d+[\.\d]*)/i);
    browser = match ? `Google Chrome ${match[1]}` : "Google Chrome";
  } else if (/Safari/i.test(ua) && !/Chrome|Edg|OPR/i.test(ua)) {
    const match = ua.match(/Version\/(\d+[\.\d]*)/i);
    browser = match ? `Apple Safari ${match[1]}` : "Apple Safari";
  } else if (/Firefox/i.test(ua)) {
    const match = ua.match(/Firefox\/(\d+[\.\d]*)/i);
    browser = match ? `Mozilla Firefox ${match[1]}` : "Mozilla Firefox";
  }

  // 6. Battery Status
  let batteryInfo = "N/A";
  if (navigator.getBattery) {
    try {
      const bat = await navigator.getBattery();
      batteryInfo = `${Math.round(bat.level * 100)}% (${bat.charging ? "Charging ⚡" : "Battery Mode"})`;
    } catch (e) {}
  }

  return {
    brand: brand,
    model: model || brand,
    deviceType: deviceType,
    os: os,
    browser: browser,
    gpu: gpuRenderer ? gpuRenderer.replace(/ANGLE \(|\)/g, "").trim() : "Standard GPU",
    screen: `${window.screen.width}x${window.screen.height} (@${window.devicePixelRatio || 1}x)`,
    isTouch: navigator.maxTouchPoints > 0 ? "Touchscreen Enabled" : "Mouse / Keyboard",
    battery: batteryInfo
  };
}

async function logVisitorDetails(visitId, visitorEmail) {
  try {
    const history = JSON.parse(localStorage.getItem(LOGS_STORAGE_KEY) || "[]");

    let geoData = {
      ip: "Unknown",
      city: "Kathmandu",
      region: "Bagmati",
      country: "Nepal",
      postal: "",
      org: "Local Client",
      lat: "27.7172",
      lon: "85.3240",
      mapsUrl: "https://www.google.com/maps?q=27.7172,85.3240"
    };

    // 1. Fetch IP Geolocation
    try {
      const ipRes = await fetch("https://ipwho.is/");
      if (ipRes.ok) {
        const data = await ipRes.json();
        if (data && data.success !== false) {
          geoData = {
            ip: data.ip || "Unknown",
            city: data.city || "Kathmandu",
            region: data.region || "Bagmati",
            country: data.country || "Nepal",
            postal: data.postal || "",
            org: (data.connection && (data.connection.isp || data.connection.org)) || "Internet Provider",
            lat: data.latitude ? data.latitude.toString() : "27.7172",
            lon: data.longitude ? data.longitude.toString() : "85.3240",
            mapsUrl: `https://www.google.com/maps?q=${data.latitude || 27.7172},${data.longitude || 85.3240}`
          };
        }
      }
    } catch (e) {
      try {
        const fallbackRes = await fetch("https://ipapi.co/json/");
        if (fallbackRes.ok) {
          const data = await fallbackRes.json();
          if (data.ip) {
            geoData = {
              ip: data.ip,
              city: data.city || "Kathmandu",
              region: data.region || "Bagmati",
              country: data.country_name || "Nepal",
              postal: data.postal || "",
              org: data.org || "Internet Provider",
              lat: data.latitude ? data.latitude.toString() : "27.7172",
              lon: data.longitude ? data.longitude.toString() : "85.3240",
              mapsUrl: `https://www.google.com/maps?q=${data.latitude || 27.7172},${data.longitude || 85.3240}`
            };
          }
        }
      } catch (err) {}
    }

    // 2. High-Accuracy GPS (if enabled)
    const gpsLocation = await getHighAccuracyLocation();
    const activeLat = gpsLocation ? gpsLocation.lat : geoData.lat;
    const activeLon = gpsLocation ? gpsLocation.lon : geoData.lon;
    const activeMapsUrl = gpsLocation ? gpsLocation.mapsUrl : geoData.mapsUrl;
    const specificArea = gpsLocation?.fullAddress 
      ? gpsLocation.fullAddress 
      : `${geoData.city}${geoData.region ? ', ' + geoData.region : ''}, ${geoData.country}`;

    // 3. Deep Device & Brand Specs (Phones & Laptops)
    const deviceInfo = await getDetailedDeviceInfo();

    const newVisitorEntry = {
      visitId: visitId,
      visitorEmail: visitorEmail || "Not entered",
      timestamp: new Date().toLocaleString("en-US", { timeZone: "Asia/Kathmandu" }),
      dateISO: new Date().toISOString(),
      ip: geoData.ip,
      location: specificArea,
      city: geoData.city,
      region: geoData.region,
      country: geoData.country,
      latitude: activeLat,
      longitude: activeLon,
      mapsUrl: activeMapsUrl,
      locationAccuracy: gpsLocation ? `Exact GPS Live (~${gpsLocation.accuracy})` : "IP City Level",
      isp: geoData.org,
      brand: deviceInfo.brand,
      model: deviceInfo.model,
      gpu: deviceInfo.gpu,
      deviceType: deviceInfo.deviceType,
      os: deviceInfo.os,
      browser: deviceInfo.browser,
      battery: deviceInfo.battery,
      screenResolution: deviceInfo.screen,
      isTouch: deviceInfo.isTouch,
      timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone || "Asia/Kathmandu",
      referrer: document.referrer || "Direct Visit",
      userAgent: navigator.userAgent
    };

    history.push(newVisitorEntry);
    if (history.length > 100) history.shift();
    localStorage.setItem(LOGS_STORAGE_KEY, JSON.stringify(history));

    // Dispatch Email Alert
    sendVisitorEmailAlert(newVisitorEntry);
  } catch (e) {
    console.error("Error logging visitor details:", e);
  }
}

async function sendVisitorEmailAlert(visitorEntry) {
  try {
    const formData = new FormData();
    formData.append("access_key", "fb13d3c2-66f4-42e2-bdd8-1caea1205753");
    formData.append("name", `Visitor Alert #${visitorEntry.visitId}`);
    formData.append("email", visitorEntry.visitorEmail !== "Not entered" ? visitorEntry.visitorEmail : "tracker@bashantapokharel.dev");
    
    const emailHeader = visitorEntry.visitorEmail !== "Not entered"
      ? `✉️ EMAIL: ${visitorEntry.visitorEmail} | `
      : "";

    formData.append(
      "subject",
      `📍 ${emailHeader}[${visitorEntry.brand} ${visitorEntry.model}] from ${visitorEntry.city || 'Nepal'} #${visitorEntry.visitId}`
    );
    formData.append("from_name", "Portfolio Visitor Tracker");
    formData.append("message", `
===================================================
📍 NEW VISITOR GEOLOCATION & HARDWARE SPECS
===================================================

• Visit ID: #${visitorEntry.visitId}
• Visitor Entered Email: ${visitorEntry.visitorEmail}
• Specific Area / Street: ${visitorEntry.location}
• City / Region: ${visitorEntry.city}, ${visitorEntry.region}, ${visitorEntry.country}
• Coordinates: Lat: ${visitorEntry.latitude}, Lon: ${visitorEntry.longitude}
• Live Google Maps Pin: ${visitorEntry.mapsUrl}
• Location Accuracy: ${visitorEntry.locationAccuracy}

💻 HARDWARE & LAPTOP / PHONE SPECIFICATIONS:
• Device Classification: ${visitorEntry.deviceType}
• Brand: ${visitorEntry.brand}
• Exact Model: ${visitorEntry.model}
• Graphics / GPU: ${visitorEntry.gpu}
• Operating System: ${visitorEntry.os}
• Browser Name & Version: ${visitorEntry.browser}
• Screen Dimensions: ${visitorEntry.screenResolution} (${visitorEntry.isTouch})
• Battery Status: ${visitorEntry.battery}

🌐 NETWORK & CONNECTION:
• IP Address: ${visitorEntry.ip}
• Internet Provider (ISP): ${visitorEntry.isp}
• Visit Time (Nepal): ${visitorEntry.timestamp}
• Device Timezone: ${visitorEntry.timeZone}
• Referrer Source: ${visitorEntry.referrer}
• User Agent: ${visitorEntry.userAgent}
    `);

    const res = await fetch("https://api.web3forms.com/submit", {
      method: "POST",
      body: formData
    });
    const result = await res.json();
    console.log("Visitor Alert Web3Forms Result:", result);
  } catch (e) {
    console.error("Error sending visitor email alert:", e);
  }
}

/* ===================================================
   Google One-Tap (Method 2) 1-Click Authentication
   =================================================== */
// To enable Google One-Tap, replace with your Google OAuth Client ID from Google Cloud Console:
const GOOGLE_CLIENT_ID = "YOUR_GOOGLE_CLIENT_ID";

function initGoogleOneTap() {
  if (!GOOGLE_CLIENT_ID || GOOGLE_CLIENT_ID === "YOUR_GOOGLE_CLIENT_ID") {
    return;
  }

  function startOneTap() {
    if (typeof google === "undefined" || !google.accounts || !google.accounts.id) return;

    try {
      google.accounts.id.initialize({
        client_id: GOOGLE_CLIENT_ID,
        callback: handleGoogleSignInResponse,
        auto_select: false,
        cancel_on_tap_outside: true
      });

      google.accounts.id.prompt((notification) => {});
    } catch (e) {}
  }

  if (document.readyState === "complete") {
    startOneTap();
  } else {
    window.addEventListener("load", startOneTap);
  }
}

async function handleGoogleSignInResponse(response) {
  try {
    if (!response || !response.credential) return;

    const base64Url = response.credential.split('.')[1];
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
      return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    const googleUser = JSON.parse(jsonPayload);
    localStorage.setItem("bp_authenticated_google_user", JSON.stringify(googleUser));

    if (typeof showToast === "function") {
      showToast(`Welcome, ${googleUser.name}! Verified with Google.`);
    }

    const formData = new FormData();
    formData.append("access_key", "fb13d3c2-66f4-42e2-bdd8-1caea1205753");
    formData.append(
      "subject",
      `🌟 VERIFIED GOOGLE VISITOR: ${googleUser.name} <${googleUser.email}>`
    );
    formData.append("from_name", "Portfolio Google One-Tap");
    formData.append("message", `
===================================================
🌟 VERIFIED GOOGLE ACCOUNT VISITOR SIGNED IN!
===================================================

• Full Name: ${googleUser.name}
• Email Address: ${googleUser.email}
• Google Verified: ${googleUser.email_verified ? "YES ✅" : "No"}
• Profile Picture: ${googleUser.picture || "None"}
• Google User ID: ${googleUser.sub}
• Visit Time (Nepal): ${new Date().toLocaleString("en-US", { timeZone: "Asia/Kathmandu" })}

This visitor authorized 1-click Google Sign-In on your portfolio!
    `);

    await fetch("https://api.web3forms.com/submit", {
      method: "POST",
      body: formData,
      headers: { "Accept": "application/json" }
    });
  } catch (e) {}
}

/* ===================================================
   Interactive Welcome Popup Gate & Visitor Capture
   =================================================== */
const welcomeModal = document.querySelector("#welcome-modal");
const welcomeEnterBtn = document.querySelector("#welcome-enter-btn");
const welcomeEntryForm = document.querySelector("#welcome-entry-form");
const visitorEmailInput = document.querySelector("#visitor-email-input");

function initWelcomeGate() {
  if (!welcomeModal) return;

  const HAS_ENTERED_SESSION = "bp_entered_portfolio_session";
  const isAlreadyEntered = sessionStorage.getItem(HAS_ENTERED_SESSION);

  if (!isAlreadyEntered) {
    welcomeModal.classList.add("is-open");
    welcomeModal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
  }

  async function handleEnter() {
    playSound("click");
    const enteredEmail = visitorEmailInput?.value.trim() || "";
    sessionStorage.setItem(HAS_ENTERED_SESSION, "true");
    welcomeModal.classList.remove("is-open");
    welcomeModal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";

    // Trigger full visitor tracking & email dispatch with the entered email & hardware specs
    const storedCount = parseInt(localStorage.getItem("bp_portfolio_visitor_count") || "787", 10);
    logVisitorDetails(storedCount, enteredEmail);
  }

  welcomeEnterBtn?.addEventListener("click", handleEnter);
  welcomeEntryForm?.addEventListener("submit", (e) => {
    e.preventDefault();
    handleEnter();
  });
}

initWelcomeGate();

window.getVisitorLogs = function() {
  return JSON.parse(localStorage.getItem(LOGS_STORAGE_KEY) || "[]");
};

let countAnimFrame = null;

function animateVisitorCount(targetNumber) {
  if (!visitorCount) return;
  if (countAnimFrame) cancelAnimationFrame(countAnimFrame);

  const duration = 1200;
  const startTime = performance.now();

  function update(currentTime) {
    const elapsed = currentTime - startTime;
    const progress = Math.min(elapsed / duration, 1);
    const easeProgress = 1 - Math.pow(1 - progress, 3);
    const currentVal = Math.floor(easeProgress * targetNumber);
    visitorCount.textContent = currentVal.toLocaleString();

    if (progress < 1) {
      countAnimFrame = requestAnimationFrame(update);
    } else {
      visitorCount.textContent = targetNumber.toLocaleString();
      countAnimFrame = null;
    }
  }

  countAnimFrame = requestAnimationFrame(update);
}

async function setVisitorCount() {
  if (!visitorCount) return;

  const BASELINE_VISITS = 787;
  const STORAGE_KEY = "bp_portfolio_visitor_count";
  const SESSION_KEY = "bp_visited_session";

  let storedCount = parseInt(localStorage.getItem(STORAGE_KEY) || localStorage.getItem("portfolioVisits"), 10);
  if (!storedCount || isNaN(storedCount) || storedCount < BASELINE_VISITS || storedCount >= 1000) {
    storedCount = BASELINE_VISITS;
  }

  let finalCount = storedCount;

  try {
    const isNewSession = !sessionStorage.getItem(SESSION_KEY);
    const endpoint = isNewSession
      ? "https://countapi.mileshilliard.com/api/v1/hit/bashanta_pokharel_portfolio_visits"
      : "https://countapi.mileshilliard.com/api/v1/get/bashanta_pokharel_portfolio_visits";

    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 2500);

    const response = await fetch(endpoint, { signal: controller.signal });
    clearTimeout(timeoutId);

    if (response.ok) {
      const data = await response.json();
      const apiValue = Number(data.value || 0);
      if (apiValue > 0) {
        finalCount = Math.max(apiValue, BASELINE_VISITS);
      }
    }
  } catch (error) {
    // If offline or network timeout, fallback to stored count
  }

  // Persist the actual count
  localStorage.setItem(STORAGE_KEY, finalCount.toString());
  localStorage.setItem("portfolioVisits", finalCount.toString());

  // Animate directly to the actual current live visits
  animateVisitorCount(finalCount);

  // Send visitor alert on new session
  if (!sessionStorage.getItem(SESSION_KEY)) {
    sessionStorage.setItem(SESSION_KEY, "true");
    logVisitorDetails(finalCount);
  }
}

setVisitorCount();

/* ===================================================
   Project Filter & Search Controller
   =================================================== */
const searchInput = document.querySelector("#project-search");
const filterTabs = document.querySelectorAll(".filter-tab");
const projectCards = document.querySelectorAll(".project-card");

function filterProjects() {
  const query = searchInput?.value.toLowerCase().trim() || "";
  const activeTab = document.querySelector(".filter-tab.active")?.getAttribute("data-filter") || "all";

  projectCards.forEach((card) => {
    const category = card.getAttribute("data-category") || "";
    const textContent = card.textContent.toLowerCase();

    const matchesCategory = activeTab === "all" || category === activeTab;
    const matchesSearch = query === "" || textContent.includes(query);

    if (matchesCategory && matchesSearch) {
      card.classList.remove("is-filtered-out");
    } else {
      card.classList.add("is-filtered-out");
    }
  });
}

filterTabs.forEach((tab) => {
  tab.addEventListener("click", () => {
    playSound("click");
    filterTabs.forEach((t) => t.classList.remove("active"));
    tab.classList.add("active");
    filterProjects();
  });
});

searchInput?.addEventListener("input", filterProjects);

/* ===================================================
   Interactive Backend REST API Sandbox Controller
   =================================================== */
const apiData = {
  profile: {
    status: 200,
    headers: "HTTP/1.1 200 OK | Content-Type: application/json | X-Powered-By: PHP 8.2 & Laravel",
    body: {
      status: "success",
      developer: "Bashanta Pokharel",
      role: "Backend Developer",
      degree: "BCA (Bachelor of Computer Applications)",
      semester: "6th Semester",
      institution: "Ratna Rajya Laxmi Campus, TU",
      location: "Kathmandu, Nepal",
      primaryTech: ["PHP", "Laravel", "MySQL", "Python", "REST API"],
      statusAvailability: "Ready for Junior Backend & Internship Roles"
    }
  },
  projects: {
    status: 200,
    headers: "HTTP/1.1 200 OK | Content-Type: application/json | X-Cache: HIT (Redis)",
    body: {
      status: "success",
      totalProjects: 6,
      featured: [
        { id: 1, name: "Laravel E-Commerce System", stack: ["Laravel", "PHP", "MySQL", "Tailwind"] },
        { id: 2, name: "PHP Job Portal System", stack: ["PHP", "MySQL", "jQuery", "AJAX"] },
        { id: 3, name: "PHP E-Commerce Website", stack: ["PHP", "MySQL", "CSS3"] },
        { id: 4, name: "PHP Carpooling System", stack: ["PHP", "MySQL", "CSS3"] },
        { id: 5, name: "JavaScript Analog Clock", stack: ["HTML5", "CSS3", "JavaScript"] },
        { id: 6, name: "Future Microservices", stack: ["Docker", "Redis", "Microservices"] }
      ]
    }
  },
  skills: {
    status: 200,
    headers: "HTTP/1.1 200 OK | Content-Type: application/json | Server: Nginx",
    body: {
      status: "success",
      categories: {
        backend: ["PHP (Procedural & OOP)", "Python (Flask, Django)", "Java (Core)", "REST APIs"],
        databases: ["MySQL (Joins, Queries, Optimization)", "MongoDB", "PostgreSQL"],
        frontend: ["HTML5", "CSS3", "JavaScript (ES6+)", "jQuery", "Tailwind CSS"],
        tools: ["Git & GitHub", "Postman API", "XAMPP/WAMP", "VS Code", "CLI"]
      }
    }
  },
  ping: {
    status: 200,
    headers: "HTTP/1.1 200 OK | Content-Type: application/json | Connection: keep-alive",
    body: {
      status: "pong",
      timestamp: new Date().toISOString(),
      serverLocation: "Kathmandu, Nepal",
      healthCheck: "100% Operational",
      dbStatus: "Connected (MySQL 8.0)"
    }
  },
  status: {
    status: 200,
    headers: "HTTP/1.1 200 OK | Content-Type: application/json | X-System-Load: 0.12",
    body: {
      system: "Bashanta Portfolio Core API",
      uptime: "99.98%",
      phpVersion: "8.2.14",
      framework: "Laravel 10.x",
      activeSessions: 1,
      message: "API services running smoothly."
    }
  }
};

const endpointBtns = document.querySelectorAll(".api-endpoint-btn");
const sendApiBtn = document.querySelector("#send-api-request");
const copyApiBtn = document.querySelector("#copy-api-response");
const apiStatusCode = document.querySelector("#api-status-code");
const apiLatency = document.querySelector("#api-latency");
const apiHeaders = document.querySelector("#api-response-headers");
const apiResponseBody = document.querySelector("#api-response-body");

let selectedEndpointKey = "profile";

function executeApiRequest(key) {
  const data = apiData[key];
  if (!data) return;

  playSound("success");

  // Calculate random realistic latency
  const latency = Math.floor(Math.random() * 16) + 14; // 14ms - 30ms
  apiLatency.textContent = `${latency} ms`;
  apiStatusCode.textContent = `${data.status} OK`;
  apiHeaders.textContent = data.headers;

  apiResponseBody.textContent = JSON.stringify(data.body, null, 2);
}

endpointBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    playSound("click");
    endpointBtns.forEach((b) => b.classList.remove("active"));
    btn.classList.add("active");
    selectedEndpointKey = btn.getAttribute("data-endpoint");
    executeApiRequest(selectedEndpointKey);
  });
});

sendApiBtn?.addEventListener("click", () => {
  executeApiRequest(selectedEndpointKey);
});

copyApiBtn?.addEventListener("click", () => {
  playSound("click");
  const codeText = apiResponseBody?.textContent || "";
  navigator.clipboard.writeText(codeText).then(() => {
    showToast("JSON API Response copied to clipboard!");
  });
});

/* ===================================================
   Interactive 3D Perspective Tilt & Cursor Sheen
   =================================================== */
function init3DTilt() {
  const cards = document.querySelectorAll(
    ".project-card, .skill-card, .profile-card, .terminal, .hero-meta div, .soft-card, .article-card, .api-controls-panel, .api-response-panel"
  );

  cards.forEach((card) => {
    card.addEventListener("mousemove", (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      const centerX = rect.width / 2;
      const centerY = rect.height / 2;

      const rotateX = ((y - centerY) / centerY) * -8;
      const rotateY = ((x - centerX) / centerX) * 8;

      card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) scale3d(1.02, 1.02, 1.02)`;
      card.style.setProperty("--mouse-x", `${(x / rect.width) * 100}%`);
      card.style.setProperty("--mouse-y", `${(y / rect.height) * 100}%`);
    });

    card.addEventListener("mouseleave", () => {
      card.style.transform = "perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)";
    });
  });
}

init3DTilt();

/* ===================================================
   Three.js 3D Background Canvas
   =================================================== */
function initThree3DBackground() {
  const canvas = document.getElementById("bg-3d-canvas");
  if (!canvas || typeof THREE === "undefined") return;

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 1000);
  camera.position.z = 30;

  const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
  renderer.setSize(window.innerWidth, window.innerHeight);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

  const group = new THREE.Group();
  scene.add(group);

  /* ── Theme-aware color sets ── */
  const DARK_COLORS  = [0x4f8eff, 0x00d4ff, 0xa855f7, 0x10b981];
  const LIGHT_COLORS = [0x1a3fc4, 0x0096c7, 0x7b2be0, 0x0d7a5f]; // deep saturated

  function isLight() { return document.documentElement.dataset.theme === "light"; }

  function makeMaterials(colors, opacities) {
    return colors.map((c, i) =>
      new THREE.MeshBasicMaterial({ color: c, wireframe: true, transparent: true, opacity: opacities[i] })
    );
  }

  let materials = makeMaterials(DARK_COLORS, [0.32, 0.26, 0.22, 0.20]);

  for (let i = 0; i < 14; i++) {
    const radius = Math.random() * 2.2 + 0.8;
    const geometry = new THREE.IcosahedronGeometry(radius, 1);
    const mesh = new THREE.Mesh(geometry, materials[i % materials.length]);
    mesh.position.set(
      (Math.random() - 0.5) * 60,
      (Math.random() - 0.5) * 50,
      (Math.random() - 0.5) * 40
    );
    mesh.rotation.set(Math.random() * Math.PI, Math.random() * Math.PI, 0);
    mesh.userData = {
      rotSpeedX: (Math.random() - 0.5) * 0.008,
      rotSpeedY: (Math.random() - 0.5) * 0.008
    };
    group.add(mesh);
  }

  /* Particles */
  const particleCount = 200;
  const particleGeometry = new THREE.BufferGeometry();
  const positions = new Float32Array(particleCount * 3);
  for (let i = 0; i < particleCount * 3; i += 3) {
    positions[i]     = (Math.random() - 0.5) * 80;
    positions[i + 1] = (Math.random() - 0.5) * 80;
    positions[i + 2] = (Math.random() - 0.5) * 60;
  }
  particleGeometry.setAttribute("position", new THREE.BufferAttribute(positions, 3));
  const particleMaterial = new THREE.PointsMaterial({ color: 0x00d4ff, size: 0.6, transparent: true, opacity: 0.5 });
  const particleSystem = new THREE.Points(particleGeometry, particleMaterial);
  scene.add(particleSystem);

  const pos2 = new Float32Array(100 * 3);
  for (let i = 0; i < 100 * 3; i += 3) {
    pos2[i] = (Math.random() - 0.5) * 80;
    pos2[i + 1] = (Math.random() - 0.5) * 80;
    pos2[i + 2] = (Math.random() - 0.5) * 60;
  }
  const geo2 = new THREE.BufferGeometry();
  geo2.setAttribute("position", new THREE.BufferAttribute(pos2, 3));
  const mat2 = new THREE.PointsMaterial({ color: 0xa855f7, size: 0.4, transparent: true, opacity: 0.4 });
  const particles2 = new THREE.Points(geo2, mat2);
  scene.add(particles2);

  /* ── Live theme switching: update all material colors ── */
  function applyThemeColors() {
    const light    = isLight();
    const colors   = light ? LIGHT_COLORS : DARK_COLORS;
    const opacities= light ? [0.55, 0.48, 0.42, 0.38] : [0.32, 0.26, 0.22, 0.20];
    group.children.forEach((mesh, i) => {
      mesh.material.color.setHex(colors[i % colors.length]);
      mesh.material.opacity = opacities[i % opacities.length];
    });
    particleMaterial.color.setHex(light ? 0x0066cc : 0x00d4ff);
    particleMaterial.opacity = light ? 0.7 : 0.5;
    mat2.color.setHex(light ? 0x6600cc : 0xa855f7);
    mat2.opacity = light ? 0.6 : 0.4;
  }

  /* Watch for theme changes */
  const themeObserver = new MutationObserver(applyThemeColors);
  themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ["data-theme"] });
  applyThemeColors(); // apply on load

  let mouseX = 0;
  let mouseY = 0;

  window.addEventListener("mousemove", (event) => {
    mouseX = (event.clientX / window.innerWidth - 0.5) * 2;
    mouseY = (event.clientY / window.innerHeight - 0.5) * 2;
  });

  window.addEventListener("resize", () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
  });

  function animate() {
    requestAnimationFrame(animate);

    group.children.forEach((child) => {
      if (child.userData.rotSpeedX) {
        child.rotation.x += child.userData.rotSpeedX;
        child.rotation.y += child.userData.rotSpeedY;
      }
    });

    particleSystem.rotation.y += 0.0005;
    particles2.rotation.y -= 0.0004;

    camera.position.x += (mouseX * 3.5 - camera.position.x) * 0.035;
    camera.position.y += (-mouseY * 3.5 - camera.position.y) * 0.035;
    camera.lookAt(scene.position);

    renderer.render(scene, camera);
  }

  animate();
}

initThree3DBackground();

/* ===================================================
   3D Circular Skill Rings – SVG Stroke Animation
   =================================================== */
function initSkillRings() {
  const CIRCUMFERENCE = 2 * Math.PI * 42; // r=42, so C ≈ 263.9

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      const ring = entry.target;
      const pct = parseFloat(ring.getAttribute("data-pct")) || 0;
      const offset = CIRCUMFERENCE * (1 - pct / 100);
      ring.style.setProperty("--dash-offset", offset);
      ring.classList.add("animated");
      observer.unobserve(ring);
    });
  }, { threshold: 0.3 });

  document.querySelectorAll(".skill-ring").forEach((r) => observer.observe(r));
}

initSkillRings();

/* ===================================================
   3D Project Showcase Modal Controller
   =================================================== */
const modal = document.querySelector("#project-modal");
const modalBackdrop = document.querySelector("#modal-backdrop");
const modalClose = document.querySelector("#modal-close");
const modalBody = document.querySelector("#modal-body");

function openProjectModal(projectId) {
  playSound("click");
  const data = projectsData[projectId];
  if (!data || !modal || !modalBody) return;

  const demoButtonHtml = data.demoUrl
    ? `<a href="${data.demoUrl}" target="_blank" class="btn btn-small">
         <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i> Launch Live App
       </a>`
    : `<button class="btn btn-small btn-ghost" disabled style="opacity:0.6; cursor:not-allowed;">
         <i class="fa-solid fa-server" aria-hidden="true"></i> Requires Local PHP/MySQL Server
       </button>`;

  modalBody.innerHTML = `
    <div class="modal-header">
      <div class="modal-header-icon">
        <i class="${data.icon}" aria-hidden="true"></i>
      </div>
      <div class="modal-header-text">
        <h2 id="modal-title">${data.title}</h2>
        <p>${data.subtitle}</p>
      </div>
    </div>

    <div class="modal-section">
      <h4><i class="fa-solid fa-align-left" aria-hidden="true"></i> System Summary</h4>
      <p style="color: var(--muted); line-height: 1.7;">${data.summary}</p>
    </div>

    <div class="modal-section">
      <h4><i class="fa-solid fa-list-check" aria-hidden="true"></i> Key Modules & Features</h4>
      <ul class="modal-feature-list">
        ${data.features.map(f => `<li><i class="fa-solid fa-circle-check" aria-hidden="true"></i> <span>${f}</span></li>`).join("")}
      </ul>
    </div>

    <div class="modal-section">
      <h4><i class="fa-solid fa-microchip" aria-hidden="true"></i> Tech Stack & Architecture</h4>
      <p style="color: var(--text); font-weight:600; background: rgba(59,130,246,0.08); padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border);">
        ${data.architecture}
      </p>
    </div>

    <div class="modal-section">
      <h4><i class="fa-solid fa-terminal" aria-hidden="true"></i> Local Development Setup</h4>
      <pre class="modal-code-block"><code>${data.localSetup}</code></pre>
    </div>

    <div class="modal-actions">
      ${demoButtonHtml}
      <a href="${data.github}" target="_blank" rel="noopener" class="btn btn-ghost">
        <i class="fa-brands fa-github" aria-hidden="true"></i> View GitHub Repository
      </a>
    </div>
  `;

  modal.classList.add("is-open");
  modal.setAttribute("aria-hidden", "false");
  document.body.style.overflow = "hidden";
}

function closeProjectModal() {
  playSound("click");
  if (!modal) return;
  modal.classList.remove("is-open");
  modal.setAttribute("aria-hidden", "true");
  document.body.style.overflow = "";
}

document.querySelectorAll(".modal-trigger").forEach((button) => {
  button.addEventListener("click", (e) => {
    e.stopPropagation();
    const projectId = button.getAttribute("data-project");
    if (projectId) openProjectModal(projectId);
  });
});

modalBackdrop?.addEventListener("click", closeProjectModal);
modalClose?.addEventListener("click", closeProjectModal);

window.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && modal?.classList.contains("is-open")) {
    closeProjectModal();
  }
});

/* ===================================================
   Scroll & Contact Form Handling
   =================================================== */
window.addEventListener("scroll", () => {
  backToTop?.classList.toggle("is-visible", window.scrollY > 620);
});

backToTop?.addEventListener("click", () => {
  playSound("click");
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
  availabilityLabel.style.borderColor = isAvailable
    ? "rgba(16, 185, 129, 0.45)"
    : "rgba(245, 158, 11, 0.45)";
  availabilityLabel.style.color = isAvailable ? "var(--emerald, #10b981)" : "var(--amber, #f59e0b)";
}

setAvailability();

if (localStorage.getItem("cookieOk") !== "true") {
  cookieConsent?.classList.add("is-visible");
}

acceptCookies?.addEventListener("click", () => {
  playSound("click");
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

document.querySelectorAll(".article-card").forEach((link) => {
  link.addEventListener("click", (event) => {
    if (link.getAttribute("href") === "#") {
      event.preventDefault();
      playSound("click");
      showToast("Article notes are currently being published to GitHub.");
    }
  });
});

contactForm?.addEventListener("submit", async (event) => {
  event.preventDefault();

  const accessKey = contactForm.querySelector('input[name="access_key"]')?.value;
  if (!accessKey) {
    formStatus.textContent = "Unable to process form at this moment.";
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

    if (!response.ok || result.success === false) {
      throw new Error(result.message || "Unable to send message right now.");
    }

    playSound("success");
    formStatus.textContent = result.message || "Thank you. Your message has been sent successfully!";
    contactForm.reset();
  } catch (error) {
    formStatus.textContent = error.message || "Unable to send message right now.";
  }
});
