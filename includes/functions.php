<?php

/**
 * Insert a new address
 *
 * @param  array  $args
 *
 * @return int|WP_Error
 */
function wp_standard_insert_address($args = [])
{
    global $wpdb;

    if (empty($args['name'])) {
        return new \WP_Error('no-name', __('You must provide a name.', 'wp-standard'));
    }

    $defaults = [
        'name'       => '',
        'address'    => '',
        'phone'      => '',
        'created_by' => get_current_user_id(),
        'created_at' => current_time('mysql'),
    ];

    $data = wp_parse_args($args, $defaults);

    $inserted = $wpdb->insert(
        $wpdb->prefix . '_addressbook',
        $data,
        [
            '%s',
            '%s',
            '%s',
            '%d',
            '%s'
        ]
    );

    if (!$inserted) {
        return new \WP_Error('failed-to-insert', __('Failed to insert data', 'wp-standard'));
    }

    return $wpdb->insert_id;
}

/**
 * 
 */
function wp_standard_get_addresses($args = [])
{
    global $wpdb;

    $defaults = [
        'number'  => 20,
        'offset'  => 0,
        'orderby' => 'id',
        'order'   => 'ASC'
    ];

    $args = wp_parse_args($args, $defaults);

    $sql = $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}_addressbook
            ORDER BY {$args['orderby']} {$args['order']}
            LIMIT %d, %d",
        $args['offset'],
        $args['number']
    );

    $items = $wpdb->get_results($sql);

    return $items;
}

/**
 * 
 */
function wp_standard_address_count()
{
    global $wpdb;

    return (int) $wpdb->get_var("SELECT count(id) FROM {$wpdb->prefix}_addressbook");
}

/**
 * Get Address
 *
 * @param [type] $id
 * @return void
 */
function wp_standard_get_address($id)
{
    global $wpdb;

    return $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM {$wpdb->prefix}_addressbook WHERE id = %d", $id)
    );
}

/**
 * Delete Address
 */
function wp_standard_delete_address($id)
{
    global $wpdb;

    return $wpdb->delete(
        $wpdb->prefix . '_addressbook',
        ['id' => $id],
        ['%d']
    );
}
