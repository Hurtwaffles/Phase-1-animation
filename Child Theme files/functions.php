<?php
/**
 * Semplice Child Theme - functions.php
 *
 * Enqueues all custom and third-party scripts for the front-end only.
 * Now includes GSAP SplitText and ScrollToPlugin for advanced text animations.
 * Dequeues conflicting MediaElement scripts
 */

// Only load assets on the frontend (not in admin, Semplice editor, or Customizer)
add_action('wp_enqueue_scripts', function() {
    // Block on admin, Semplice editor, and Customizer preview
    if (
        !is_admin() &&
        !(isset($_GET['semplice']) && $_GET['semplice'] === 'editor') &&
        !isset($_GET['customize_changeset_uuid'])
    ) {
        // Custom CSS
        wp_enqueue_style(
            'brandon-child-style',
            get_stylesheet_directory_uri() . '/style.css',
            array(),
            filemtime(get_stylesheet_directory() . '/style.css')
        );

        // Custom JS
        wp_enqueue_script(
            'brandon-custom-scripts',
            get_stylesheet_directory_uri() . '/brandon-custom-scripts.js',
            array('jquery'),
            filemtime(get_stylesheet_directory() . '/brandon-custom-scripts.js'),
            true
        );

        // GSAP + plugins from CDN
        wp_enqueue_script(
            'gsap',
            'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',
            array(),
            '3.12.5',
            true
        );
        wp_enqueue_script(
            'gsap-custom-ease',
            'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js',
            array('gsap'),
            '3.12.5',
            true
        );
        wp_enqueue_script(
            'gsap-splittext',
            'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/SplitText.min.js',
            array('gsap'),
            '3.12.5',
            true
        );
        wp_enqueue_script(
            'gsap-scrollto',
            'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js',
            array('gsap'),
            '3.12.5',
            true
        );
    }
}, 10);

// REMOVE MediaElement scripts/styles if needed (frontend only)
add_action('wp_enqueue_scripts', function() {
    if (
        !is_admin() &&
        !(isset($_GET['semplice']) && $_GET['semplice'] === 'editor') &&
        !isset($_GET['customize_changeset_uuid'])
    ) {
        wp_dequeue_script('wp-mediaelement');
        wp_dequeue_style('wp-mediaelement');
    }
}, 100);
