<div class="wrap">
  <?php screen_icon('awwapp'); ?>
  <h2><?php _e('Shared Whiteboard', self::ld); ?></h2>

  <?php
  if ($message == 'saved')
    echo '<div class="updated"><p>'.__('Settings were successfully saved.', self::ld).'</p></div>';
  ?>

  <h4><?php echo sprintf(__('You can insert shared whiteboard via shortcode [%s] into posts.', self::ld), self::shortcode); ?></h4>

  <form action="<?php echo $url; ?>" method="post">
    <?php
    wp_nonce_field(self::nonce);

    require_once $this->_path.'/admin/options_table.php';

    submit_button(__('Save Changes', self::ld), 'primary', 'awwapp_save', true);
    ?>
  </form>
</div>