<?php
/**
 * Semplice Child Theme - functions.php
 *
 * Enqueues all custom and third-party scripts for the front-end only.
 * Now includes GSAP SplitText and ScrollToPlugin for advanced text animations.
 * Dequeues conflicting MediaElement scripts
 * Simplified without fallbacks - relies on modern browsers
 * Updated: 2025-06-16 by Hurtwaffles
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

        // Custom JS with dependency checks
        wp_enqueue_script(
            'brandon-custom-scripts',
            get_stylesheet_directory_uri() . '/brandon-custom-scripts.js',
            array('jquery', 'gsap', 'p5js'),
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
        
        // P5.js for canvas animations
        wp_enqueue_script(
            'p5js',
            'https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.7.0/p5.min.js',
            array(),
            '1.7.0',
            true
        );
        
        // Localize script for AJAX security
        wp_localize_script('brandon-custom-scripts', 'brandon_ajax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('brandon_nonce'),
            'debug' => WP_DEBUG
        ));
    }
}, 10);

// Add capability check for admin actions
add_action('wp_ajax_brandon_action', 'handle_brandon_ajax');
add_action('wp_ajax_nopriv_brandon_action', 'handle_brandon_ajax');

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

// Handle AJAX requests with proper security
function handle_brandon_ajax() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'brandon_nonce')) {
        wp_die('Security check failed');
    }
    
    // Check capabilities
    if (!current_user_can('edit_posts')) {
        wp_die('Insufficient permissions');
    }
    
    // Sanitize input
    $action = sanitize_text_field($_POST['action_type']);
    
    // Process request
    $response = array('status' => 'success');
    
    wp_send_json($response);
}

// Add Content Security Policy headers
add_action('send_headers', function() {
    header("Content-Security-Policy: script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net;");
});