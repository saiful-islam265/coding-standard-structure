<?php

namespace WordPress\Standard\Admin;

use WordPress\Standard\Traits\Form_Error;

class AddressBook
{
    use Form_Error;
    // public $errors;
    public function plugin_page()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';
        $id     = isset($_GET['id']) ? intval($_GET['id']) : 0;

        switch ($action) {
            case "new":
                $template = __DIR__ . "/views/address-new.php";
                break;
            case "edit":
                $address  = wp_standard_get_address($id);
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

        $id      = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $name    = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $address = isset($_POST['address']) ? sanitize_textarea_field($_POST['address']) : '';
        $phone   = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';

        if (empty($phone)) {
            $this->errors['phone'] = __('Please provide a phone number.', 'wp-standard');
        }

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

        if ($id) {
            $redirected_to = admin_url('admin.php?page=wp-standard&action=edit&address-updated=true&id=' . $id);
        } else {
            $redirected_to = admin_url('admin.php?page=wp-standard&inserted=true');
        }

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
