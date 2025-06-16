<?php
/**
 * Semplice Child Theme - functions.php
 *
 * Enqueues all custom and third-party scripts for the front-end only.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add error logging for debugging
if (!function_exists('write_log')) {
    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}

add_action('wp_enqueue_scripts', 'brandon_enqueue_custom_scripts');

function brandon_enqueue_custom_scripts() {
    // Only run on the public-facing site, not in the WordPress admin area.
    if (is_admin()) {
        return;
    }

    try {
        // --- STYLES ---
        wp_enqueue_style(
            'brandon-custom-styles',
            get_stylesheet_uri(),
            array(),
            '1.0.0'
        );

        // --- THIRD-PARTY LIBRARIES ---
        // Core GSAP
        wp_enqueue_script(
            'gsap-core',
            'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',
            array(),
            '3.12.5',
            true
        );

        // P5.js
        wp_enqueue_script(
            'brandon-p5',
            'https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.9.4/p5.min.js',
            array(),
            '1.9.4',
            true
        );

        // GSAP Plugins
        wp_enqueue_script(
            'brandon-gsap-ce',
            'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js',
            array('gsap-core'),
            '3.12.5',
            true
        );

        wp_enqueue_script(
            'brandon-gsap-st',
            'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js',
            array('gsap-core'),
            '3.12.5',
            true
        );

        // --- CUSTOM JS ---
        wp_enqueue_script(
            'brandon-custom-scripts',
            get_stylesheet_directory_uri() . '/brandon-custom-scripts.js',
            array('gsap-core', 'brandon-p5', 'brandon-gsap-ce', 'brandon-gsap-st'),
            '1.0.3',
            true
        );

    } catch (Exception $e) {
        write_log('Brandon Child Theme Error: ' . $e->getMessage());
    }
}
