<?php
/*
Plugin Name: Shared Whiteboard
Plugin URI: http://wordpress.org/plugins/shared-whiteboard
Description: A Web Whiteboard is touch-friendly online whiteboard app that lets you use your computer, tablet or smartphone to easily draw sketches, collaborate with others and share them with the world.
Author: A Web Whiteboard
Version: 1.0
Author URI: http://awwapp.com
License: GPLv2 or later
Text Domain: awwapp
*/

class AWWApp
{
  // version of the plugin, should be changed with version from the header
  const version = '1.0';

  // name of shortcode used to insert whiteboard into post
  const shortcode = 'whiteboard';

  const ld = 'awwapp';
  const nonce = 'awwapp-nonce';

  private $_url, $_path, $counter = 0;

  protected $default_options, $options, $options_def;


  public function __construct()
  {
    // paths
    $this->_url = plugins_url('', __FILE__);
    $this->_path = dirname(__FILE__);

    // called to load locale file
    add_action('plugins_loaded', array($this, 'plugins_loaded'));

    // definition of default global options
    $this->default_options = array(
      'apiKey' => '',
      'orientation' => 'auto',
      'toolbar' => 1,
      'smallIcons' => 0,
      'shrinkCanvas' => 1,
      'autoJoin' => 1,
      'font' => 'bold 14px verdana, sans-serif',
      'lang' => 'en',
      'width' => '100%',
      'height' => '300px',
      'border' => 1
    );

    // available orientations
    $orientations = array(
      'auto' => __('Auto', self::ld),
      'portrait' => __('Portrait', self::ld),
      'landscape' => __('Landscape', self::ld)
    );

    // languages used with awwapp
    $languages = array(
      'en' => __('English', self::ld),
      //'es' => __('Spanish', self::ld)
    );

    // definition of options form
    $yesno = array(__('No', self::ld), __('Yes', self::ld));

    $this->options_def = array(
      'apiKey' => array(
        // option name
        'name' => __('API Key', self::ld),
        // hover title
        'title' => __("Your <a href='http://awwapp.com/blog/plans-and-pricing/' target='_blank'>premium subscription API Key</a>, needed for board sharing. If you don't need shared boards, leave it empty.", self::ld),
        // type of input - text or select
        'type' => 'text',
        // class attribute
        'class' => 'regular-text'
      ),

      'singleBoardId' => array(
        'name' => __('Board ID', self::ld),
        'title' => __('If set, the plugin will connect to this shared board for every visitor coming to your page or post. If empty, visitors will manually have to click "Invite" and copy/paste the link to share it.', self::ld),
        'type' => 'text',
        'helper' => __('Shortcode option: singleBoardId="..."', self::ld),
        'values' => $orientations
      ),

      'autoJoin' => array(
        'name' => __('Auto Join', self::ld),
        'title' => __('Automatically join shared board.', self::ld),
        'type' => 'select',
        'helper' => __('Shortcode option: autoJoin=[0|1]', self::ld),
        'values' => $yesno
      ),

      'width' => array(
        'name' => __('Width', self::ld),
        'title' => __('Width of the whiteboard in pixels or percents (examples: 100%, or 300px).', self::ld),
        'type' => 'text',
        'helper' => __('Shortcode option: width="..."', self::ld)
      ),

      'height' => array(
        'name' => __('Height', self::ld),
        'title' => __('Height of the whiteboard in pixels (example: 300px).', self::ld),
        'type' => 'text',
        'helper' => __('Shortcode option: height="..."', self::ld)
      ),

      'lang' => array(
        'name' => __('Language', self::ld),
        'title' => __('Language for the whiteboard interface.', self::ld),
        'type' => 'select',
        'helper' => __('Shortcode option: lang=[en]', self::ld),
        'values' => $languages
      ),

      'toolbar' => array(
        'name' => __('Show Toolbar', self::ld),
        'title' => __('Whether the toolbar should be shown on the whiteboard; setting this to false hides the toolbar completely.', self::ld),
        'type' => 'select',
        'helper' => __('Shortcode option: toolbar=[0|1]', self::ld),
        'values' => $yesno
      ),

      'smallIcons' => array(
        'name' => __('Small Icons', self::ld),
        'title' => __("Whether the toolbar should use small icons (without text), so there's more room for the actual whiteboard.", self::ld),
        'type' => 'select',
        'helper' => __('Shortcode option: smallIcons=[0|1]', self::ld),
        'values' => $yesno
      ),

      'orientation' => array(
        'name' => __('Orientation', self::ld),
        'title' => __('In landscape, the toolbar is on the left; in portrait, the toolbar is on the top; if auto, the toolbar position will depend on the size of the whiteboard.', self::ld),
        'type' => 'select',
        'helper' => __('Shortcode option: orientation=[auto|portrait|landscape]', self::ld),
        'values' => $orientations
      ),

      'border' => array(
        'name' => __('Show Border', self::ld),
        'title' => __('Whether it should show a border around the whiteboard.', self::ld),
        'type' => 'select',
        'helper' => __('Shortcode option: border=[0|1]', self::ld),
        'values' => $yesno
      ),

      'shrinkCanvas' => array(
        'name' => __('Shrink Canvas', self::ld),
        'title' => __('if the whiteboard is resized to be smaller, whether to shrink the drawing area.', self::ld),
        'type' => 'select',
        'helper' => __('Shortcode option: shrinkCanvas=[0|1]', self::ld),
        'values' => $yesno
      ),

      'font' => array(
        'name' => __('Font', self::ld),
        'type' => 'text',
        'helper' => __('Shortcode option: font="..."', self::ld),
        'class' => 'regular-text'
      )
    );


    // load saved options
    $this->options = get_option(__class__.'_options', $this->default_options);

    // backend
    if (is_admin())
    {
      add_action('admin_menu', array($this, 'admin_menu'));
      add_action('admin_init', array($this, 'admin_init'));

      // add styles to the options page
      add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }
    else
    {
      // add scripts to the frontend
      add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));

      // shortcode [...]
      add_shortcode(self::shortcode, array($this, 'shortcode'));
    }

    register_activation_hook(__FILE__, array($this, 'activation'));
    register_uninstall_hook(__FILE__, array(__class__, 'uninstall'));
  }

  // hook on activation
  public function activation()
  {
    add_option(__class__.'_options', $this->default_options);
  }

  // hook on uninstallation
  static function uninstall()
  {
    delete_option(__class__.'_options');
  }

  // load language file, filename should be in format, eg. awwapp-sk_SK.mo
  public function plugins_loaded()
  {
    load_plugin_textdomain(self::ld, false, dirname(plugin_basename(__FILE__)).'/languages/');
  }

  // hook admin_init
  public function admin_init()
  {
    // action to save options
    if (isset($_POST['awwapp_save']) && $_POST['awwapp_save'])
    {
      if (!wp_verify_nonce($_POST['_wpnonce'], self::nonce))
        die(__('Whoops! There was a problem with the data you posted. Please go back and try again.', self::ld));

      $options = array();
      foreach($_POST as $key => $value)
        if (isset($this->options_def[$key]))
          $options[$key] = $value;

      update_option(__class__.'_options', $options);

      wp_redirect(admin_url('options-general.php?page='.__class__.'&message=saved'));
      exit;
    }

    // add dialog to tinyMCE editor
    add_action('admin_footer-post.php', array($this, 'admin_footer_edit'));
    add_action('admin_footer-post-new.php', array($this, 'admin_footer_edit'));

    // add mce plugin to insert shortcode
    if (get_user_option('rich_editing') == 'true')
    {
      add_filter('mce_external_plugins', array($this, 'mce_plugin'));
      add_filter('mce_buttons', array($this, 'mce_buttons'));
    }
  }

  // add mce button
  public function mce_buttons($buttons)
  {
    array_push($buttons, '|', 'awwapp_button');
    return $buttons;
  }

  // set mce plugin
  public function mce_plugin($plugin_array)
  {
    $plugin_array['awwapp_mce_plugin'] = $this->_url.'/admin/mce_plugin.js';
    return $plugin_array;
  }

  // add mce dialog into footer of posts page
  public function admin_footer_edit()
  {
    require_once $this->_path.'/admin/mce_dialog.php';
  }

  // extend settings menu with our options page
  public function admin_menu()
  {
    add_options_page(__('Shared Whiteboard', self::ld), __('Shared Whiteboard', self::ld), 'manage_options', __class__, array($this, 'options_page'));
    add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'filter_plugin_actions'), 10, 2);
  }

  // add styles to option page
  public function admin_enqueue_scripts($hook)
  {
    if ($hook == 'settings_page_AWWApp')
    {
      wp_enqueue_style(__class__, $this->_url.'/admin/options.css', array(), self::version, 'all');
    }
    else
    if ($hook == 'post.php' || $hook == 'post-new.php')
    {
      wp_enqueue_style(__class__, $this->_url.'/admin/mce_dialog.css', array(), self::version, 'all');
      wp_enqueue_script(__class__, $this->_url.'/admin/mce_dialog.js', array('jquery'), self::version, false);

      wp_localize_script(__class__, __class__, array(
        'version' => self::version,
        'shortcode' => self::shortcode,
        'text' => array(
          'title' => __('Shared Whiteboard', self::ld),
          'insert' => __('Insert', self::ld)
        )
      ));
    }
  }

  // add shortcut to the options page on the plugins list page
  public function filter_plugin_actions($l, $file)
  {
    $settings_link = '<a href="options-general.php?page='.__class__.'">'.__('Settings').'</a>';
    array_unshift($l, $settings_link);
    return $l;
  }

  // options page
  public function options_page()
  {
    $url = admin_url('options-general.php?page='.__class__);
    $message = isset($_GET['message'])?$_GET['message']:false;

    require_once $this->_path.'/admin/options.php';
  }

  // enqueue scripts on frontend
  public function wp_enqueue_scripts()
  {
    wp_enqueue_style(__class__, '//static.awwapp.com/plugin/1.0/aww.css', array(), self::version, 'all');
    wp_enqueue_script(__class__.'_helper', $this->_url.'/helper.js', array('jquery'), self::version, false);
    wp_enqueue_script(__class__, '//static.awwapp.com/plugin/1.0/aww.min.js', array('jquery'), self::version, false);
    wp_enqueue_script(__class__.'_now', 'http://awwapp.com:7000/nowjs/now.js', array('jquery'), self::version, false);

    // add global options
    wp_localize_script(__class__, __class__.'_global', $this->options);
  }

  // shortcode content
  public function shortcode($att, $content = '')
  {
    if (!is_array($att)) $att = array();
    $options = array();

    // check if option is valid
    foreach($att as $key => $value)
      if (isset($this->options_def[$key]))
        $options[$key] = $value;

    $this->counter++;
    $id = __class__.$this->counter;

    $width = isset($options['width'])?$options['width']:$this->getOption('width');
    $height = isset($options['height'])?$options['height']:$this->getOption('height');
    $border = isset($options['border'])?$options['border']:$this->getOption('border');

    return str_replace("\n", ' ', '
      <div id="'.$id.'" style="width: '.$width.'; height: '.$height.';'.($border?'border: #ddd 1px solid;':'').'"></div>
      <script type="text/javascript">
        jQuery(function($)
        {
          var options = $.extend({}, AWWApp_global, '.json_encode($options).');

          for(var i in options)
            if (!isNaN(options[i]))
              options[i] = parseInt(options[i]);

          $("#'.$id.'").awwCanvas(options);
        });
      </script>
    ');
  }

  // get option if is available, otherwise return default value
  protected function getOption($name)
  {
    if (!isset($this->options[$name]))
      return $this->default_options[$name];
    else
      return stripslashes($this->options[$name]);
  }

  // convert text to HTML entities - usable for forms
  protected function htmlent($text)
  {
    return htmlentities($text, ENT_COMPAT, 'UTF-8');
  }
}

new AWWApp();
