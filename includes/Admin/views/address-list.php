<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e("Address Book", "wp-standard") ?></h1>
    <a href="<?php echo admin_url("admin.php?page=wp-standard&action=new") ?>" class="page-title-action"><?php _e("Add New", "wp-standard") ?></a>

    <?php if (isset($_GET['inserted'])) { ?>
        <div class="notice notice-success">
            <p><?php _e('Address has been added successfully!', 'wp-standard'); ?></p>
        </div>
    <?php } ?>

    <?php if (isset($_GET['address-deleted']) && $_GET['address-deleted'] == 'true') { ?>
        <div class="notice notice-success">
            <p><?php _e('Address has been deleted successfully!', 'wp-standard'); ?></p>
        </div>
    <?php } ?>

    <form action="" method="post">
        <?php
        $table = new WordPress\Standard\Admin\AddressList();
        $table->prepare_items();
        $table->search_box('search', 'search_id');
        $table->display();
        ?>
    </form>
</div>