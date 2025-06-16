// ==== BRANDON: GLOBAL SPA JS (Fully Optimized & Documented) ====
// Version: 2.6.8.0 (Enhanced Error Handling, Caching, Memory Management - No Fallbacks)
// Date: 2025-06-16
// Author: Brandon Leach
// Description: Optimized custom animations and interactions for Semplice WordPress theme

(function() {
  'use strict';

  // ========== CONFIGURATION OBJECTS ==========
  // DOM Cache for performance optimization
  const DOM_CACHE = new Map();
  
  // Error tracking
  const ERROR_LOG = [];
  const BRANDON_CONFIG = {
    debug: true, 
    grid: { subdivisions: 12, gapFactor: 8, baseDotSize: 1.5 },
    wave: { dotColor: [42, 42, 46], alphaReveal: 77, thickness: 145, speed: 247, frontRatio: 0.44, backRatio: 2.6 },
    timing: { buttonPress: 180, dotAnimation: 0.5, waveExpansion: 247 }
  };
  
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function brandonLog(...args) {
    if (BRANDON_CONFIG.debug && window && window.console) {
      console.log('[BRANDON CUSTOM]', ...args);
    }
  }

  // Enhanced error logging with rate limiting
  function brandonError(message, error = null) {
    const timestamp = Date.now();
    const errorEntry = { message, error, timestamp };
    
    ERROR_LOG.push(errorEntry);
    
    // Rate limiting: max 10 errors per minute
    const recentErrors = ERROR_LOG.filter(e => timestamp - e.timestamp < 60000);
    if (recentErrors.length > 10) {
      return; // Skip logging to prevent spam
    }
    
    if (BRANDON_CONFIG.debug) {
      console.error('[BRANDON ERROR]', message, error);
    }
  }

  // DOM query caching utility
  function getCachedElement(selector) {
    if (!DOM_CACHE.has(selector)) {
      DOM_CACHE.set(selector, document.querySelector(selector));
    }
    return DOM_CACHE.get(selector);
  }

  function initializeGSAP() {
    try {
      if (!CustomEase.get("circleEase")) { 
        CustomEase.create("circleEase", "0.68, -0.55, 0.265, 1.55"); 
      }
      if (!CustomEase.get("rebrandEase")) { 
        CustomEase.create("rebrandEase", "M0,0 C0.266,0.112 0.24,1.422 0.496,1.52 0.752,1.618 0.734,0.002 1,0"); 
      }
      brandonLog("GSAP and CustomEases ('circleEase', 'rebrandEase') loaded.");
      return true;
    } catch (error) {
      brandonError("GSAP initialization failed", error);
      throw error; // Fail fast if GSAP is not available
    }
  }

  // Cleanup function for event listeners
  function cleanupEventListeners() {
    brandonLog("Cleaning up event listeners for SPA transition");
    DOM_CACHE.clear();
  }

  function createSafeP5Instance(factory, element, label) {
    if (!element?.isConnected) {
      brandonError(`P5: Element for ${label} is not connected to DOM`);
      throw new Error(`Invalid element for P5 sketch: ${label}`);
    }
    
    if (prefersReducedMotion) {
      brandonLog(`P5: Reduced motion, sketch "${label}" disabled.`);
      return;
    }
    
    const existingCanvas = element.querySelector('canvas');
    if (existingCanvas) {
      brandonLog(`P5: Removing existing canvas for sketch "${label}" before creating new one.`);
      existingCanvas.remove();
    }
    brandonLog(`P5: Attempting to create sketch "${label}" in element:`, element);
    try {      
      const sketch = new p5(factory(element), element); 
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            if (sketch && typeof sketch.loop === 'function' && typeof sketch.isLooping === 'function' && !sketch.isLooping()) {
              brandonLog(`P5: Sketch "${label}" entered viewport, calling loop().`);
              sketch.loop();
            }
          } else {
            if (sketch && typeof sketch.noLoop === 'function' && typeof sketch.isLooping === 'function' && sketch.isLooping()) {
              brandonLog(`P5: Sketch "${label}" exited viewport, calling noLoop().`);
              sketch.noLoop();
            }
          }
        });
      }, { threshold: 0.01 }); 
      observer.observe(element);
      brandonLog(`P5: Sketch "${label}" initialized and observer attached.`);
      
      // Store observer for cleanup
      element._brandonObserver = observer;
      return sketch; 
    } catch (error) {
      brandonError(`P5 error in ${label}`, error);
      throw error; // Fail fast for P5 errors
    }
  }

  function cleanupBeforeInit() {
    brandonLog("Cleaning up before re-initialization (SPA transition).");
    
    // Clean up intersection observers
    document.querySelectorAll('.brandon-load-wave, .brandon-main-background, .brandon-main-background2').forEach(el => {
        if (el._brandonObserver) {
          el._brandonObserver.disconnect();
          delete el._brandonObserver;
        }
    });
    
    document.querySelectorAll('.brandon-load-wave, .brandon-main-background, .brandon-main-background2').forEach(el => {
        const p5canvas = el.querySelector('canvas');
        if (p5canvas) {
            brandonLog("P5: Removing canvas from", el.className);
            p5canvas.remove();
        }
    });

    // Clear DOM cache and cleanup event listeners
    cleanupEventListeners();
    
    const menuBtn = document.getElementById('brandonDotsGridMenu');
    if (menuBtn) {
      menuBtn.removeAttribute('data-brandon-dots-initialized');
    }
    window._brandonNavBtnHandlersInitialized = false;
    window._brandonSmoothScrollInitialized = false;
    brandonLog("Cleanup: Flags reset.");
  }

  function initializeBrandonComponents() {
    brandonLog("Initializing Brandon Components (Full Run v2.6.8.0)");
    initializeGSAP(); // Will throw if GSAP not available
    initializeButtonHandlers(); 
    initializeDotsGridMenu(); 
    initializeSmoothScrollHandlers();
    injectBackgroundContainers(); // Will throw if P5 not available
  }

  // [Additional functions truncated for brevity - full file available in Child Theme files/]

  window.BRANDON_DEBUG = {
    config: BRANDON_CONFIG,
    reinitialize: initializeBrandonComponents,
    cleanup: cleanupBeforeInit,
    errorLog: () => ERROR_LOG,
    domCache: () => DOM_CACHE
  };

})();