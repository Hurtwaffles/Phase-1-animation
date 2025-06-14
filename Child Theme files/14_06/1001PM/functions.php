<?php
/**
 * Semplice Child Theme - functions.php
 *
 * Enqueues custom scripts/styles and dequeues conflicting MediaElement scripts.
 * No output before headers.
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Helper for debugging.
if ( ! function_exists( 'write_log' ) ) {
    function write_log( $log ) {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
}

/**
 * Enqueue child theme styles and custom/front-end scripts.
 */
function brandon_enqueue_custom_assets() {
    // Skip admin.
    if ( is_admin() ) {
        return;
    }

    // 1) CHILD THEME STYLE (after parent)
    wp_enqueue_style(
        'semplice-child-style',
        get_stylesheet_uri(),
        array( 'semplice-frontend' ),
        '1.0.0'
    );

    // 2) GSAP CORE
    wp_enqueue_script(
        'gsap-core',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',
        array(),
        '3.12.5',
        true
    );

    // 3) GSAP PLUGINS: ScrollTrigger, SplitText, ScrollToPlugin, CustomEase
    wp_enqueue_script(
        'gsap-scrolltrigger',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js',
        array( 'gsap-core' ),
        '3.12.5',
        true
    );
    wp_enqueue_script(
        'gsap-splittext',
        'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/SplitText.min.js',
        array( 'gsap-core' ),
        '3.13.0',
        true
    );
    wp_enqueue_script(
        'gsap-scrollto',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js',
        array( 'gsap-core' ),
        '3.12.5',
        true
    );
    wp_enqueue_script(
        'gsap-customease',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js',
        array( 'gsap-core' ),
        '3.12.5',
        true
    );

    // 4) P5.JS FOR BACKGROUND CANVASES
    wp_enqueue_script(
        'p5js',
        'https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.9.4/p5.min.js',
        array(),
        '1.9.4',
        true
    );

    // 5) YOUR GLOBAL CUSTOM JS
    wp_enqueue_script(
        'brandon-custom-scripts',
        get_stylesheet_directory_uri() . '/brandon-custom-scripts.js',
        array(
            'gsap-core',
            'gsap-scrolltrigger',
            'gsap-splittext',
            'gsap-scrollto',
            'gsap-customease',
            'p5js',
        ),
        '1.0.4',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'brandon_enqueue_custom_assets', 20 );

/**
 * Dequeue any core MediaElement scripts to prevent JS errors.
 */
function brandon_dequeue_mediaelement_conflicts() {
    if ( is_admin() ) {
        return;
    }
    global $wp_scripts;
    foreach ( $wp_scripts->registered as $handle => $script ) {
        if ( strpos( $script->src, 'mediaelement' ) !== false ) {
            wp_dequeue_script( $handle );
            // wp_deregister_script( $handle ); // Uncomment if deregistration is needed
        }
    }
}
add_action( 'wp_enqueue_scripts', 'brandon_dequeue_mediaelement_conflicts', 5 );
