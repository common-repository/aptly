<?php

// phpinfo();
// echo "PhP version is: " . phpversion();
// header('Content-Type: application/json');

/**
 * @internal    never define functions inside callbacks.
 *              these functions could be run multiple times; this would result in a fatal error.
 */

 /**
  * @package Aptly
  * @version 1.3
  */
 /*
 Plugin Name: Aptly
 Plugin URI: http://www.goaptly.com/
 Description: Increase your site's engagement and stickiness by pesonalizing each user's experience with Aptly. Placed in the footer of your posts, Aptly will serve up more of the content your users like. Click on <a href="http://goaptly.com/wp-admin/options-general.php?page=aptly">Aptly Options</a> to adjust your settings.
 Author: Cerkl
 Version: 1.2
 Author URI: http://www.goaptly.com
 */

 function aptly_myplugin_activate() {
   update_option('awp_background_color', 'transparent');
   update_option('awp_section_color', '#000000');
   update_option('awp_headline_color', '#545454');
   update_option('aptly_image_width', 'sm');
   update_option('aptly_image_type', 'sq');
   update_option('aptly_image_height', 95);
   update_option('aptly_headline_font_size', 'sm');
   update_option('aptly_headline_line_height', 19);
   update_option('aptly_headline_bold', 'bolder');
   update_option('aptly_show_new', '1');
   update_option('aptly_show_trending', '1');
   update_option('aptly_show_recent', '1');
   update_option('aptly_opt_in', '0');
 }

 function aptly_cerkl_key($cerkl_key)
 {
   // print_r('Cerkl Key is: ' . $cerkl_key . '---');

   $body = array(
       'r' => 'wp_connect_cerkl',
       'cerkl_code' => $cerkl_key
   );

   $args = array(
       'body' => $body,
       'timeout' => '55',
       'redirection' => '5',
       'httpversion' => '1.0',
       'blocking' => true
   );

   $response = wp_remote_post( 'https://cerkl.com/library/awp.php', $args );
   // $response = wp_remote_post( 'http://staging-cerkl.gopagoda.io/library/awp.php', $args );
   $test_body = json_decode($response['body'], true);

   // print_r($response);
   // print_r($test_body);

   // receive server response
   // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

   // $response = curl_exec ($ch);
   // $status = curl_getinfo($ch);

  $json = $test_body; // json_decode($response, true);
  $orgId = $json['organization_id'];
  $hash = $json['hash'];

   if (($orgId != null) && ($hash != null))
   {
   update_option('aptly_orgId', sanitize_text_field($orgId));
   update_option('aptly_hash', sanitize_text_field($hash));
   return true;
 }
 else {
   return false;
 }
// rewrite our organization_id, and hash.
// if it is null, dont do anything.
 }

function aptly_store_user()
 {
   // echo ("blah man!                                    blah man!");
   $blog_url = get_bloginfo('url');
   $blog_email = get_bloginfo('admin_email');

   // $blog_pi = get_plugins(); // json_encode it.
   // $
   // we also need asynchronous hook. which will be a different function.

   if ( ! function_exists( 'get_plugins' ) ) {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
  }

  $all_plugins = get_plugins();

  $plugs = array();
  foreach($all_plugins as $plug)
  {
      array_push($plugs, $plug['Name']);
  }

    // $post_fields = "r=wp_awp_create&wp_email=" . $blog_email . "&wp_domain=" . $blog_url . "&installed_plugins=" . json_encode($plugs); // this will be localhost.

   // echo "post fields: " . $post_fields; // need to json encode too.
   // error_log($plugs, "blah");
   // $ch = curl_init();// init curl
   // curl_setopt($ch, CURLOPT_URL, "https://cerkl.com/library/awp.php");
   // "http://staging-cerkl.gopagoda.io/library/awp.php");
   // curl_setopt($ch, CURLOPT_POST, 1);
   // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

   // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
   // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

   // url_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);

   $body = array(
       'r' => 'wp_awp_create',
       'wp_domain' => $blog_url,
       'wp_email' => $blog_email,
       'installed_plugins' => json_encode($plugs)
   );

   $args = array(
       'body' => $body,
       'timeout' => '55',
       'redirection' => '5',
       'httpversion' => '1.0',
       'blocking' => true
   );

   $response = wp_remote_post( 'https://cerkl.com/library/awp.php', $args );
   // $response = wp_remote_post( 'http://staging-cerkl.gopagoda.io/library/awp.php', $args );
   $test_body = json_decode($response['body'], true);

   // print_r($response);
   // print_r ($test_body);

   // receive server response
   // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

   // $response = curl_exec ($ch);
   // $status = curl_getinfo($ch);

  $json = $test_body; // json_decode($response, true);
  $orgId = $json['organization_id'];
  $hash = $json['hash'];

   if ($orgId != null)
   {
   update_option('aptly_orgId', sanitize_text_field($orgId));
 }
 if ($hash != null)
 {
   update_option('aptly_hash', sanitize_text_field($hash));
 }
 /* if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$all_plugins = get_plugins();
print_r($all_plugins, true); */

   // curl_close ($ch);
};

register_activation_hook( __FILE__, 'aptly_myplugin_activate' );
add_action( 'widgets_init', function(){
     register_widget( 'Aptly_Personalization_Widget' );
});

// register_deactivation_hook( __FILE__, 'aptly_myplugin_deactivate' );

// add_action( 'widgets_init', function(){
    // register_widget( 'My_Useful_Widget' );
// });

/**
 * Register style sheets.
 */
function aptly_register_plugin_styles() {
  // echo plugins_url('css/spectrum.css', __FILE__);
  wp_register_style('bootstrapcss', plugin_dir_url(__FILE__) . 'css/bootstrap-iso.css');
  wp_register_style('spectrumcss', plugin_dir_url(__FILE__) . 'css/spectrum.css');
  // wp_register_style('cerkl2016css', plugin_dir_url(__FILE__) . 'css/main20161026.min.css');
  // wp_register_style('cerklstylecss', plugin_dir_url(__FILE__) . 'css/style.css');
  // wp_register_style('cerklawpcss', plugin_dir_url(__FILE__) . 'css/cerkl_awp.css');
  wp_register_style('cerklwidgetcss', plugin_dir_url(__FILE__) . 'css/widget_styles.css');
  // wp_register_style('cerklformscss', plugin_dir_url(__FILE__) . 'css/cerkl_forms.css');
  wp_register_script('cerklawpminjs', plugin_dir_url(__FILE__) . 'js/cerkl_awp_new.js');
  wp_register_script('spectrumjs', plugin_dir_url(__FILE__) . 'js/vendor/spectrum.js');
  // wp_register_script('jqueryjs', plugin_dir_url(__FILE__) . 'js/vendor/jquery.js');
  // wp_register_script('jquerymigratejs', plugin_dir_url(__FILE__) . 'js/vendor/jquery-migrate.js');

  // wp_enqueue_script( 'jqueryjs' );
  // wp_enqueue_script( 'jquerymigratejs' );
  wp_enqueue_script( 'spectrumjs' );
  wp_enqueue_script( 'cerklawpminjs' );
	wp_enqueue_style( 'spectrumcss' );
  // wp_enqueue_style( 'cerklawpcss' );

  wp_enqueue_style( 'bootstrapcss' );
  // wp_enqueue_style( 'cerkl2016css' );
  // wp_enqueue_style( 'cerklstylecss' );
  // wp_enqueue_style( 'cerklwidgetcss' );
  // wp_enqueue_style( 'cerklformscss' );
}

add_action( 'wp_enqueue_scripts', 'aptly_register_plugin_styles' );
add_action( 'admin_enqueue_scripts', 'aptly_register_plugin_styles' );

function aptly_register_plugin_scripts() {
}

/**
 * Adds Aptly_Personalization_Widget widget.
 */
class Aptly_Personalization_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Aptly_Personalization_Widget', // Base ID
			__('Aptly Personalization Widget', 'text_domain'), // Name
			array('description' => __( "Aptly serves up a personalized set of suggested posts to your site's users.", 'text_domain' ),) // Args
		);
    wp_enqueue_script('cerklawpminjs', plugins_url('js/cerkl_awp_new.js', __FILE__));
    // wp_enqueue_style('cerkl2016css', plugins_url('css/main20161026.min.css', __FILE__));
    // wp_enqueue_style('cerklstylecss', plugins_url('css/style.css', __FILE__));
    wp_enqueue_style('bootstrapcss', plugins_url('css/bootstrap-iso.css', __FILE__));
    wp_enqueue_style('cerklwidgetcss', plugins_url('css/widget_styles.css', __FILE__));
    wp_enqueue_style('cerklawpcss', plugins_url('css/cerkl_awp.css', __FILE__));
    wp_enqueue_style('cerklwidgetcss', plugins_url('css/widget_styles.css', __FILE__));
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
    // extract($args); // dont do extract()
    $before_widget = $args['before_widget'];
    $after_widget = $args['after_widget'];

     $bgc = $this->get_default_or_set_colors('bgc');
     $hdl = $this->get_default_or_set_colors('hdl');
     $sec = $this->get_default_or_set_colors('sec');
     $wide = $this->get_default_or_set_colors('wide');
     $iw = $this->get_default_or_set_colors('iw');
     $itype = $this->get_default_or_set_colors('itype');
     $ih = $this->get_default_or_set_colors('ih');
     $hdl_fz = $this->get_default_or_set_colors('hdl_fz');
     $hdl_bold = $this->get_default_or_set_colors('hdl_bold');
     $hdl_lh = $this->get_default_or_set_colors('hdl_lh');
     $sec_trnd = $this->get_default_or_set_colors('sec_trnd');
     $sec_new = $this->get_default_or_set_colors('sec_new');
     $sec_vis = $this->get_default_or_set_colors('sec_vis');
     $show = $this->get_default_or_set_colors('opt');
     $orgId = $this->get_orgId();

     $email = null;

    echo $before_widget;
    // echo '<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';

    // if ($orgId != null)
    if ($show == '1')
    {
      // echo '<p>Org ID is: </p>' . $orgId;
      echo '<div class="bootstrap-iso"><div id="cerkl_awp_container"></div></div>';
      echo '<script>';
      echo  "cerkl_awp({div: document.getElementById('cerkl_awp_container'), organization_id: '$orgId', image_size: '$iw', image_type: '$itype', headline: { font_size: '$hdl_fz', font_weight: '$hdl_bold'}, email: null, background: '$bgc', popular:{enabled: '$sec_trnd'}, newest:{enabled: '$sec_new'}, last:{enabled: '$sec_vis'}, defaults: {colors: {headline: '$hdl', section_color: '$sec' }}});";
      echo '</script>';
    }
    echo $after_widget;
	}

  public function get_orgId() {
    $options = get_option('aptly_orgId');

    $orgId = null;
    if ($options != null)
    {
      $orgId = $options; // ['orgId'];
    }
    return $orgId;
  }

  public function get_default_or_set_colors($color) {
    $wordp_theme_default_colors = get_option('theme_mods_cerkl');

    $background_color = get_option('awp_background_color');
    if ($background_color == null || $background_color == "") $background_color = 'transparent'; // '#' .  get_background_color();
    $section_title_color = get_option('awp_section_color');
    if ($section_title_color == null || $section_title_color == "") $section_title_color = '#000000'; // $wordp_theme_default_colors['main_text_color'];
    $headline_color = get_option('awp_headline_color');
    if ($headline_color == null || $headline_color == "") $headline_color = '#000000'; // $wordp_theme_default_colors['link_color'];
    $wide = get_option('wide'); // wide is not width of the sidebar...
    if ($wide == null || $wide == "") $wide = false;

    $iw = get_option('aptly_image_width');
    $itype = get_option('aptly_image_type');
    $ih = get_option('aptly_image_height');
    $hdl_fz = get_option('aptly_headline_font_size');
    $hdl_lh = get_option('aptly_headline_line_height');
    $hdl_bold = get_option('aptly_headline_bold');

    $sec_new = get_option('aptly_show_new');
    $sec_trnd = get_option('aptly_show_trending');
    $sec_vis = get_option('aptly_show_recent');

    $opt = get_option('aptly_opt_in');

  $result = "";

switch($color) {
case 'bgc':
$result = $background_color;
break;
case 'sec':
$result = $section_title_color;
break;
case 'hdl':
$result = $headline_color;
break;
case 'wide':
$result = $wide;
break;
case 'hdl_bold':
$result = $hdl_bold;
break;
case 'iw':
$result = $iw;
break;
case 'itype':
$result = $itype;
break;
case 'ih':
$result = $ih;
break;
case 'hdl_fz':
$result = $hdl_fz;
break;
case 'hdl_lh':
$result = $hdl_lh;
break;
case 'sec_new':
$result = $sec_new;
break;
case 'sec_trnd':
$result = $sec_trnd;
break;
case 'sec_vis':
$result = $sec_vis;
break;
case 'opt':
$result = $opt;
break;
default:
$result = "empty";
}

  return $result;
  }

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
	}

	public function update( $new_instance, $old_instance ) {

		$instance = array();
		// $instance['story_id'] = ( ! empty( $new_instance['story_id'] ) ) ? strip_tags( $new_instance['story_id'] ) : '';
		return $instance;
	}

} // class My_Widget


function aptly_settings_init() {
}

function aptly_get_orgId() {
  $options = get_option('aptly_orgId');

  $orgId = null;
  if ($options != null)
  {
    $orgId = $options; // ['orgId'];
  }
  return $orgId;
}

function aptly_get_default_or_set_colors($color) {
  // $options = get_option('cerkl_options');
    $wordp_theme_default_colors = get_option('theme_mods_cerkl');

    $background_color = get_option('awp_background_color');
    if ($background_color == null || $background_color == "") $background_color = 'transparent'; // '#' .  get_background_color();
    $section_title_color = get_option('awp_section_color');
    if ($section_title_color == null || $section_title_color == "") $section_title_color = '#000000'; // $wordp_theme_default_colors['main_text_color'];
    $headline_color = get_option('awp_headline_color');
    if ($headline_color == null || $headline_color == "") $headline_color = '#000000'; // $wordp_theme_default_colors['link_color'];
    $wide = get_option('wide'); // wide is not width of the sidebar...
    if ($wide == null || $wide == "") $wide = false;

    $iw = get_option('aptly_image_width');
    $itype = get_option('aptly_image_type');
    $ih = get_option('aptly_image_height');
    $hdl_fz = get_option('aptly_headline_font_size');
    $hdl_lh = get_option('aptly_headline_line_height');
    $hdl_bold = get_option('aptly_headline_bold');

    $sec_new = get_option('aptly_show_new');
    $sec_trnd = get_option('aptly_show_trending');
    $sec_vis = get_option('aptly_show_recent');
    $opt = get_option('aptly_opt_in');

  $result = "";


switch($color) {
case 'bgc':
$result = $background_color;
break;
case 'sec':
$result = $section_title_color;
break;
case 'hdl':
$result = $headline_color;
break;
case 'wide':
$result = $wide;
break;
case 'hdl_bold':
$result = $hdl_bold;
break;
case 'iw':
$result = $iw;
break;
case 'itype':
$result = $itype;
break;
case 'ih':
$result = $ih;
break;
case 'hdl_fz':
$result = $hdl_fz;
break;
case 'hdl_lh':
$result = $hdl_lh;
break;
case 'sec_new':
$result = $sec_new;
break;
case 'sec_trnd':
$result = $sec_trnd;
break;
case 'sec_vis':
$result = $sec_vis;
break;
case 'opt':
$result = $opt;
break;

default:
$result = "empty";
}

  return $result;

  // return [$background_color, $section_title_color, $headline_color, $wide];
}

function aptly_style_display($content)
{
 if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$all_plugins = get_plugins();

$plugs = array();
foreach($all_plugins as $plug)
{
    array_push($plugs, $plug['Name']);

}

 // echo "post fields: " . $post_fields; // need to json encode too.
 // echo print_r($plugs);

 // echo print_r($all_plugins, true);

  wp_enqueue_script('cerklawpminjs', plugins_url('js/cerkl_awp_new.js', __FILE__));
  // wp_enqueue_style('cerkl2016css', plugins_url('css/main20161026.min.css', __FILE__));
  // wp_enqueue_style('cerklstylecss', plugins_url('css/style.css', __FILE__));
  // wp_enqueue_script('awporgsettingsjs', plugins_url('js/awp_org_settings.js', __FILE__));
  wp_enqueue_style('cerklawpcss', plugins_url('css/cerkl_awp.css', __FILE__));
  wp_enqueue_style('cerklwidgetcss', plugins_url('css/widget_styles.css', __FILE__));

  $bgc = aptly_get_default_or_set_colors('bgc');
  $hdl = aptly_get_default_or_set_colors('hdl');
  $sec = aptly_get_default_or_set_colors('sec');
  $wide = aptly_get_default_or_set_colors('wide');
  $iw = aptly_get_default_or_set_colors('iw');
  $itype = aptly_get_default_or_set_colors('itype');
  $ih = aptly_get_default_or_set_colors('ih');
  $hdl_fz = aptly_get_default_or_set_colors('hdl_fz');
  $hdl_bold = aptly_get_default_or_set_colors('hdl_bold');
  $hdl_lh = aptly_get_default_or_set_colors('hdl_lh');
  $sec_trnd = aptly_get_default_or_set_colors('sec_trnd');
  $sec_new = aptly_get_default_or_set_colors('sec_new');
  $sec_vis = aptly_get_default_or_set_colors('sec_vis');
  $orgId = aptly_get_orgId(); // 1;
  $show = aptly_get_default_or_set_colors('opt');

  $email = null;

  $stub = "";
  if (!is_active_widget(false, false, 'aptly_personalization_widget', true ))
  {
    if (is_single() && $show == '1')
    {
    // $stub .= '<link rel="stylesheet" href="' . plugins_url("css/bootstrap.min.css", __FILE__) . '">';
    // $stub .= '<link rel="stylesheet" href="' . plugins_url("css/style.css", __FILE__) . '">';
    $stub .= '<div class="bootstrap-iso"><div id="cerkl_awp_container"></div></div>';
    $stub .= '<script>';
    $stub .= "cerkl_awp({div: document.getElementById('cerkl_awp_container'), organization_id: '$orgId', image_size: '$iw', image_type: '$itype', headline: { font_size: '$hdl_fz', font_weight: '$hdl_bold'}, email: null, background: '$bgc', popular:{enabled: '$sec_trnd'}, newest:{enabled: '$sec_new'}, last:{enabled: '$sec_vis'}, defaults: {colors: {headline: '$hdl', section_color: '$sec' }}});";
    $stub .= '</script>';
  }
}
  return $content . $stub;
}

add_filter( 'the_content', 'aptly_style_display' );

function aptly_plugin_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=aptly">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'aptly_plugin_settings_link' );

function aptly_settings_page() {
    // add top level menu page
    add_menu_page(
        'Aptly',
        'Aptly Options',
        'manage_options',
        'aptly',
        'aptly_settings_page_html',
        'dashicons-editor-textcolor'
    );
}

add_action('admin_menu', 'aptly_settings_page'); // settings_options_page

function aptly_settings_page_html() {

  // $background_color = get_option('awp_background_color');
  // $section_title_color = get_option('awp_section_color');
  // $headline_color = get_option('awp_headline_color');
  /* wp_enqueue_style('spectrumcss', plugins_url('css/spectrum.css', __FILE__));
  wp_enqueue_script('spectrumjs', plugins_url('js/vendor/spectrum.js', __FILE__));
  wp_enqueue_script('awporgsettingsjs', plugins_url('js/awp_org_settings.js', __FILE__));
  wp_enqueue_style('cerklawpcss', plugins_url('css/cerkl_awp.min.css', __FILE__));
  wp_enqueue_style('cerklformscss', plugins_url('css/cerkl_forms.css', __FILE__)); */
      if (!current_user_can('manage_options')) {
        echo "user is not allowed to do this operation";
          return;
      }

      if (isset($_POST['aptly_cerkl_key'])) {
        if (aptly_cerkl_key(sanitize_text_field($_POST['aptly_cerkl_key'])))
        {
          update_option('aptly_cerkl_key', sanitize_text_field($_POST['aptly_cerkl_key']));
          $aptly_key_value = sanitize_text_field($_POST['aptly_cerkl_key']);
          // what is the value of this variable?
          // somewhere else, logic for hiding the tab.
        }
        else {
          $aptly_key_value = 'invalid key';
        }
      }

      if (isset($_POST['aptly_opt_in'])) {
          update_option('aptly_opt_in', sanitize_text_field($_POST['aptly_opt_in']));
          $opt_in_value = sanitize_text_field($_POST['aptly_opt_in']);
      }
      else {
        if (isset($_POST['optin_submitted']))
        {
        update_option('aptly_opt_in', '1');
        $opt_in_value = '1';
        aptly_store_user();
      }
      else {
        $opt_in_value = get_option('aptly_opt_in');
      }
      }

      if (isset($_POST['awp_background_color'])) {
          update_option('awp_background_color', sanitize_text_field($_POST['awp_background_color']));
          $bgr_clr_value = sanitize_text_field($_POST['awp_background_color']);
      }
      else {
        $bgr_clr_value = get_option('awp_background_color');
      }

      if (isset($_POST['awp_section_color'])) {
          update_option('awp_section_color', sanitize_text_field($_POST['awp_section_color']));
          $sec_clr_value = sanitize_text_field($_POST['awp_section_color']);
      }
      else {
        $sec_clr_value = get_option('awp_section_color');
      }

      if (isset($_POST['awp_headline_color'])) {
          update_option('awp_headline_color', sanitize_text_field($_POST['awp_headline_color']));
          $hdl_clr_value = sanitize_text_field($_POST['awp_headline_color']);
      }
      else {
        $hdl_clr_value = get_option('hdl_color');
      }

      if (isset($_POST['aptly_image_width'])) {
          update_option('aptly_image_width', sanitize_text_field($_POST['aptly_image_width']));
          $iw_value = sanitize_text_field($_POST['aptly_image_width']);
      }
      else {
        $iw_value = get_option('aptly_image_width');
      }
      if (isset($_POST['aptly_image_type'])) {
          update_option('aptly_image_type', sanitize_text_field($_POST['aptly_image_type']));
          $itype_value = sanitize_text_field($_POST['aptly_image_type']);
      }
      else {
        $itype_value = get_option('aptly_image_type');
      }
      if (isset($_POST['aptly_image_height'])) {
          update_option('aptly_image_height', sanitize_text_field($_POST['aptly_image_height']));
          $ih_value = sanitize_text_field($_POST['aptly_image_height']);
      }
      else {
        $ih_value = get_option('aptly_image_height');
      }
      if (isset($_POST['aptly_headline_font_size'])) {
          update_option('aptly_headline_font_size', sanitize_text_field($_POST['aptly_headline_font_size']));
          $hdl_fz_value = sanitize_text_field($_POST['aptly_headline_font_size']);
      }
      else {
        $hdl_fz_value = get_option('aptly_headline_font_size');
      }
      if (isset($_POST['aptly_headline_bold'])) {
          update_option('aptly_headline_bold', sanitize_text_field($_POST['aptly_headline_bold']));
          $hdl_bold_value = sanitize_text_field($_POST['aptly_headline_bold']);
      }
      else {
          $hdl_bold_value = get_option('aptly_headline_bold');
      }
      if (isset($_POST['aptly_headline_line_height'])) {
          update_option('aptly_headline_line_height', sanitize_text_field($_POST['aptly_headline_line_height']));
          $hdl_lh_value = sanitize_text_field($_POST['aptly_headline_line_height']);
      }
      else {
        $hdl_lh_value = get_option('aptly_headline_line_height');
      }
      if (isset($_POST['awp_background_color'])) {
          update_option('awp_background_color', sanitize_text_field($_POST['awp_background_color']));
          $bgr_clr_value = sanitize_text_field($_POST['awp_background_color']);
      }
      else {
        $bgr_clr_value = get_option('awp_background_color');
      }

      if (isset($_POST['awp_section_color'])) {
          update_option('awp_section_color', sanitize_text_field($_POST['awp_section_color']));
          $sec_clr_value = sanitize_text_field($_POST['awp_section_color']);
      }
      else {
        $sec_clr_value = get_option('awp_section_color');
      }

      if (isset($_POST['awp_headline_color'])) {
          update_option('awp_headline_color', sanitize_text_field($_POST['awp_headline_color']));
          // sanitize might strip the # character. Need to be careful.
          $hdl_clr_value = sanitize_text_field($_POST['awp_headline_color']);
      }
      else {
        $hdl_clr_value = get_option('awp_headline_color');
      }
      if (isset($_POST['awp_switch_color'])) {
          update_option('awp_switch_color', sanitize_text_field($_POST['awp_switch_color']));
          $bgc_selection_value = sanitize_text_field($_POST['awp_switch_color']);
      }
      else {
        update_option('awp_switch_color', 'dontshow'); //validate here
        $bgc_selection_value = 'dontshow';
      }
      if (isset($_POST['aptly_show_trending'])) {
          update_option('aptly_show_trending', sanitize_text_field($_POST['aptly_show_trending']));
          $sec_trnd_value = sanitize_text_field($_POST['aptly_show_trending']);
          // check here
      }
      else {
        if (isset($_POST['submitted'])) // change 'submitted' also.
        {
        update_option('aptly_show_trending', '0');
        $sec_trnd_value = '0';
      }
      else {
        $sec_trnd_value = get_option('aptly_show_trending');
      }
      }
      if (isset($_POST['aptly_show_new'])) {
          update_option('aptly_show_new', sanitize_text_field($_POST['aptly_show_new']));
          $sec_new_value = sanitize_text_field($_POST['aptly_show_new']);
      }
      else {
        if (isset($_POST['submitted']))
        {
        update_option('aptly_show_new', '0'); // may be validate here
        $sec_new_value = '0'; // if aptly_show_new is not a form variable, then it will set it to one.
        // is this the logic we want. Also, how will IS_SET work when the value is null. Will the
        // variable be in the request variable?
      }
      else {
        $sec_new_value = get_option('aptly_show_new');
      }
      }
      if (isset($_POST['aptly_show_recent'])) {
          update_option('aptly_show_recent', sanitize_text_field($_POST['aptly_show_recent']));
          $sec_recent_value = sanitize_text_field($_POST['aptly_show_recent']);
      }
      else {
        if (isset($_POST['submitted']))
        {
        update_option('aptly_show_recent', '0');
        $sec_recent_value = '0'; // will not choosing it, save zero? is it int or string?
      }
      else {
        $sec_recent_value = get_option('aptly_show_recent');
      }
      }
      ?>

      <?php
      wp_enqueue_style('bootstrapcss', plugins_url('css/bootstrap-iso.css', __FILE__));
      wp_enqueue_style('spectrumcss', plugins_url('css/spectrum.css', __FILE__));
      wp_enqueue_script('spectrumjs', plugins_url('js/vendor/spectrum.js', __FILE__));
      // wp_enqueue_style('cerklformscss', plugins_url('css/cerkl_forms.css', __FILE__));
      // wp_enqueue_style('cerkl2016css', plugins_url('css/main20161026.min.css', __FILE__));
      // wp_enqueue_style('cerklstylecss', plugins_url('css/style.css', __FILE__));
      wp_enqueue_style('toggleswitch', plugins_url('css/toggle-switch.css', __FILE__));
      wp_enqueue_script('awporgsettingsjs', plugins_url('js/awp_org_settings.staging.js', __FILE__));
      wp_enqueue_script('cerklspectrumjs', plugins_url('js/vendor/cerkl-spectrum.js', __FILE__));
      ?>
      <!-- ?php if (1 == 1) : ? -->
        <div class="wrap">
          <div class="bootstrap-iso">
            <h2 style="text-align: left; text-transform: none; font-weight: bold;">Aptly Plugin Setup Page</h2>
            <!-- ?php settings_errors(); ? -->
            <?php
                    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'cerkl_options';
            ?>

            <div style="text-transform: none; font-family: 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif";">
            <h2 class="nav-tab-wrapper" style="text-transform: none; font-weight: bold;">
              <?php if (get_option('aptly_opt_in') == '0') : ?>
              <a href="?page=aptly&amp;tab=cerkl_optin" class="nav-tab <?php echo $active_tab == 'cerkl_optin' ? 'nav-tab-active' : ''; ?>"><?php _e('Let\'s get started...', 'aptly'); ?></a>
            <?php endif; ?>
              <?php if (get_option('aptly_opt_in') == '1') : ?>
              <a href="?page=aptly&amp;tab=cerkl_options" class="nav-tab <?php echo $active_tab == 'cerkl_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Options', 'aptly'); ?></a>
            <?php endif; ?>
              <?php if (get_option('aptly_opt_in') == '1' && get_option('aptly_cerkl_key') == null) : ?>
              <a href="?page=aptly&amp;tab=cerkl_key" class="nav-tab <?php echo $active_tab == 'cerkl_key' ? 'nav-tab-active' : ''; ?>"><?php _e('Cerkl Key', 'aptly'); ?></a>
              <?php endif; ?>
            </h2>

            <!-- div -->
            <!-- form method="POST" -->
            <!-- ?php if ($active_tab == 'cerkl_optin' && get_option('aptly_opt_in') == '0') : ? -->
            <?php if (get_option('aptly_opt_in') == '0') : ?>
            <div style="margin: 25px 0;"></div>

              <div style="padding-left: 20px; padding-top: 20px; padding-bottom: 20px; width: 862px; background-color: #ececec;">

                <div class="row">
                <div >
                  <div class="col-md-4" style="width: 20.3%;">
                <img style="float: left;" height="150" width="150" src="<?php echo plugins_url('img/mr_aptly.png', __FILE__);?>"/>
                </div>
                <div class="col-md-8" style="padding-left: 0;">
                  <p>Hi, I'm Nick and we're about to become best friends.</p>
                  <p>I am going to help you engage your audience like never before! I automatically learn about the interests of every person that visits your site and tailor contents to them based on their interests.</p>
                  <p>Cool, I know.</p>
                  <p>You will also receive free email reports from me showing how personalizing visitor experiences is increasing engagement. For me to work, I am going to grab your domain and your email address.</p>
                  <p>Ready for awesomeness?</p>
                </div>
                </div>
              </div>
              </div>

              <div style="margin: 20px 0;"></div>
              <form method="post">

                <input type='hidden' name='optin_submitted' value='1'>
                <div style="margin: 20px 0;"/></div>
                <input type="submit" value="Let's Go!" class="btn btn-primary btn-lg" style="background-color: #495f8c; color: white">

              </form>
            </div>
          </div>
        </div>
            <?php endif; ?>

              <?php if ( ($active_tab == 'cerkl_options' && get_option('aptly_opt_in') == '1') || ($active_tab == 'cerkl_key' && get_option('aptly_opt_in') == '1' && get_option('aptly_cerkl_key') != null)    ) : ?>
<!-- ?php if (get_option('aptly_opt_in') == '1') : ? -->
                <div>
                  <div class="bootstrap-iso">

                    <div class="row">
                        <div class="col-lg-8 col-md-12">
                          <div class="panel panel-tile br-a br-grey" >

                            <div class="panel-body">
                                <label class="label-heading"><h2 style="text-transform: none; font-weight: bold;">Configure the settings for your Aptly Plugin</h2></label>
                                <div class="row col-sh">
                                  <form method="POST">
                                    <div class="awp_setting" style="text-align: left;" >
                                    <div style="padding-left: 20px; padding-top: 20px; padding-bottom: 20px; background-color: #ffffff;">

                                      <div class="row">
                                      <div >
                                        <div class="col-md-4" style="width: 20.3%;">
                                      <img style="float: left;" height="150" width="150" src="<?php echo plugins_url('img/mr_aptly.png', __FILE__);?>"/>
                                    </div>
                                    <div class="col-md-8" style="padding-left: 0;">
                                      <h4 style="float: left; font-size: 22px;">Welcome to Aptly, I'm Nick. Here is where you can customize Aptly display and settings.</h4>
                                      <h4 style="float: left; font-size: 22px;">You can also use Aptly in any of the widget areas of your site. To turn this option on go to Appearance > Widgets and look for the Aptly Personalization Widget.</h4>
                                    </div>
                                    <!-- div class="col-md-1" -->
                                    </div>
                                  </div>

                                  </div>
                                  </div>
                                  <!-- /div -->
                                  <div style="margin: 20px 0;"></div>
                                    <label font-weight: bold;><h2 style="text-transform: none; font-weight: bold;">Colors</h2></label>
                                    <div class="awp_setting">
                                      <div style="padding-left: 20px; padding-top: 20px; padding-bottom: 20px; background-color: #ececec;">
                                            <div name="testo" style="display: table;">
                                            <p style="display: table-row">
                                            <span for="awp_background_color" title="" style="display: table-cell; align-items: right; width: 145px; font-weight: bold; justify-content: right;">Background Color</span>
                                            <input type='text' style="display: none;" id="awp_background_color" name="awp_background_color" value="<?php echo $bgr_clr_value ?>"/>
                                            <label style="display: table-cell; font-weight: normal !important; padding-left: 8px;">This is the background color of your whole Aptly widget. Most people choose a light color to make the text easier to read.</label>
                                          </p>
                                          <p style="display: table-row">
                                            <span for="awp_section_color" title="" style="display: table-cell; align-items: right; font-weight: bold; justify-content: right; width: 114px;">Section Title</span>
                                            <input type='text' id="awp_section_color" name="awp_section_color" style="display: none;" value="<?php echo $sec_clr_value?>"/>
                                            <label style="display: table-cell; font-weight: normal !important; padding-left: 8px;">Here you can change the color of your widget title. Think about using a common color on your site to help it look consistent.</label>
                                            </p>
                                          <p style="display: table-row">
                                            <span for="awp_headline_color" title="" style="display: table-cell; align-items: right; font-weight: bold; justify-content: right; width: 114px;">Headline</span>
                                            <input type='text' id="awp_headline_color" name="awp_headline_color" style="display: none;" value="<?php echo $hdl_clr_value?>"/>
                                            <label style="display: table-cell; font-weight: normal !important; padding-left: 8px;">Pick a color, any color. Well, not any, just one you would like for your Aptly headlines.</label>
                                          </p>
                                        </div>
                                    </div>
                                    </div>
                                    <div style="margin: 20px 0;"></div>
                                    <label class="label-heading" title=""><h2 style="text-transform: none; font-weight: bold;">Story Cover Photo Size</h2></label>
                                    <div class="awp_setting" style="text-align: left;" >
                                      <div style="padding-left: 20px; padding-top: 20px; padding-bottom: 20px; background-color: #ececec;">
                                        <div style="display: table">
                                          <p style="display: table-row">
                                        <label style="display: table-cell;">Image Size </label>
                                        <select id="slt_image_size" name="aptly_image_width" style="width: 90px;">
                                          <option value="sm" <?php if($iw_value == 'sm'): ?> selected="selected"<?php endif; ?>>Small</option>
                                          <option value="md" <?php if($iw_value == 'md'): ?> selected="selected"<?php endif; ?>>Medium</option>
                                          <option value="lg" <?php if($iw_value == 'lg'): ?> selected="selected"<?php endif; ?>>Large</option>
                                        </select>
                                        <label style="display: table-cell; font-weight: normal !important; padding-left: 8px;">Choose the image size you'd like to show with your Aptly stories.</label>

                                  </p>
                                      <p style="display: table-row; height: 7px;"></p>
                                        <p style="display: table-row">
                                        <label style="display: table-cell;">Image Type&nbsp;&nbsp;&nbsp;</label>
                                        <select id="slt_image_type" name="aptly_image_type" style="width: 90px;">
                                          <option value="sq" <?php if($itype_value == 'sq'): ?> selected="selected"<?php endif; ?>>Square</option>
                                          <option value="rect" <?php if($itype_value == 'rect'): ?> selected="selected"<?php endif; ?>>Rectangle</option>
                                        </select>
                                        <label style="display: table-cell; font-weight: normal !important; padding-left: 8px;">Square and rectangular sizes are suggested.</label>
                                        </p>
                                      </div>
                                    </div>
                                    </div>
                                    <div style="margin: 20px 0;"></div>

                                    <label class="label-heading"><h2 style="text-transform: none; font-weight: bold;">Headline</h2></label>
                                    <div class="awp_setting" style="text-align: left;" >
                                      <div style="padding-left: 20px; padding-top: 20px; padding-bottom: 20px; background-color: #ececec;">

                                        <div style="display: table">
                                          <p style="display: table-row">
                                      <!-- div class="form-group" -->
                                        <label style="display: table-cell;" title="">Font Size &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <select id="slt_font_size" name="aptly_headline_font_size" style="width: 80px" value="<?php echo $hdl_fz_value; ?>">
                                          <option value="sm" <?php if($hdl_fz_value == 'sm'): ?> selected="selected"<?php endif; ?>>Smaller</option>
                                          <option value="nm" <?php if($hdl_fz_value == 'nm'): ?> selected="selected"<?php endif; ?>>Normal</option>
                                          <option value="lg" <?php if($hdl_fz_value == 'lg'): ?> selected="selected"<?php endif; ?>>Larger</option>
                                        </select>
                                        <label style="display: table-cell; font-weight: normal !important; padding-left: 8px; padding-right: 4px;">Choose the size you'd like your Aptly headlines to be. This should be slightly larger than your site's body font.</label>
                                    </p>
                                    <p style="display: table-row; height: 7px;"></p>
                                    <p style="display: table-row">
                                      <!-- div class="form-group" -->
                                        <label style="display: table-cell;" title="">Font Weight&nbsp;&nbsp;&nbsp;</label>
                                        <select id="slt_font_weight" name="aptly_headline_bold" style="width: 80px;" value="<?php echo $hdl_bold_value; ?>">
                                          <option value="lighter" <?php if($hdl_bold_value == 'lighter'): ?> selected="selected"<?php endif; ?>>Light</option>
                                          <option value="normal" <?php if($hdl_bold_value == 'normal'): ?> selected="selected"<?php endif; ?>>Normal</option>
                                          <option value="bolder" <?php if($hdl_bold_value == 'bolder'): ?> selected="selected"<?php endif; ?>>Bold</option>
                                        </select>
                                        <label style="display: table-cell; font-weight: normal !important; padding-left: 8px;">It's your choice! Play around, it's easy to change it back.</label>
                                    </p>
                                    </div>
                                  </div>
                                </div>
                                <div style="margin: 20px 0;"/></div>
                                <label class="label-heading"><h2 style="text-transform: none; font-weight: bold;">Sections</h2></label>
                                <div class="awp_setting" style="text-align: left;" >
                                  <div style="padding-left: 20px; padding-top: 20px; padding-bottom: 20px; background-color: #ececec;">

                                    <div style="display: table">
                                    <p style="display: table-row">
                                    <label style="display: table-cell;">Show Trending stories? </label>
                                    <input type="checkbox" name="aptly_show_trending" style="" <?php if($sec_trnd_value == '1'): ?> checked="checked"<?php endif; ?>   value="1">

                                </p>
                                <!-- p style="display: table-row; height: 7px;"></p -->
                                <p style="display: table-row">
                                    <label style="display: table-cell;">Show Newest stories?&nbsp;&nbsp;&nbsp;</label>
                                    <input type="checkbox" name="aptly_show_new" style="" <?php if($sec_new_value == '1'): ?> checked="checked"<?php endif; ?> value="1">

                                </p>
                                <p style="display: table-row">
                                    <label style="display: table-cell;">Show Recently Visited stories?&nbsp;&nbsp;&nbsp;</label>
                                    <input type="checkbox" name="aptly_show_recent" style="" <?php if($sec_recent_value == '1'): ?> checked="checked"<?php endif; ?> value="1">
                                </p>
                                </div>
                              </div>
                            </div>

                            <input type='hidden' name='submitted' value='yes'>
                            <div style="margin: 20px 0;"/></div>
                            <input type="submit" value="Save Settings" class="btn btn-primary btn-lg" style="background-color: #495f8c; color: white">

                                  </form>
                                  <div style="margin: 20px 0;"/></div>
                                  <div class="awp_setting" style="text-align: left;" >
                                    <div style="padding-left: 20px; padding-top: 20px; padding-bottom: 20px; background-color: #ececec;">
                                    <div class="form-group">
                                      <h3 style="text-align: left; text-transform: none; font-weight: bold;">Having trouble? No worries. Contact our <a href="http://www.goaptly.com/support">support team.</a> </h3>
                                    </div>
                                  </div>
                                  </div>

                          </div>
                            </div>
                          </div>
                          </div>
                        </div>
                      </div>
                    </div>
      <!-- ?php endif; ? -->

    </div>
  </div>

      <style type="text/css">
              .awp_settings img.layout_selected{
                border:2px solid #39bfc9;
              }
              .awp_color_section{
                margin-bottom: 10px;
              }
              .awp_layout_select img, .awp_img_select img{
                margin-bottom: 10px;
              }
            </style>

            <input type='hidden' id='bgr_color' name='bgr_color' value='<?php echo  $background_color ?>'>
            <input type='hidden' id='sec_color' name='sec_color' value='<?php echo  $section_title_color ?>'>
            <input type='hidden' id='hdl_color' name='hdl_color' value='<?php echo  $headline_color ?>'>
      <?php endif; ?>

      <?php if ($active_tab == 'cerkl_key' && get_option('aptly_opt_in') == '1' && get_option('aptly_cerkl_key') == null) : ?>
        <div style="margin: 25px 0;"></div>

          <div style="padding-left: 20px; padding-top: 20px; padding-bottom: 20px; width: 862px; background-color: #ececec;">

            <div class="row">
            <div >
              <div class="col-md-4" style="width: 20.3%;">
            <img style="float: left;" height="150" width="150" src="<?php echo plugins_url('img/mr_aptly.png', __FILE__);?>"/>
            </div>
            <div class="col-md-8" style="padding-left: 0;">
              <p>If you already have a Cerkl account, please enter the Cerkl Code below. This will help connecting your Cerkl engagement score to your Aptly plugin.</p>
              <p>If you dont have a Cerkl account or you are not ready to connect it to the plugin yet, please leave it blank.</p>
              <p>Note: The engagement score, insights, from the stories on your Wordpress blog will be considered as test,  until you provide the Cerkl Code for your account.</p>
            </div>
            </div>
          </div>
          </div>

          <div style="margin: 20px 0;"></div>

          <div style="padding-left: 20px; padding-top: 20px; padding-bottom: 20px; width: 862px; background-color: #ececec;">
            <p>Enter the Cerkl Code: </p>
            <form method="post">

              <input type="text" name="aptly_cerkl_key">
              <div style="margin: 20px 0;"/></div>
              <input type="submit" value="Let's Go!" class="btn btn-primary btn-lg" style="background-color: #495f8c; color: white">

            </form>
          </div>


          <?php if ($aptly_key_value == 'invalid key') : ?>
          <div style="margin: 20px 0;"></div>
          <p style="color: red;">The Cerkl Key value that you entered is not correct. We will continue to use your existing Cerkl</p>
        <?php endif; ?>
        </div>
      </div>
    </div>
      <?php endif; ?>

      <?php
    }
