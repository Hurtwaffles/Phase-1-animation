// ==== BRANDON: GLOBAL SPA JS (Fully Optimized & Documented) ====
// Version: 2.5
// Date: 2025-06-12
// Author: Brandon Leach
// Description: Optimized custom animations and interactions for Semplice WordPress theme

(function() {
  'use strict';

  // ========== GSAP & CUSTOM EASE INITIALIZATION ==========
  function initializeGSAP() {
    if (window.gsap && window.CustomEase) {
      // Register custom ease if it doesn't exist
      if (!CustomEase.get("circleEase")) {
        CustomEase.create("circleEase", "0.68, -0.55, 0.265, 1.55");
      }
      return true;
    }
    return false;
  }

  // ========== CONFIGURATION OBJECTS ==========
  const BRANDON_CONFIG = {
    // Grid and wave animation settings
    grid: {
      subdivisions: 12,
      gapFactor: 8,
      baseDotSize: 1.5
    },
    wave: {
      dotColor: [42, 42, 46], // This is #2A2A2E
      alphaReveal: 77,
      thickness: 145,
      speed: 247,
      frontRatio: 0.44,
      backRatio: 2.6
    },
    // Animation timing
    timing: {
      buttonPress: 180,
      dotAnimation: 500,
      waveExpansion: 247
    }
  };
  
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ========== UTILITY FUNCTIONS ==========
  function isElement(el) {  
    return el && el.closest && typeof el.closest === 'function';  
  }

  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // ========== BUTTON & LOGO ANIMATION HANDLERS ==========
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
          setTimeout(() => btn.classList.remove('pressed'), BRANDON_CONFIG.timing.buttonPress);
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
        const menuTrigger = 
          document.querySelector('.open-menu.menu-icon') ||
          document.querySelector('.hamburger') ||
          document.querySelector('[data-module="menu"] .hamburger, [data-menu-type="hamburger"] .hamburger');
        
        if (menuTrigger) {
          menuTrigger.click();
        }
      }
    }, { capture: true });
  }

function initializeDotsGridMenu() {
  if (prefersReducedMotion) return;

  // 1. GSAP AND ELEMENT SETUP
  if (!initializeGSAP()) return;
  const menuBtn = document.getElementById('brandonDotsGridMenu');
  if (!menuBtn || menuBtn.dataset.brandonInitialized === 'true') {
    return;
  }
  menuBtn.dataset.brandonInitialized = 'true';

  const dots = menuBtn.querySelectorAll('.brandon-dot');
  if (dots.length !== 9) return;

  // 2. REGISTER THE CUSTOM EASE
  if (!CustomEase.get("rebrandEase")) {
    CustomEase.create("rebrandEase", "M0,0 C0.266,0.112 0.24,1.422 0.496,1.52 0.752,1.618 0.734,0.002 1,0");
  }

  // 3. ANIMATION CONSTANTS & STATE
  const DURATION = 0.5;
  const SPACING = 8;
  let isOpen = false;
  let menuTimeline = gsap.timeline({
    paused: true,
    onComplete: () => isOpen = true,
    onReverseComplete: () => isOpen = false,
  });

  // 4. DEFINE THE ANIMATION TIMELINE
  gsap.set(dots, {
    transformOrigin: 'center center'
  });

  menuTimeline
    .set(dots, {
      x: (i) => (i % 3) * SPACING - SPACING,
      y: (i) => Math.floor(i / 3) * SPACING - SPACING,
      scale: 1,
      opacity: 1,
    })
    .to(dots, {
      duration: DURATION,
      ease: "rebrandEase",
      stagger: {
        each: 0.04,
        from: "center",
        grid: [3, 3]
      },
      x: (i) => {
        const col = i % 3;
        if (col === 0) return SPACING;
        if (col === 2) return -SPACING;
        return 0;
      },
      y: (i) => {
        const row = Math.floor(i / 3);
        if (row === 0) return SPACING;
        if (row === 2) return -SPACING;
        return 0;
      },
      opacity: (i) => [1, 3, 5, 7].includes(i) ? 0 : 1,
      scale: (i) => (i === 4) ? 1.3 : 1,
    });
  
  // 5. OVERLAY CLOSE HANDLER
  // This remains important to reverse the animation when the overlay is closed
  const observer = new MutationObserver((mutations, obs) => {
    const overlayCloseBtn = document.querySelector('.close-overlay');
    if (overlayCloseBtn && !overlayCloseBtn.dataset.brandonCloseListener) {
        overlayCloseBtn.dataset.brandonCloseListener = 'true';
        overlayCloseBtn.addEventListener('click', () => {
            if (isOpen) {
                menuTimeline.reverse();
            }
        });
    }
  });
  observer.observe(document.body, { childList: true, subtree: true });


  // 6. MAIN CLICK HANDLER (UPDATED)
  // The event listener now ONLY controls the animation timeline.
  // The separate `initializeButtonHandlers` function handles the overlay trigger.
  menuBtn.addEventListener('click', function(e) {
    // Prevent the event from firing twice if it bubbles up
    e.stopPropagation(); 
    
    if (!isOpen) {
      menuTimeline.play();
    } else {
      // The native Semplice close button will reverse the animation via the listener above.
      // This allows a user to click the icon again to close the menu.
      menuTimeline.reverse();
    }
  });
}

  // ========== SMOOTH SCROLL HANDLERS (SPA-AWARE) ==========
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
            console.error('Smooth scroll target not found or invalid selector:', targetId, error);
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
  // Ensures all grid/dot backgrounds are consistent and responsive.
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
      const DOT_COLOR = [42, 42, 46];      // #2A2A2E
      const BG_COLOR = [14, 14, 16];       // #0E0E10
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
   * - BG: #F2F2F3 (light gray), DOTS: #9E9EA7 (muted lavender)
   */
  function createWhiteHazeBackgroundSketch(containerElement) {
    return (p) => {
      const DOT_COLOR = [158, 158, 167];   // #9E9EA7
      const BG_COLOR = [242, 242, 243];    // #F2F2F3
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
