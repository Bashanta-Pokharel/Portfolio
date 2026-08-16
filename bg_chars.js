
/* =====================================================================
   ONE PIECE BACKGROUND CREW  —  bg_chars.js logic
   Luffy · Nami · Robin (Clutch) · Zoro (Santoryu / Sleep)
   ===================================================================== */
(function initBgCrew() {

  /* ── Constants ── */
  const CHAR_W    = 70;
  const CHAR_H    = 160;   // total px height including hat
  const NAV_H     = 70;    // clear nav bar
  const MARGIN    = 20;

  /* ── State durations ── */
  const WALK_MIN  = 4000;
  const WALK_MAX  = 9000;
  const IDLE_MIN  = 2500;
  const IDLE_MAX  = 5500;

  /* ── Per-character config ── */
  const CONFIGS = [
    {
      id:       'bg-luffy',
      bubId:    'bub-luffy',
      speed:    0.55,
      idleStates: ['wave', 'stretch'],
      speeches: [
        "I'll be King of the Pirates! 🏴‍☠️",
        "Shishishi~ 😄",
        "I'm hungry… I need meat! 🍖",
        "My crew is the best! 🌊",
        "Gomu Gomu no…! 💨",
        "I'll never run away! 🔥"
      ]
    },
    {
      id:       'bg-nami',
      bubId:    'bub-nami',
      speed:    0.42,
      idleStates: ['wave', 'stretch'],
      speeches: [
        "Pay me 100,000 Berries! 💰",
        "Clima-Tact! ⚡",
        "I'll draw a map of the whole world! 🗺️",
        "Don't underestimate me! 😤",
        "Luffy you idiot!! 😡",
        "Weather Egg! 🌩️"
      ]
    },
    {
      id:       'bg-robin',
      bubId:    'bub-robin',
      speed:    0.35,
      idleStates: ['clutch', 'wave'],
      speeches: [
        "Tres Fleurs… Clutch. 🌸",
        "I want to live! 🌺",
        "Seis Fleurs! 🌸🌸",
        "How fascinating… 🔍",
        "The Poneglyphs await. 📜",
        "Nico Robin, Archaeologist. 📖"
      ]
    },
    {
      id:       'bg-zoro',
      bubId:    'bub-zoro',
      speed:    0.45,
      idleStates: ['pose', 'sleep'],
      speeches: [
        "Nothing happened.",
        "Santoryu… 🗡️🗡️🗡️",
        "I'll be the world's greatest swordsman!",
        "Tatsumaki Senpukyaku!",
        "Don't make me use my left hand…",
        "Oni Giri!! 🗡️"
      ]
    }
  ];

  /* ── Build a runner ── */
  function makeRunner(cfg) {
    const el  = document.getElementById(cfg.id);
    const bub = document.getElementById(cfg.bubId);
    if (!el) return null;

    let speechIdx = 0;

    /* Stagger spawn positions so they don't start piled */
    const side = CONFIGS.indexOf(cfg);
    const startX = MARGIN + (window.innerWidth  / 4) * side + Math.random() * 60;
    const startY = NAV_H  + Math.random() * (window.innerHeight - NAV_H - CHAR_H - MARGIN);

    let x    = Math.min(startX, window.innerWidth  - CHAR_W  - MARGIN);
    let y    = Math.min(startY, window.innerHeight - CHAR_H - MARGIN);
    let vx   = cfg.speed * (Math.random() > 0.5 ? 1 : -1);
    let vy   = 0;

    let currentState = 'walk';
    let walkTimer = null;
    let idleTimer = null;

    /* Apply initial position */
    el.style.left = x + 'px';
    el.style.top  = y + 'px';
    el.classList.add('facing-right');

    /* ── Speech ── */
    function speak(msg) {
      if (!bub) return;
      bub.textContent = msg || cfg.speeches[speechIdx++ % cfg.speeches.length];
      el.classList.add('show-bubble');
      clearTimeout(speak._t);
      speak._t = setTimeout(() => el.classList.remove('show-bubble'), 2800);
    }

    /* Randomly speak while walking */
    function scheduleSpeech() {
      setTimeout(() => {
        if (currentState === 'walk') speak();
        scheduleSpeech();
      }, 8000 + Math.random() * 16000);
    }
    scheduleSpeech();

    /* ── State management ── */
    function clearStates() {
      el.classList.remove('state-idle', 'state-wave', 'state-stretch',
                          'state-clutch', 'state-pose', 'state-sleep');
    }

    function startWalk() {
      clearStates();
      currentState = 'walk';
      /* Pick a new walking direction */
      const angle = (Math.random() - 0.5) * Math.PI * 0.8;
      vx = cfg.speed * Math.cos(angle) * (Math.random() > 0.5 ? 1 : -1);
      vy = cfg.speed * Math.sin(angle) * 0.35;

      clearTimeout(walkTimer);
      walkTimer = setTimeout(startIdle, WALK_MIN + Math.random() * (WALK_MAX - WALK_MIN));
    }

    function startIdle() {
      clearStates();
      /* Pick a random idle from this character's list */
      const pick = cfg.idleStates[Math.floor(Math.random() * cfg.idleStates.length)];
      currentState = 'state-' + pick;
      el.classList.add('state-idle', 'state-' + pick);

      if (pick === 'sleep' || pick === 'clutch' || pick === 'pose') speak();

      vx = 0; vy = 0;

      clearTimeout(idleTimer);
      idleTimer = setTimeout(startWalk, IDLE_MIN + Math.random() * (IDLE_MAX - IDLE_MIN));
    }

    /* Start */
    startWalk();

    /* ── Frame update ── */
    function update() {
      if (currentState !== 'walk') return;

      x += vx;
      y += vy;

      const maxX = window.innerWidth  - CHAR_W  - MARGIN;
      const maxY = window.innerHeight - CHAR_H  - MARGIN;

      /* Wall bounce */
      if (x < MARGIN)  { x = MARGIN;  vx =  Math.abs(vx); }
      if (x > maxX)    { x = maxX;    vx = -Math.abs(vx); }
      if (y < NAV_H)   { y = NAV_H;   vy =  Math.abs(vy) * 0.5; }
      if (y > maxY)    { y = maxY;    vy = -Math.abs(vy) * 0.5; }

      /* Gravity + damping */
      vy += 0.012;
      if (Math.abs(vy) > 1.8) vy *= 0.85;

      el.style.left = Math.round(x) + 'px';
      el.style.top  = Math.round(y) + 'px';

      /* Facing direction */
      el.classList.toggle('facing-left',  vx < -0.05);
      el.classList.toggle('facing-right', vx >= -0.05);
    }

    return { update };
  }

  /* ── Init all runners ── */
  const runners = CONFIGS.map(makeRunner).filter(Boolean);

  /* ── Main animation loop ── */
  function loop() {
    runners.forEach(r => r.update());
    requestAnimationFrame(loop);
  }
  requestAnimationFrame(loop);

})();
