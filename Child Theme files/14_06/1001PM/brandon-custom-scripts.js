// ==== BRANDON: GLOBAL SPA JS (Fully Optimized & Documented) ====
// Version: 5.0 (Final Merged)
// Date: 2025-06-14
// Author: Brandon Leach
// Description: Optimized custom animations and interactions for Semplice WordPress theme, fully SPA-safe.

(function() {
  'use strict';

  // Prevent Semplice “not defined” errors
  window.semplice = window.semplice || { animate: {} };

  // ========== GSAP & CUSTOM EASE INITIALIZATION ==========
  function initializeGSAP() {
    if (window.gsap && window.CustomEase) {
      gsap.registerPlugin(CustomEase, ScrollTrigger, SplitText, ScrollToPlugin);
      if (!CustomEase.get("circleEase")) {
        CustomEase.create("circleEase", "0.68, -0.55, 0.265, 1.55");
      }
      return true;
    }
    return false;
  }

  // ========== CONFIGURATION OBJECT (Original Preserved) ==========
  const BRANDON_CONFIG = {
    grid: { subdivisions: 12, gapFactor: 8, baseDotSize: 1.5 },
    wave: { dotColor: [42, 42, 46], alphaReveal: 77, thickness: 145, speed: 247, frontRatio: 0.44, backRatio: 2.6 },
    timing: { buttonPress: 180, dotAnimation: 500, waveExpansion: 247 },
    easings: { button: "circleEase" }
  };

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ========== UTILITY FUNCTIONS (Original Preserved) ==========
  function isElement(el) { return el && el.closest && typeof el.closest === 'function'; }
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => { clearTimeout(timeout); func(...args); };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }
  function isMobile() { return window.innerWidth < 900; }
  
  // ========== BUTTON & LOGO ANIMATION HANDLERS (Original Preserved) ==========
  function initializeButtonHandlers() {
    if (window._brandonNavBtnHandlers) return;
    window._brandonNavBtnHandlers = true;
    const buttonSelector   = '.brandon-animated-button-reveal, .brandon-logo-reveal-link';
    const workMenuSelector = '.brandon-work-menu-trigger';
    const pressEvents      = ['mousedown', 'touchstart', 'keydown'];
    const releaseEvents    = ['mouseup','mouseleave','touchend','touchcancel','keyup','blur'];
    pressEvents.forEach(evt => {
      document.addEventListener(evt, e => {
        const btn = isElement(e.target) ? e.target.closest(buttonSelector) : null;
        if (!btn) return;
        if (evt === 'keydown' && !['Enter',' '].includes(e.key)) return;
        btn.classList.add('pressed');
        setTimeout(() => btn.classList.remove('pressed'), BRANDON_CONFIG.timing.buttonPress);
      }, { passive: true, capture: true });
    });
    releaseEvents.forEach(evt => {
      document.addEventListener(evt, e => {
        const btn = isElement(e.target) ? e.target.closest(buttonSelector) : null;
        if (btn) btn.classList.remove('pressed');
      }, { passive: true, capture: true });
    });
    document.addEventListener('click', e => {
      const trigger = isElement(e.target) ? e.target.closest(workMenuSelector) : null;
      if (!trigger) return;
      e.preventDefault();
      const menuTrigger = document.querySelector('.open-menu.menu-icon') || document.querySelector('.hamburger') || document.querySelector('[data-module="menu"] .hamburger, [data-menu-type="hamburger"] .hamburger');
      if (menuTrigger) menuTrigger.click();
    }, { capture: true });
  }

  // ========== DOTS GRID MENU ANIMATION (Original Preserved) ==========
  function initializeDotsGridMenu() {
    if (prefersReducedMotion || !initializeGSAP()) return;
    const menuBtn = document.getElementById('brandonDotsGridMenu');
    if (!menuBtn || menuBtn.dataset.brandonInitialized === 'true') return;
    menuBtn.dataset.brandonInitialized = 'true';
    const dots = Array.from(menuBtn.querySelectorAll('.brandon-dot'));
    let isOpen = false;
    if (!dots.length) return;
    menuBtn.addEventListener('click', () => {
      isOpen = !isOpen;
      if (isOpen) {
        dots.forEach((dot, i) => {
          const props = [ { x:10,  y:10,  scale:1.2, ease:'circleEase', duration:0.5 }, { opacity:0, scale:3,   ease:'circleEase', duration:0.5 }, { x:-10, y:10,  scale:1.2, ease:'circleEase', duration:0.5 }, { opacity:0, scale:3,   ease:'circleEase', duration:0.5 }, { scale:1.3,      ease:'circleEase', duration:0.5 }, { opacity:0, scale:3,   ease:'circleEase', duration:0.3 }, { x:10,  y:-10, scale:1.2, ease:'circleEase', duration:0.5 }, { opacity:0, scale:3,   ease:'circleEase', duration:0.5 }, { x:-10, y:-10, scale:1.2, ease:'circleEase', duration:0.5 } ][i];
          gsap.to(dot, props);
        });
      } else {
        gsap.to(dots, { x:0, y:0, scale:1, opacity:1, ease:'circleEase', duration:0.5 });
      }
    });
  }

  // ========== SMOOTH SCROLL HANDLERS (Original Preserved) ==========
  function initializeSmoothScrollHandlers() {
    const scrollLinks = document.querySelectorAll('.brandon-logo-reveal-link[href^="#"], .brandon-animated-button-reveal[href^="#"]');
    scrollLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        const linkUrl    = new URL(this.href);
        const currentUrl = new URL(window.location.href);
        if (linkUrl.pathname !== currentUrl.pathname || linkUrl.search !== currentUrl.search) return;
        e.preventDefault();
        const targetId = linkUrl.hash;
        if (targetId && targetId !== '#') {
          const targetElement = document.querySelector(targetId);
          if (targetElement && window.ScrollToPlugin) {
            gsap.to(window, { duration: 0.8, scrollTo: { y: targetElement, offsetY: 0 }, ease: 'power3.inOut' });
          } else if (targetElement) {
            targetElement.scrollIntoView({ behavior:'smooth', block:'start' });
          }
        } else {
          window.scrollTo({ top:0, behavior:'smooth' });
        }
      });
    });
  }
  
  // ===================================================================
  // [FIX APPLIED] HERO ANIMATION & PINNING SEQUENCE (REPLACES OLD CODE)
  // ===================================================================
  // I have replaced your original `animateHeroTitle` and `initializeSection1PinReveal`
  // functions with this corrected, chained sequence. This resolves the core issue.

  // STEP 1: Animate the title text into view on page load.
  function animateHeroTitle() {
      const title = document.querySelector('.brandon-hero-title');
      if (!title) {
          console.log("BRANDON DEBUG: Hero title element not found. Skipping sequence.");
          return;
      }
      console.log("BRANDON DEBUG: Found hero title, starting intro animation...");

      const mask = title.parentElement;
      if (mask) mask.classList.add('brandon-hero-title-mask');

      setTimeout(() => {
          // Revert any previous SplitText to avoid errors on SPA navigation
          if (title.split) title.split.revert();
          
          const split = new SplitText(title, { type: "lines", linesClass: "lineChild" });
          title.split = split; // Store instance for cleanup
          
          gsap.set(title, { opacity: 1 }); // Make visible before animating
          gsap.from(split.lines, {
              y: "120%", // Animate from below
              opacity: 0,
              duration: 1.2,
              ease: "circleEase",
              stagger: 0.1,
              onComplete: () => {
                  console.log("BRANDON DEBUG: Intro complete. Initializing pinning.");
                  // STEP 2: Once the intro is done, set up the pinning and scroll-out animation.
                  initializeSection1PinReveal();
              }
          });
      }, 150);
  }

  // STEP 3: Pin the section and animate the title text out on scroll.
  function initializeSection1PinReveal() {
      const pinSection = document.querySelector('.brandon-section1-pin');
      if (!pinSection) return;

      const title = pinSection.querySelector('.brandon-hero-title');
      if (!title || !title.split) {
          console.error("BRANDON DEBUG: Cannot pin. Title or SplitText instance is missing.");
          return;
      }
      
      console.log("BRANDON DEBUG: Initializing pinning and outro scroll.");
      
      gsap.timeline({
          scrollTrigger: {
              trigger: pinSection,
              pin: true,
              start: "top top",
              end: "bottom top", // Pin for the section's full height
              scrub: 1, // Smoothly ties animation to scrollbar
              pinSpacing: true, // Prevents content jump
              markers: true, // IMPORTANT: REMOVE FOR PRODUCTION
          }
      }).to(title.split.lines, {
          y: "-120%", // Animate upwards
          opacity: 0,
          duration: 1,
          ease: "circleEase",
          stagger: 0.05
      });
  }
  // ========= END OF APPLIED FIX =========

  // ======= DOT NAVIGATION (Original Preserved)=======
  function createDotNav() {
    if (isMobile() || !initializeGSAP()) return;
    const projects = document.querySelectorAll('.brandon-project-section');
    if (!projects.length) return;
    const oldNav = document.querySelector('.brandon-dot-nav');
    if (oldNav) oldNav.remove();
    const nav = document.createElement('div');
    nav.className = 'brandon-dot-nav';
    document.body.appendChild(nav);
    projects.forEach((section,i) => {
      const dot = document.createElement('button');
      dot.className = 'brandon-dot is-hidden';
      dot.setAttribute('data-index', i);
      dot.setAttribute('aria-label', `Jump to project ${i+1}`);
      nav.append(dot);
    });
    const dotEls = nav.querySelectorAll('.brandon-dot');
    projects.forEach((section,i) => {
      ScrollTrigger.create({
        trigger: section,
        start:   'top center+=80',
        end:     'bottom center',
        onEnter:      () => {
          dotEls[i].classList.replace('is-hidden','is-visible');
          dotEls.forEach((d,j)=>d.classList.toggle('is-active', j===i));
        },
        onLeaveBack:  () => {
          dotEls[i].classList.replace('is-visible','is-hidden');
          dotEls.forEach((d,j)=>{ if(j>=i) d.classList.remove('is-active'); });
        }
      });
      dotEls[i].addEventListener('click', e => {
        e.preventDefault();
        if (window.ScrollToPlugin) {
          gsap.to(window, { duration: 1, scrollTo: { y: section, offsetY: 0 }, ease: 'power3.inOut' });
        } else {
          section.scrollIntoView({ behavior:'smooth', block:'start' });
        }
      });
    });
  }

  // ========== P5.JS CANVAS HELPERS (Originals Preserved) ==========
  function createSafeP5Instance(factory, element, label) {
    if (prefersReducedMotion) return;
    element.querySelectorAll('canvas').forEach(c => c.remove());
    try {
      const sketch = new p5(factory(element), element);
      const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) { if (sketch.isLooping && !sketch.isLooping()) sketch.loop(); } 
          else { if (sketch.isLooping && sketch.isLooping()) sketch.noLoop(); }
        });
      }, { threshold: 0.1 });
      observer.observe(element);
    } catch (error) { console.error(`❌ P5 error in ${label}:`, error); }
  }

  function waitForElementSizeAndInit(element, p5Factory, label, maxWaitTime = 2000) {
    const startTime = Date.now();
    function attemptInit() {
      const hasSize   = element.offsetHeight > 20 && element.offsetWidth > 20;
      const hasCanvas = element.querySelector('canvas');
      const timeElapsed = Date.now() - startTime;
      if (hasSize && !hasCanvas) { createSafeP5Instance(p5Factory, element, label); } 
      else if (!hasSize && timeElapsed < maxWaitTime) { setTimeout(attemptInit, 100); }
    }
    attemptInit();
  }

  function getResponsiveGridSettings(canvasWidth) {
    if (canvasWidth < 600)    return { DOT_GAP:16, BASE_DOT_SIZE:2 };
    else if (canvasWidth < 1440) return { DOT_GAP:12, BASE_DOT_SIZE:1.5 };
    else                       return { DOT_GAP:16, BASE_DOT_SIZE:2 };
  }

  function computeGrid(w, h, dotGap, baseDotSize) {
    const spacing = dotGap + baseDotSize * 2; const cols = Math.ceil(w / spacing); const rows = Math.ceil(h / spacing); const result  = [];
    for (let r = 0; r < rows; r++) { for (let c = 0; c < cols; c++) { result.push({ x: c * spacing + spacing/2, y: r*spacing + spacing/2 }); } }
    return result;
  }

  function createHazeBackgroundSketch(containerElement) {
    return (p) => {
      const DOT_COLOR = [42,42,46], BG_COLOR = [14,14,16]; const BLOB_SCALE = 0.005, THRESHOLD = 0.64, FADE_RANGE = 0.17, ANIM_SPEED = 0.0002; let DOT_GAP, BASE_DOT_SIZE; function alphaRamp(x) { let t = Math.max(0,Math.min(1,x)); return t*t*(3-2*t); }
      p.setup = function() { p.createCanvas(containerElement.offsetWidth, containerElement.offsetHeight).style('pointer-events','none'); p.noStroke(); ({ DOT_GAP, BASE_DOT_SIZE } = getResponsiveGridSettings(p.width)); p.loop(); };
      p.draw = function() {
        p.background(...BG_COLOR); const t = p.millis() * ANIM_SPEED;
        for (let x = DOT_GAP/2; x < p.width; x += DOT_GAP) { for (let y = DOT_GAP/2; y < p.height; y += DOT_GAP) {
          const nx = x * BLOB_SCALE, ny = y * BLOB_SCALE; const field = 0.7*p.noise(nx,ny,t) +0.25*p.noise(nx*0.6, ny*0.6, t*1.7+99) +0.13*p.noise(nx*2.0, ny*2.0, t*0.8-7); const rawAlpha = (field - (THRESHOLD - FADE_RANGE)) / (2 * FADE_RANGE); const alpha = alphaRamp(rawAlpha);
          if (alpha > 0.02) { p.fill(...DOT_COLOR, alpha*255); p.ellipse(x, y, BASE_DOT_SIZE*2, BASE_DOT_SIZE*2); }
        }}
      };
      p.windowResized = function() { p.resizeCanvas(containerElement.offsetWidth, containerElement.offsetHeight); ({ DOT_GAP, BASE_DOT_SIZE } = getResponsiveGridSettings(p.width)); };
    };
  }

  function createWhiteHazeBackgroundSketch(containerElement) {
    return (p) => {
      const DOT_COLOR = [158,158,167], BG_COLOR = [242,242,243]; const BLOB_SCALE = 0.005, THRESHOLD = 0.64, FADE_RANGE = 0.17, ANIM_SPEED = 0.0002; let DOT_GAP, BASE_DOT_SIZE; function alphaRamp(x) { let t = Math.max(0,Math.min(1,x)); return t*t*(3-2*t); }
      p.setup = function() { p.createCanvas(containerElement.offsetWidth, containerElement.offsetHeight).style('pointer-events','none'); p.noStroke(); ({ DOT_GAP, BASE_DOT_SIZE } = getResponsiveGridSettings(p.width)); p.loop(); };
      p.draw = function() {
        p.background(...BG_COLOR); const t = p.millis() * ANIM_SPEED;
        for (let x = DOT_GAP/2; x < p.width; x += DOT_GAP) { for (let y = DOT_GAP/2; y < p.height; y += DOT_GAP) {
          const nx = x * BLOB_SCALE, ny = y * BLOB_SCALE; const field = 0.7*p.noise(nx,ny,t) +0.25*p.noise(nx*0.6, ny*0.6, t*1.7+99) +0.13*p.noise(nx*2.0, ny*2.0, t*0.8-7); const rawAlpha = (field - (THRESHOLD - FADE_RANGE)) / (2 * FADE_RANGE); const alpha = alphaRamp(rawAlpha);
          if (alpha > 0.02) { p.fill(...DOT_COLOR, alpha*255); p.ellipse(x, y, BASE_DOT_SIZE*2, BASE_DOT_SIZE*2); }
        }}
      };
      p.windowResized = function() { p.resizeCanvas(containerElement.offsetWidth, containerElement.offsetHeight); ({ DOT_GAP, BASE_DOT_SIZE } = getResponsiveGridSettings(p.width)); };
    };
  }

  function createLoadWaveSketch(containerElement) {
    return (p) => {
      const DOT_COLOR = [42,42,46], BG_COLOR = [14,14,16]; const WAVE_THICKNESS = 145, BREATH_SPEED = 247, FRONT_RATIO = 0.44, BACK_RATIO = 2.6; let dots = [], centerX, centerY, maxDist, startTime; let DOT_GAP, BASE_DOT_SIZE;
      p.setup = function() {
        p.createCanvas(containerElement.offsetWidth, containerElement.offsetHeight).style('pointer-events','none'); p.noStroke(); ({ DOT_GAP, BASE_DOT_SIZE } = getResponsiveGridSettings(p.width)); dots = computeGrid(p.width, p.height, DOT_GAP, BASE_DOT_SIZE); centerX = p.width/2; centerY = p.height/2; maxDist = Math.sqrt(centerX*centerX + centerY*centerY) + WAVE_THICKNESS; startTime = p.millis(); p.loop();
      };
      p.draw = function() {
        p.background(...BG_COLOR); const elapsedSec = (p.millis() - startTime) * 0.001; const fullDist   = elapsedSec * BREATH_SPEED; const revealDist = Math.min(fullDist, maxDist); const cycleLen   = maxDist + WAVE_THICKNESS; const pulseDist  = (fullDist % cycleLen);
        dots.forEach(dot => { const d = p.dist(dot.x, dot.y, centerX, centerY); if (d <= revealDist) { p.fill(...DOT_COLOR, 77); p.ellipse(dot.x, dot.y, BASE_DOT_SIZE*2, BASE_DOT_SIZE*2); } });
        const frontRange = WAVE_THICKNESS * FRONT_RATIO; const backRange  = WAVE_THICKNESS * BACK_RATIO;
        dots.forEach(dot => {
          const dx = dot.x - centerX, dy = dot.y - centerY; const d  = Math.sqrt(dx*dx + dy*dy);
          if (d <= revealDist) {
            const delta = d - pulseDist; let pulse = 0;
            if (delta >= -backRange && delta <= frontRange) { pulse = delta > 0 ? 1 - delta/frontRange : 1 - Math.abs(delta)/backRange; pulse = Math.pow(pulse, 0.6); pulse = Math.sin(pulse * Math.PI/2); }
            if (pulse > 0.01) {
              let mag = delta >= 0 ? 1 - delta/frontRange : 1 - (Math.abs(delta)/backRange)*0.5; mag = Math.max(0,Math.min(1,mag)) * pulse; let warp = delta >= 0 ? mag * BASE_DOT_SIZE : 0; const ux = dx/(d||1), uy = dy/(d||1); const wx = ux * warp, wy = uy * warp; const size  = BASE_DOT_SIZE + BASE_DOT_SIZE * mag; const alpha = 0.3 + 0.7 * pulse; p.fill(...DOT_COLOR, alpha*255); p.ellipse(dot.x + wx, dot.y + wy, size*2, size*2);
            }
          }
        });
      };
      p.windowResized = function() { p.resizeCanvas(containerElement.offsetWidth, containerElement.offsetHeight); ({ DOT_GAP, BASE_DOT_SIZE } = getResponsiveGridSettings(p.width)); dots = computeGrid(p.width, p.height, DOT_GAP, BASE_DOT_SIZE); centerX = p.width/2; centerY = p.height/2; maxDist = Math.sqrt(centerX*centerX + centerY*centerY) + WAVE_THICKNESS; };
    };
  }

  function injectBackgroundContainers() {
    const sectionConfigs = [
      { sectionClass: 'brandon-bg-load-wave', bgClass: 'brandon-load-wave', factory: createLoadWaveSketch, label: 'load-wave' },
      { sectionClass: 'brandon-bg-main-bg',  bgClass: 'brandon-main-background',  factory: createHazeBackgroundSketch,    label: 'main-background' },
      { sectionClass: 'brandon-bg-main-bg2', bgClass: 'brandon-main-background2', factory: createWhiteHazeBackgroundSketch,label: 'main-background2' }
    ];
    sectionConfigs.forEach(config => {
      document.querySelectorAll(`smp-section.${config.sectionClass}`).forEach(section => {
        if (!section.querySelector(`.${config.bgClass}`)) {
          const backgroundDiv = document.createElement('div');
          backgroundDiv.className = config.bgClass;
          section.prepend(backgroundDiv);
        }
      });
      document.querySelectorAll(`.${config.bgClass}`).forEach(element => {
        waitForElementSizeAndInit(element, config.factory, config.label);
      });
    });
  }

  // ========== MAIN INITIALIZATION FUNCTION ==========
  function initializeBrandonComponents() {
    console.log("BRANDON DEBUG: Initializing all components...");
    initializeButtonHandlers();
    initializeDotsGridMenu();
    initializeSmoothScrollHandlers();
    injectBackgroundContainers();
    createDotNav();
    // [FIX APPLIED] The main hero animation sequence is now the last thing to be called.
    animateHeroTitle();
  }

  // ========== CLEANUP BEFORE SPA RE-INIT ==========
  function cleanupBeforeInit() {
    // Reset dots-grid menu flag
    document.querySelectorAll('#brandonDotsGridMenu').forEach(menu => {
      menu.removeAttribute('data-brandon-initialized');
    });
    // [FIX APPLIED] Revert any SplitText splits created by the new animation logic.
    document.querySelectorAll('.brandon-hero-title').forEach(title => {
      if (title.split) {
        title.split.revert();
        title.split = null; // Clear the reference
      }
    });
    // [FIX APPLIED] Also kill all ScrollTriggers to prevent conflicts. This is critical.
    if(window.ScrollTrigger) {
        ScrollTrigger.getAll().forEach(trigger => trigger.kill());
    }
  }

  // ========== SPA-SAFE INITIALIZATION ==========
  function safeInitialize() {
    cleanupBeforeInit();
    setTimeout(initializeBrandonComponents, 100);
  }

  // DOM ready → first init
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeBrandonComponents);
  } else {
    initializeBrandonComponents();
  }

  // Re-init for Semplice AJAX & history nav
  window.addEventListener('sempliceAppendContent', safeInitialize);
  window.addEventListener('popstate', debounce(safeInitialize, 300));

  // Debug hooks
  window.BRANDON_DEBUG = {
    config:       BRANDON_CONFIG,
    reinitialize: initializeBrandonComponents,
    cleanup:      cleanupBeforeInit
  };

})();
