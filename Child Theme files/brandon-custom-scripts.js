// ==== BRANDON: GLOBAL SPA JS (Dot Grid X Animation Refined) ====
// Version: 2.7 (Dot Grid "X" Animation, codepen-matched)
// Date: 2025-06-15


// ========== DEBUG LOGGING ==========
function brandonLog(...args) {
  if (window && window.console) console.log('[BRANDON DOT GRID]', ...args);
}

(function() {
  'use strict';

  // ========== GSAP & CUSTOM EASE INITIALIZATION ==========
  function initializeGSAP() {
    if (window.gsap && window.CustomEase) {
      if (!CustomEase.get("rebrandEase")) {
        CustomEase.create("rebrandEase", "M0,0 C0.266,0.112 0.24,1.422 0.496,1.52 0.752,1.618 0.734,0.002 1,0");
      }
      brandonLog("GSAP and CustomEase loaded.");
      return true;
    }
    brandonLog("GSAP or CustomEase missing!");
    return false;
  }

  // ========== BUTTON HANDLER ==========
  function isElement(el) {  
    return el && el.closest && typeof el.closest === 'function';  
  }

  function initializeButtonHandlers() {
    if (window._brandonNavBtnHandlers) return;
    window._brandonNavBtnHandlers = true;

    const buttonSelector = '.brandon-animated-button-reveal, .brandon-logo-reveal-link';
    const workMenuSelector = '.brandon-work-menu-trigger';

    const pressEvents = ['mousedown', 'touchstart', 'keydown'];
    const releaseEvents = ['mouseup', 'mouseleave', 'touchend', 'touchcancel', 'keyup', 'blur'];

    pressEvents.forEach(evt => {
      document.addEventListener(evt, e => {
        const btn = isElement(e.target) ? e.target.closest(buttonSelector) : null;
        if (btn) {
          if (evt === 'keydown' && !['Enter', ' '].includes(e.key)) return;
          btn.classList.add('pressed');
          setTimeout(() => btn.classList.remove('pressed'), 180);
        }
      }, { passive: true, capture: true });
    });

    releaseEvents.forEach(evt => {
      document.addEventListener(evt, e => {
        const btn = isElement(e.target) ? e.target.closest(buttonSelector) : null;
        if (btn) btn.classList.remove('pressed');
      }, { passive: true, capture: true });
    });

    document.addEventListener('click', e => {
      const workMenuTrigger = isElement(e.target) ? e.target.closest(workMenuSelector) : null;
      if (workMenuTrigger) {
        e.preventDefault();
        brandonLog('WORK menu/dot grid trigger clicked');
        const menuTrigger = 
          document.querySelector('.open-menu.menu-icon') ||
          document.querySelector('.hamburger') ||
          document.querySelector('[data-module="menu"] .hamburger, [data-menu-type="hamburger"] .hamburger');
        if (menuTrigger) {
          brandonLog('Triggering native Semplice overlay menu');
          menuTrigger.click();
        }
      }
    }, { capture: true });
  }

  // ========== DOTS GRID MENU ICON (Grid <-> X, Synchronized with Overlay, Debug Logging) ==========
  function initializeDotsGridMenu() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      brandonLog('Reduced motion: grid dot animation disabled');
      return;
    }
    if (!initializeGSAP()) return;

    const menuBtn = document.getElementById('brandonDotsGridMenu');
    if (!menuBtn || menuBtn.dataset.brandonInitialized === 'true') {
      brandonLog('No menuBtn or already initialized');
      return;
    }
    menuBtn.dataset.brandonInitialized = 'true';

    const dots = Array.from(menuBtn.querySelectorAll('.brandon-dot'));
    if (dots.length !== 9) {
      brandonLog('Dot grid: incorrect dot count', dots.length);
      return;
    }

    // Spacing matches your previous (8px)
    const SPACING = 8;
    const DURATION = 0.5;
    let isOpen = false; // local fallback state

    // Set initial grid state (all dots in a 3x3 grid)
    gsap.set(dots, {
      x: (i) => (i % 3) * SPACING - SPACING,
      y: (i) => Math.floor(i / 3) * SPACING - SPACING,
      scale: 1,
      opacity: 1,
      transformOrigin: 'center center'
    });

    // X state positions
    const xState = [
      -SPACING,  // 0 (dot #1): top-left
      0,         // 1 (dot #2): center
      SPACING,   // 2 (dot #3): top-right
      0,         // 3 (dot #4): center
      0,         // 4 (dot #5): center
      0,         // 5 (dot #6): center
      -SPACING,  // 6 (dot #7): bot-left
      0,         // 7 (dot #8): center
      SPACING    // 8 (dot #9): bot-right
    ];
    const yState = [
      -SPACING,  // 0 (dot #1): top-left
      0,         // 1 (dot #2): center
      -SPACING,  // 2 (dot #3): top-right
      0,         // 3 (dot #4): center
      0,         // 4 (dot #5): center
      0,         // 5 (dot #6): center
      SPACING,   // 6 (dot #7): bot-left
      0,         // 7 (dot #8): center
      SPACING    // 8 (dot #9): bot-right
    ];

    // Timeline: grid -> X (forward), X -> grid (reverse)
    const menuTimeline = gsap.timeline({
      paused: true,
      onStart: () => brandonLog('Timeline started', { isOpen }),
      onComplete: () => { isOpen = true; brandonLog('Timeline complete/open', { isOpen }); },
      onReverseComplete: () => { isOpen = false; brandonLog('Timeline reversed/grid', { isOpen }); }
    }).to(dots, {
      duration: DURATION,
      ease: "rebrandEase",
      x: (i) => xState[i],
      y: (i) => yState[i],
      stagger: {
        each: 0.04,
        from: "center",
        grid: [3, 3]
      }
    });

    // ===== Overlay State Synchronization =====
    function isOverlayOpen() {
      // 1. Body class
      if (document.body.classList.contains('is-menu-open')) return true;
      // 2. Common overlay menu class
      const overlay = document.querySelector('.overlay-menu, .semplice-overlay, .semplice-menu-overlay, .menu-overlay, .menu__overlay, .overlay');
      if (overlay && (overlay.classList.contains('active') || overlay.classList.contains('is-active') || overlay.classList.contains('menu-open'))) return true;
      // 2b. Semplice overlay element with inline height (open state)
      const smpOverlay = document.querySelector('.smp-overlay');
      if (smpOverlay) {
        const height = parseFloat(smpOverlay.style.height || '0');
        if (height > 0) return true;
      }
      // 3. Fallback: check for visible hamburger close button
      const closeBtn = document.querySelector('.close-overlay, .close-menu, .menu__close');
      if (closeBtn && closeBtn.offsetParent !== null) return true;
      return false;
    }

    // Watch for overlay state changes and sync the timeline
    let lastOverlayOpen = isOverlayOpen();

    function syncTimelineWithOverlay() {
      const overlayOpen = isOverlayOpen();
      brandonLog('Overlay state poll:', { overlayOpen, lastOverlayOpen });
      if (overlayOpen && !lastOverlayOpen) {
        // Overlay just opened: play to X
        menuTimeline.play();
      } else if (!overlayOpen && lastOverlayOpen) {
        // Overlay just closed: reverse to grid
        menuTimeline.reverse();
      }
      lastOverlayOpen = overlayOpen;
    }

    // MutationObserver: watch for changes to body class or overlay menu
    const observer = new MutationObserver(syncTimelineWithOverlay);

    observer.observe(document.body, { attributes: true, attributeFilter: ['class'], subtree: false });
    const overlays = document.querySelectorAll('.overlay-menu, .semplice-overlay, .semplice-menu-overlay, .menu-overlay, .menu__overlay, .overlay');
    overlays.forEach(el => {
      observer.observe(el, { attributes: true, attributeFilter: ['class'], subtree: false });
    });

    // SPA-safe: poll as backup in case overlay is toggled by JS without attribute mutation
    setInterval(syncTimelineWithOverlay, 250);

    // On page load, set correct icon state
    setTimeout(syncTimelineWithOverlay, 100);

    // === Fallback: Animate on dot grid click IF overlay state cannot be detected ===
    menuBtn.addEventListener('click', function(e) {
      brandonLog('Dot grid menuBtn clicked. Fallback local state isOpen:', isOpen);
      if (!isOverlayOpen()) {
        menuTimeline.play();
        isOpen = true;
      } else {
        menuTimeline.reverse();
        isOpen = false;
      }
    });
  }

  // ========== SMOOTH SCROLL HANDLERS (unchanged) ==========
  function initializeSmoothScrollHandlers() {
    const scrollLinks = document.querySelectorAll('.brandon-logo-reveal-link[href^="#"], .brandon-animated-button-reveal[href^="#"]');
    scrollLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        const linkUrl = new URL(this.href);
        const currentUrl = new URL(window.location.href);
        if (linkUrl.pathname === currentUrl.pathname && linkUrl.search === currentUrl.search) {
          e.preventDefault();
          e.stopPropagation();
          const targetId = linkUrl.hash;
          try {
            if (targetId && targetId !== '#') {
              const targetElement = document.querySelector(targetId);
              if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
              }
            } else {
              window.scrollTo({ top: 0, behavior: 'smooth' });
            }
          } catch (error) {
            brandonLog('Smooth scroll target not found or invalid selector:', targetId, error);
          }
        }
      });
    });
  }
  
  // ========== P5.JS CANVAS HELPERS (WITH INTERSECTION OBSERVER) ==========
  function createSafeP5Instance(factory, element, label) {
    if (prefersReducedMotion) return;

    element.querySelectorAll('canvas').forEach(canvas => canvas.remove());

    try {
      const sketch = new p5(factory(element), element);
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            if (sketch.isLooping && !sketch.isLooping()) sketch.loop();
          } else {
            if (sketch.isLooping && sketch.isLooping()) sketch.noLoop();
          }
        });
      }, { threshold: 0.1 });
      observer.observe(element);
    } catch (error) {
      console.error(`❌ P5 error in ${label}:`, error);
    }
  }

  function waitForElementSizeAndInit(element, p5Factory, label, maxWaitTime = 2000) {
    const startTime = Date.now();
    function attemptInit() {
      const hasSize = element.offsetHeight > 20 && element.offsetWidth > 20;
      const hasCanvas = element.querySelector('canvas');
      const timeElapsed = Date.now() - startTime;
      if (hasSize && !hasCanvas) {
        createSafeP5Instance(p5Factory, element, label);
      } else if (!hasSize && timeElapsed < maxWaitTime) {
        setTimeout(attemptInit, 100);
      }
    }
    attemptInit();
  }

  // ========== RESPONSIVE DOT GRID BACKGROUNDS FOR SEMPLICE ==========
  function getResponsiveGridSettings(canvasWidth) {
    if (canvasWidth < 600) {
      return { DOT_GAP: 16, BASE_DOT_SIZE: 2 };
    } else if (canvasWidth < 1440) {
      return { DOT_GAP: 12, BASE_DOT_SIZE: 1.5 };
    } else {
      return { DOT_GAP: 16, BASE_DOT_SIZE: 2 };
    }
  }

  function computeGrid(w, h, dotGap, baseDotSize) {
    const spacing = dotGap + baseDotSize * 2;
    const cols = Math.ceil(w / spacing);
    const rows = Math.ceil(h / spacing);
    const result = [];
    for (let r = 0; r < rows; r++) {
      for (let c = 0; c < cols; c++) {
        result.push({ x: c * spacing + spacing / 2, y: r * spacing + spacing / 2 });
      }
    }
    return result;
  }

  /**
   * DARK HAZE / LIQUID HALFTONE BACKGROUND (e.g. homepage, dark sections)
   */
  function createHazeBackgroundSketch(containerElement) {
    return (p) => {
      const DOT_COLOR = [42, 42, 46];
      const BG_COLOR = [14, 14, 16];
      const BLOB_SCALE = 0.005, THRESHOLD = 0.64, FADE_RANGE = 0.17, ANIM_SPEED = 0.0002;
      let DOT_GAP, BASE_DOT_SIZE;
      function alphaRamp(x) { let t = Math.max(0, Math.min(1, x)); return t * t * (3 - 2 * t); }
      p.setup = function() {
        p.createCanvas(containerElement.offsetWidth, containerElement.offsetHeight)
          .style('pointer-events', 'none');
        p.noStroke();
        ({ DOT_GAP, BASE_DOT_SIZE } = getResponsiveGridSettings(p.width));
        p.loop();
      };
      p.draw = function() {
        p.background(...BG_COLOR);
        const t = p.millis() * ANIM_SPEED;
        for (let x = DOT_GAP / 2; x < p.width; x += DOT_GAP) {
          for (let y = DOT_GAP / 2; y < p.height; y += DOT_GAP) {
            const nx = x * BLOB_SCALE, ny = y * BLOB_SCALE;
            const field =
              0.7 * p.noise(nx, ny, t) +
              0.25 * p.noise(nx * 0.6, ny * 0.6, t * 1.7 + 99) +
              0.13 * p.noise(nx * 2.0, ny * 2.0, t * 0.8 - 7);
            const rawAlpha = (field - (THRESHOLD - FADE_RANGE)) / (2 * FADE_RANGE);
            const alpha = alphaRamp(rawAlpha);
            if (alpha > 0.02) {
              p.fill(...DOT_COLOR, alpha * 255);
              p.ellipse(x, y, BASE_DOT_SIZE * 2, BASE_DOT_SIZE * 2);
            }
          }
        }
      };
      p.windowResized = function() {
        p.resizeCanvas(containerElement.offsetWidth, containerElement.offsetHeight);
        ({ DOT_GAP, BASE_DOT_SIZE } = getResponsiveGridSettings(p.width));
      };
    };
  }

  /**
   * WHITE HAZE / LIQUID HALFTONE BACKGROUND (for project/light pages)
   */
  function createWhiteHazeBackgroundSketch(containerElement) {
    return (p) => {
      const DOT_COLOR = [158, 158, 167];
      const BG_COLOR = [242, 242, 243];
      const BLOB_SCALE = 0.005, THRESHOLD = 0.64, FADE_RANGE = 0.17, ANIM_SPEED = 0.0002;
      let DOT_GAP, BASE_DOT_SIZE;
      function alphaRamp(x) { let t = Math.max(0, Math.min(1, x)); return t * t * (3 - 2 * t); }
      p.setup = function() {
        p.createCanvas(containerElement.offsetWidth, containerElement.offsetHeight)
          .style('pointer-events', 'none');
        p.noStroke();
        ({ DOT_GAP, BASE_DOT_SIZE } = getResponsiveGridSettings(p.width));
        p.loop();
      };
      p.draw = function() {
        p.background(...BG_COLOR);
        const t = p.millis() * ANIM_SPEED;
        for (let x = DOT_GAP / 2; x < p.width; x += DOT_GAP) {
          for (let y = DOT_GAP / 2; y < p.height; y += DOT_GAP) {
            const nx = x * BLOB_SCALE, ny = y * BLOB_SCALE;
            const field =
              0.7 * p.noise(nx, ny, t) +
              0.25 * p.noise(nx * 0.6, ny * 0.6, t * 1.7 + 99) +
              0.13 * p.noise(nx * 2.0, ny * 2.0, t * 0.8 - 7);
            const rawAlpha = (field - (THRESHOLD - FADE_RANGE)) / (2 * FADE_RANGE);
            const alpha = alphaRamp(rawAlpha);
            if (alpha > 0.02) {
              p.fill(...DOT_COLOR, alpha * 255);
              p.ellipse(x, y, BASE_DOT_SIZE * 2, BASE_DOT_SIZE * 2);
            }
          }
        }
      };
      p.windowResized = function() {
        p.resizeCanvas(containerElement.offsetWidth, containerElement.offsetHeight);
        ({ DOT_GAP, BASE_DOT_SIZE } = getResponsiveGridSettings(p.width));
      };
    };
  }

  /**
   * LOAD WAVE ANIMATION (hero section: pulse forever after reveal)
   */
  function createLoadWaveSketch(containerElement) {
    return (p) => {
      const DOT_COLOR = [42, 42, 46];
      const BG_COLOR = [14, 14, 16];
      const WAVE_THICKNESS = 145, BREATH_SPEED = 247, FRONT_RATIO = 0.44, BACK_RATIO = 2.6;
      let dots = [], centerX, centerY, maxDist, startTime;
      let DOT_GAP, BASE_DOT_SIZE;

      p.setup = function() {
        p.createCanvas(containerElement.offsetWidth, containerElement.offsetHeight)
          .style('pointer-events', 'none');
        p.noStroke();
        ({ DOT_GAP, BASE_DOT_SIZE } = getResponsiveGridSettings(p.width));
        dots = computeGrid(p.width, p.height, DOT_GAP, BASE_DOT_SIZE);
        centerX = p.width / 2;
        centerY = p.height / 2;
        maxDist = Math.sqrt(centerX * centerX + centerY * centerY) + WAVE_THICKNESS;
        startTime = p.millis();
        p.loop();
      };

      p.draw = function() {
        p.background(...BG_COLOR);
        const elapsedSec = (p.millis() - startTime) * 0.001;
        const fullDist = elapsedSec * BREATH_SPEED;
        const revealDist = Math.min(fullDist, maxDist);
        const cycleLen = maxDist + WAVE_THICKNESS;
        const pulseDist = (fullDist % cycleLen);

        dots.forEach(dot => {
          const d = p.dist(dot.x, dot.y, centerX, centerY);
          if (d <= revealDist) {
            p.fill(...DOT_COLOR, 77); // 0.3 alpha
            p.ellipse(dot.x, dot.y, BASE_DOT_SIZE * 2, BASE_DOT_SIZE * 2);
          }
        });

        const frontRange = WAVE_THICKNESS * FRONT_RATIO;
        const backRange = WAVE_THICKNESS * BACK_RATIO;

        dots.forEach(dot => {
          const dx = dot.x - centerX, dy = dot.y - centerY;
          const d = Math.sqrt(dx * dx + dy * dy);
          if (d <= revealDist) {
            const delta = d - pulseDist;
            let pulse = 0;
            if (delta >= -backRange && delta <= frontRange) {
              pulse = delta > 0
                ? 1 - delta / frontRange
                : 1 - Math.abs(delta) / backRange;
              pulse = Math.pow(pulse, 0.6);
              pulse = Math.sin(pulse * Math.PI / 2);
            }
            if (pulse > 0.01) {
              let mag = delta >= 0
                ? 1 - (delta / frontRange)
                : 1 - (Math.abs(delta) / backRange) * 0.5;
              mag = Math.max(0, Math.min(1, mag)) * pulse;
              let warp = delta >= 0 ? mag * BASE_DOT_SIZE : 0;
              const ux = dx / (d || 1), uy = dy / (d || 1);
              const wx = ux * warp, wy = uy * warp;
              const size = BASE_DOT_SIZE + BASE_DOT_SIZE * mag;
              const alpha = 0.3 + 0.7 * pulse;
              p.fill(...DOT_COLOR, alpha * 255);
              p.ellipse(dot.x + wx, dot.y + wy, size * 2, size * 2);
            }
          }
        });
      };

      p.windowResized = function() {
        p.resizeCanvas(containerElement.offsetWidth, containerElement.offsetHeight);
        ({ DOT_GAP, BASE_DOT_SIZE } = getResponsiveGridSettings(p.width));
        dots = computeGrid(p.width, p.height, DOT_GAP, BASE_DOT_SIZE);
        centerX = p.width / 2;
        centerY = p.height / 2;
        maxDist = Math.sqrt(centerX * centerX + centerY * centerY) + WAVE_THICKNESS;
      };
    };
  }

  // ========== BACKGROUND CANVAS MANAGEMENT ==========
  function injectBackgroundContainers() {
    const sectionConfigs = [
      { sectionClass: 'brandon-bg-load-wave', bgClass: 'brandon-load-wave', factory: createLoadWaveSketch, label: 'load-wave' },
      { sectionClass: 'brandon-bg-main-bg', bgClass: 'brandon-main-background', factory: createHazeBackgroundSketch, label: 'main-background' },
      // WHITE VERSION FOR PROJECT PAGES — just add .brandon-bg-main-bg2 to the smp-section
      { sectionClass: 'brandon-bg-main-bg2', bgClass: 'brandon-main-background2', factory: createWhiteHazeBackgroundSketch, label: 'main-background2' }
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
    initializeButtonHandlers();
    initializeDotsGridMenu();
    initializeSmoothScrollHandlers();
    injectBackgroundContainers();
  }

  // ========== CLEANUP FUNCTION FOR SPA TRANSITIONS ==========
  function cleanupBeforeInit() {
    document.querySelectorAll('#brandonDotsGridMenu').forEach(menu => {
      menu.removeAttribute('data-brandon-initialized');
    });
    // Add cleanup for other components if needed in the future
  }

  // ========== SPA-SAFE INITIALIZATION ==========
  function safeInitialize() {
    cleanupBeforeInit();
    setTimeout(initializeBrandonComponents, 100);
  }

  // ========== EVENT LISTENERS ==========
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeBrandonComponents);
  } else {
    initializeBrandonComponents();
  }

  window.addEventListener('sempliceAppendContent', safeInitialize);
  window.addEventListener('popstate', debounce(safeInitialize, 300));

  // Export for debugging
  window.BRANDON_DEBUG = {
    config: BRANDON_CONFIG,
    reinitialize: initializeBrandonComponents,
    cleanup: cleanupBeforeInit
  };

})();
