<?php

namespace Agrilife_Unit;

/**
 * Add assets
 * @package Agrilife.org
 * @since 0.1.0
 */
class Assets {

    public function __construct() {

        // Register global styles used in the theme
        add_action( 'admin_footer', array( $this, 'register_admin_styles' ) );

        // Register global styles used in the theme
        add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );

        // Enqueue extension styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

        // Dequeue default styles
        add_action( 'wp_print_styles', array( $this, 'dequeue_default_styles'), 5 );

    }

    /**
     * Registers all admin styles used within the plugin
     * @since 0.1.0
     * @return void
     */
    public function register_admin_styles() {
        wp_register_style(
            'agrilife-admin-styles',
            ALUAF4_DIR_URL . 'css/admin.css',
            array(),
            filemtime(ALUAF4_DIR_PATH . 'css/admin.css'),
            'screen'
        );

        wp_enqueue_style('agrilife-admin-styles');
    }

    /**
     * Registers all styles used within the plugin
     * @since 0.1.0
     * @return void
     */
    public function register_styles() {

        wp_register_style(
            'agrilife-googlefonts',
            'https://fonts.googleapis.com/css?family=Oswald|Monoton|Open+Sans'
        );

        wp_register_style(
            'agrilife-styles',
            ALUAF4_DIR_URL . 'css/agrilife.css',
            array(),
            filemtime(ALUAF4_DIR_PATH . 'css/agrilife.css'),
            'screen'
        );

        wp_register_style(
            'agrilife-com-styles',
            ALUAF4_DIR_URL . 'css/communications.css',
            array(),
            filemtime(ALUAF4_DIR_PATH . 'css/communications.css'),
            'screen'
        );

    }

    /**
     * Enqueues extension styles
     * @since 0.1.0
     * @global $wp_styles
     * @return void
     */
    public function enqueue_styles() {

        wp_enqueue_style( 'agrilife-googlefonts' );

        wp_enqueue_style( 'agrilife-styles' );

        wp_enqueue_style( 'agrilife-communications-styles' );

    }

    /**
     * Dequeues global styles
     * @since 1.0
     * @global $wp_styles
     * @return void
     */
    public function dequeue_default_styles() {

        wp_dequeue_style( 'agriflex-default-styles' );

    }

}
