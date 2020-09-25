<?php

/**
 * Plugin Name: WordPress Plugin Standard Structure by Tareq Hasan
 * Description: This tutorial describe how plugin should be written
 * Plugin URI: https://www.saifulislam.me
 * Version: 1.0.0
 * Author: Saiful Islam
 * Auhtor URI: https://www.saifulislam.me
 * Text Domain: wp-standard
 * License: GPL-2.0+
 * Domain Path: /languages
 */

//use Elementor\Modules\System_Info\Reporters\WordPress;

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

final class WPStandard
{
    /**
     * Plugin Version
     */
    const version = '1.0';

    /**
     * Undocumented function
     */
    private function __construct()
    {
        $this->define_constants();
        // activation hook
        register_activation_hook(__FILE__, [$this, 'activate']);
        // load plugin important file
        add_action('plugins_loaded', [$this, 'init_plugin']);
    }

    /**
     * run this function when plugin got activate
     *
     * @return void
     */
    public function activate()
    {
        $installer = new WordPress\Standard\Installer();
        $installer->run();
    }

    /**
     * includes plugin important file
     *
     * @return void
     */
    public function init_plugin()
    {
        if (is_admin()) {
            new WordPress\Standard\Admin();
        } else {
            new WordPress\Standard\Frontend();
        }
    }

    /**
     * init function for single tone approach
     *
     * @return void
     */
    public static function init()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }
        return $instance;
    }

    public function define_constants()
    {
        define('WP_STANDARD_VERSION', self::version);
        define('WP_STANDARD_FILE', __FILE__);
        define('WP_STANDARD_PATH', __DIR__);
        define('WP_STANDARD_URL', plugins_url('', WP_STANDARD_FILE));
        define('WP_STANDARD_ASSETS', WP_STANDARD_URL . '/assets');
    }
}

/**
 * initialise the main function
 *
 * @return void
 */
function wp_standard()
{
    return WPStandard::init();
}

// let's start the plugin
wp_standard();
