<div id="awwapp_shortcode_dialog">

  <?php
  unset($this->options_def['apiKey']);

  foreach($this->options_def as &$opt)
  {
    if (isset($opt['helper']))
      unset($opt['helper']);

    if (isset($opt['values']))
      $opt['values'][-1] = __('Use global option', self::ld);

    if ($opt['type'] == 'select')
      $opt['force_value'] = -1;
    else
      $opt['force_value'] = '';
  }

  require_once $this->_path.'/admin/options_table.php';
  ?>

  <div class="awwapp-buttonbox">
    <input type="submit" class="button button-primary" name="awwapp_insert" value="<?php esc_attr_e('Insert', self::ld); ?>" />
    <input type="button" class="button button-secondary" name="awwapp_cancel" value="<?php esc_attr_e('Cancel', self::ld); ?>" />
  </div>
</div>