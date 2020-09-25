<?php

namespace WordPress\Standard\Admin;

use WordPress\Standard\Traits\Form_Error;

class AddressBook
{
    public $errors;
    public function plugin_page()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';

        switch ($action) {
            case "new":
                $template = __DIR__ . "/views/address-new.php";
                break;
            case "edit":
                $template = __DIR__ . "/views/address-edit.php";
                break;
            case "view":
                $template = __DIR__ . "/views/address-view.php";
                break;
            default:
                $template = __DIR__ . "/views/address-list.php";
        }

        if (file_exists($template)) {
            include $template;
        }
    }

    public function form_handler()
    {
        if (!isset($_POST['submit_address'])) {
            return;
        }
        if (isset($_POST['_wpnonce']) && !wp_verify_nonce($_POST['_wpnonce'], 'new-address')) {
            wp_die("Hey stop!");
        }

        if (!current_user_can('manage_options')) {
            wp_die("I said stop");
        }

        $name    = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $address = isset($_POST['address']) ? sanitize_textarea_field($_POST['address']) : '';
        $phone   = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';

        if (!empty($this->errors)) {
            return;
        }

        $insert_id = wp_standard_insert_address([
            'name'    => $name,
            'address' => $address,
            'phone'   => $phone
        ]);

        if (is_wp_error($insert_id)) {
            wp_die($insert_id->get_error_message());
        }

        $redirected_to = admin_url('admin.php?page=wp-standard&inserted=true');
        wp_redirect($redirected_to);
        exit;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function delete_address()
    {
        if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'wp-standard-delete-address')) {
            wp_die('Hey stop!');
        }

        if (!current_user_can('manage_options')) {
            wp_die('Hey i said stop!');
        }

        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        if (wp_standard_delete_address($id)) {
            $redirected_to = admin_url('admin.php?page=wp-standard&address-deleted=true');
        } else {
            $redirected_to = admin_url('admin.php?page=wp-standard&address-deleted=false');
        }

        wp_redirect($redirected_to);
        exit;
    }
}
