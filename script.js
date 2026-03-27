// ============================================
// THEME TOGGLE
// ============================================
const themeToggle = document.getElementById('themeToggle');
const body = document.body;

// Load saved theme preference
const savedTheme = localStorage.getItem('theme') || 'dark';
if (savedTheme === 'light') {
    body.classList.add('light-mode');
}

themeToggle.addEventListener('click', () => {
    body.classList.toggle('light-mode');
    const isLightMode = body.classList.contains('light-mode');
    localStorage.setItem('theme', isLightMode ? 'light' : 'dark');
    
    // Update three.js colors based on theme
    if(particlesMaterial && linesMaterial) {
        const color = isLightMode ? 0x2563eb : 0x3b82f6;
        particlesMaterial.color.setHex(color);
        linesMaterial.color.setHex(color);
    }
});

// ============================================
// MOBILE MENU TOGGLE
// ============================================
const navToggle = document.getElementById('navToggle');
const navMenu = document.getElementById('navMenu');
const navLinks = document.querySelectorAll('.nav-link');

navToggle.addEventListener('click', () => {
    navToggle.classList.toggle('active');
    navMenu.classList.toggle('active');
});

navLinks.forEach(link => {
    link.addEventListener('click', () => {
        navToggle.classList.remove('active');
        navMenu.classList.remove('active');
    });
});

// ============================================
// SMOOTH SCROLLING
// ============================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            const target = document.querySelector(href);
            const offsetTop = target.offsetTop - 70;
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    });
});

// ============================================
// FORM SUBMISSION
// ============================================
const contactForm = document.getElementById('contactForm');
const formStatus = document.getElementById('formStatus');

contactForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();

    // Validation
    if (!name || !email || !message) {
        showFormStatus('Please fill in all fields.', 'error');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showFormStatus('Please enter a valid email address.', 'error');
        return;
    }

    if (message.length < 10) {
        showFormStatus('Message must be at least 10 characters long.', 'error');
        return;
    }

    // Simulate form submission
    const submitBtn = contactForm.querySelector('button[type="submit"]');
    const originalContent = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Sending...';

    setTimeout(() => {
        showFormStatus('Message sent successfully! I will get back to you soon.', 'success');
        contactForm.reset();
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalContent;
    }, 1500);
});

function showFormStatus(message, type) {
    formStatus.textContent = message;
    formStatus.className = `form-status show ${type}`;

    setTimeout(() => {
        formStatus.classList.remove('show');
    }, 5000);
}

// ============================================
// SCROLL ANIMATIONS
// ============================================
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Add animated class to elements dynamically
document.querySelectorAll('.about-card, .project-card, .timeline-item').forEach((el, index) => {
    el.classList.add('animated-element');
    el.style.animationDelay = `${(index % 3) * 0.15}s`;
    observer.observe(el);
});


// ============================================
// THREE.JS NETWORK ANIMATION
// ============================================
let scene, camera, renderer, particles, linesMesh;
let particlesMaterial, linesMaterial; // Accessible globally for theme change

function initThreeJS() {
    const container = document.getElementById('canvas-container');
    if(!container) return;

    // SCENE
    scene = new THREE.Scene();

    // CAMERA
    camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 1, 1000);
    camera.position.z = 400;

    // RENDERER
    renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(container.clientWidth, container.clientHeight);
    container.appendChild(renderer.domElement);

    // Initial color based on theme
    const isLightMode = document.body.classList.contains('light-mode');
    const colorHex = isLightMode ? 0x2563eb : 0x3b82f6;

    // PARTICLES / NODES
    const particleCount = 150;
    const geometry = new THREE.BufferGeometry();
    const positions = new Float32Array(particleCount * 3);
    const velocities = [];

    for (let i = 0; i < particleCount; i++) {
        positions[i * 3] = (Math.random() - 0.5) * 1000;
        positions[i * 3 + 1] = (Math.random() - 0.5) * 1000;
        positions[i * 3 + 2] = (Math.random() - 0.5) * 1000;
        
        velocities.push({
            x: (Math.random() - 0.5) * 1,
            y: (Math.random() - 0.5) * 1,
            z: (Math.random() - 0.5) * 1
        });
    }

    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));

    particlesMaterial = new THREE.PointsMaterial({
        color: colorHex,
        size: 4,
        blending: THREE.AdditiveBlending,
        transparent: true,
        sizeAttenuation: true
    });

    particles = new THREE.Points(geometry, particlesMaterial);
    // Bind velocities to object for animate loop
    particles.velocities = velocities;
    scene.add(particles);

    // LINES (Connecting Nodes)
    linesMaterial = new THREE.LineBasicMaterial({
        color: colorHex,
        transparent: true,
        opacity: 0.15
    });
    
    // We update lines dynamically via geometry
    const lineGeometry = new THREE.BufferGeometry();
    linesMesh = new THREE.LineSegments(lineGeometry, linesMaterial);
    scene.add(linesMesh);

    window.addEventListener('resize', onWindowResize, false);
    
    // Initial calculation
    updateLines();
}

function updateLines() {
    const positions = particles.geometry.attributes.position.array;
    const particleCount = positions.length / 3;
    
    // Arrays for lines
    const linePositions = [];
    
    for (let i = 0; i < particleCount; i++) {
        for (let j = i + 1; j < particleCount; j++) {
            const dx = positions[i * 3] - positions[j * 3];
            const dy = positions[i * 3 + 1] - positions[j * 3 + 1];
            const dz = positions[i * 3 + 2] - positions[j * 3 + 2];
            const dist = Math.sqrt(dx*dx + dy*dy + dz*dz);
            
            // Connect nodes if they are close enough
            if (dist < 180) {
                linePositions.push(
                    positions[i * 3], positions[i * 3 + 1], positions[i * 3 + 2],
                    positions[j * 3], positions[j * 3 + 1], positions[j * 3 + 2]
                );
            }
        }
    }
    
    linesMesh.geometry.setAttribute('position', new THREE.Float32BufferAttribute(linePositions, 3));
    linesMesh.geometry.attributes.position.needsUpdate = true;
}

function onWindowResize() {
    const container = document.getElementById('canvas-container');
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(container.clientWidth, container.clientHeight);
}

function animateThreeJS() {
    requestAnimationFrame(animateThreeJS);
    
    if(particles) {
        const positions = particles.geometry.attributes.position.array;
        const vels = particles.velocities;
        
        for(let i = 0; i < positions.length / 3; i++) {
            positions[i * 3] += vels[i].x;
            positions[i * 3 + 1] += vels[i].y;
            positions[i * 3 + 2] += vels[i].z;
            
            // Bounce off boundaries
            if(Math.abs(positions[i * 3]) > 500) vels[i].x *= -1;
            if(Math.abs(positions[i * 3 + 1]) > 500) vels[i].y *= -1;
            if(Math.abs(positions[i * 3 + 2]) > 500) vels[i].z *= -1;
        }
        
        particles.geometry.attributes.position.needsUpdate = true;
        
        // Slightly rotate point cloud for effect
        particles.rotation.x += 0.0005;
        particles.rotation.y += 0.001;
        linesMesh.rotation.x = particles.rotation.x;
        linesMesh.rotation.y = particles.rotation.y;
        
        updateLines();
    }
    
    renderer.render(scene, camera);
}


// ============================================
// INITIALIZE
// ============================================
window.onload = () => {
    // Only init Three.js on non-mobile devices or if preferred
    if(typeof THREE !== 'undefined') {
        initThreeJS();
        animateThreeJS();
    }
};

const updateActiveLink = () => {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');

    let current = '';

    sections.forEach(section => {
        const sectionTop = section.offsetTop - 100;
        if (window.scrollY >= sectionTop) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.style.color = '';
        if (link.getAttribute('href') === `#${current}`) {
            link.style.color = 'var(--accent)';
        }
    });
};

window.addEventListener('scroll', updateActiveLink);
updateActiveLink();
