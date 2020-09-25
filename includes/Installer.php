<?php

namespace WordPress\Standard;

class Installer
{
    public function run()
    {
        $this->add_version();
        $this->create_tables();
    }

    public function add_version()
    {
        $installed = get_option('wp_standard_install');
        if (!$installed) :
            update_option('wp_standard_installed', time());
        endif;
        update_option('wp_standard_version', WP_STANDARD_VERSION);
    }

    public function create_tables()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}_addressbook` (
            `id` int(11) NOT NULL,
            `name` varchar(100) NOT NULL,
            `address` varchar(255) DEFAULT NULL,
            `phone` varchar(30) DEFAULT NULL,
            `created_by` bigint(20) NOT NULL,
            `created_at` datetime NOT NULL
          ) $charset_collate";

        if (!function_exists('dbDelta')) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }
        dbDelta($schema);
    }
}
