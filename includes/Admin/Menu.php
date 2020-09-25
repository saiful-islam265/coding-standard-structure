<?php

namespace WordPress\Standard\Admin;

//use WordPress\Standard\AddressBook;

class Menu
{
    public $addressbook;
    public function __construct($addressbook)
    {
        $this->addressbook = $addressbook;
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    public function admin_menu()
    {
        $parent_slug = "wp-standard";
        $capability = "manage_options";
        add_menu_page(__("Coding Standard By WeDevs", 'wp-standard'), __('Coding Standard', 'wp-standard'), $capability, 'wp-standard', [$this->addressbook, 'plugin_page'], 'dashicons-code-standards', null);
        add_submenu_page($parent_slug, __("Address Book", "wp-standard"), __("Address Book", "wp-standard"), $capability, $parent_slug, [$this->addressbook, 'plugin_page'], null);
        add_submenu_page($parent_slug, __("Settings", "wp-standard"), __("Settings", "wp-standard"), $capability, "wp-standard-settings", [$this, 'wp_standard_settings'], null);
    }

    public function wp_standard_settings()
    {
        echo "Hello Settings page";
    }
}
