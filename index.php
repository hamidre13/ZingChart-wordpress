<?php
/* Plugin Name: Zing Charts
 * Plugin URI:http://www.zingchart.com/
 * Description: This plugin adds Zing charts to wordpress.
 * Author: Hamid Tavakoli
 * Version:1.0
 * Author URI: http://pint.com
 * Lisense :???
 */
/*
TO DOs:
* Internationalization Implimention 
* Get the Image for the icon!! Function zing_custompost
* Check user credentials before saving for zing_save
* Create a database tabel for charts and put defult values in them.
* Creat a editor button for inserting shortcodes
* Fixing the abspath issue
* Make sure the plugin loads only once
*/

/*if (!'defined( 'ABSPATH' )') {
  header( 'HTTP/1.0 404 Not Found', true, 404 );
  exit;
}*/
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);


define('ZING_PLUGIN_URL',plugin_dir_url(__FILE__));
define('ZING_PLUGIN_PATH',plugin_dir_path(__FILE__));
define('ZING_NOUNCE',plugin_dir_path(__FILE__));
require_once(ZING_PLUGIN_PATH.'Zing_lib.php');
require_once(ZING_PLUGIN_PATH.'Zing_help.php');
//require_once(ZING_PLUGIN_PATH.'zing_edit.php');

function zing_activate() {
  //Just a place holder
}
register_activation_hook(__FILE__,"zing_activate");
function zing_deactivate() {
  //Just another place holder
}
register_deactivation_hook(__FILE__,"zing_deactivate");

function zing_admin_menu() {
  add_options_page('Zing Charts setting','Zing Chart','manage_options','Zing-chart','zing_admin');

}
add_action('admin_menu','zing_admin_menu');

function zing_loadLib(){
  wp_enqueue_script('zing_chart','http://cdn.zingchart.com/zingchart.min.js',array(),'2.1.0',TRUE);
}
add_action('wp_enqueue_scripts','zing_loadLib');

/*
 * Register custom post type
 */
function Zing_custompost() {
  wp_enqueue_script('jquery-ui','http://code.jquery.com/ui/1.11.4/jquery-ui.min.js');
   wp_enqueue_script('translate', ZING_PLUGIN_URL.'translate.js');
  wp_enqueue_script('Zing_chart','http://cdn.zingchart.com/zingchart.min.js');
  wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
  register_post_type('zing_chart',
    array( 
    'labels'              => array( 
    'name'                => _x( 'Zing Charts', 'zingchart' ),
    'singular_name'       => _x( 'Zing Chart', 'zingchart' ),
    'add_new'             => _x( 'Add New', 'zingchart' ),
    'add_new_item'        => _x( 'Add New Chart', 'zingchart' ),
    'edit_item'           => _x( 'Edit Chart', 'zingchart' ),
    'new_item'            => _x( 'New Chart', 'zingchart' ),
    'view_item'           => _x( 'View Chart', 'zingchart' ),
    'search_items'        => _x( 'Search For Charts', 'zingchart' ),
    'not_found'           => _x( 'No charts found', 'zingchart' ),
    'not_found_in_trash'  => _x( 'No charts found in Trash', 'zingchart' ),
    'menu_name'           => _x( 'Charts', 'zingchart' ),
    ),
    //TO DO: I have to upload appropriate image for the icon
    'menu_icon'           => '/images/icon_charts2.png',
    'hierarchical'        => false,
    'supports'            => array( 'title' ),
    'taxonomies'          => array( 'category' ),
    'public'              => false,
    'show_ui'             => true,
    'menu_position'       => 20,
    'show_in_nav_menus'   => false,
    'publicly_queryable'  => false,
    'exclude_from_search' => true,
    'has_archive'         => false,
    'query_var'           => false,
    'can_export'          => true,
    'capability_type'     => 'post'
    )
  );
}
add_action('init','Zing_custompost');
function amcharts_meta_boxes () {
  add_meta_box('zing_designer',__('Chart Designer','zingchart'),'zing_designer','zing_chart','high');
  add_meta_box('zing_html',__( 'HTML', 'zingchart' ),'zing_html','zing_chart');
  add_meta_box('zing_javascript',__('Java Script','zingchart'),'zing_javascript','zing_chart');
  
}
add_action( 'add_meta_boxes', 'amcharts_meta_boxes' );
function zing_designer() {

  ?> 
  <script>
  jQuery(document).ready(function($) {
    $('#tabs').tabs();
    $('#accordion').accordion();
    $( "#slider" ).slider();
    
  });
  </script> 
  <style type="text/css">
  #accordion{
    float: left;
    width: 30%;
  }


  </style>


  <script>



  </script>

  Chart type:
  <select onchange="chartRouter()" id="whichChart">
    <option value="area">Area</option>
    <option value="bar">Bar</option>
    <option value="line">line</line>
  </select>

  <div style="clear:both"></div>

  <div id="accordion">
    <h3>General</h3>
    <div>
      <div id="tabs">
        <ul>  
          <li><a href="#canvas">Canvas</a></li>
          <li><a href="#chart">Chart</a></li>
        </ul>
        <div id="canvas">
          Animate: <input type="checkbox"><br>
          <div class="yesAimate">
            Effect: 
            <select>
              <option >Defult</option>
              <option >Strech Vertical</option>
              <option >Strech Horizontal</option>
              <option >Slide Down</option>
              <option >SLide up</option>
              <option >SLide left</option>
              <option >SLide wight</option>
            </select><br>
            Speed:<div id="slider"></div>
          </div>
          <hr>
          Dimensions:  Width:<input type='text'> height: <input type="text">
          <hr>
          Background :
          <select>
            <option>Default</option>
            <option>Solid</option>
            <option>Gradiant</option>
            <option>Image</option>
          </select>
          Bg Color: <input type="text"><!-- Have to insert color picker here-->
        </div>
        <div id="chart">
          Dimension: <br> Width:<input type="text"> <br>Hight: <input type="text">
        </div>
      </div>
    </div>
    <h3>Chart specific</h3>
    <div>
      Nothing
    </div>
    <h3>Title</h3>
    <div>
      Visible : <input type="checkbox" onchange="showtitle()" id="visibleTitle">
      <hr>
      Text: <input type="text" id="titleText" onKeyUp="set_text_title()">
      <hr>
      Adjust layout:<input type="checkbox" onchange="adjast_layout_title()" id="adjust-layout">
      <hr>
      Background :
      <select id="background-type" onchange="set_background_type()">
        <option value="solid"> Solid </option>
        <option value="gradiant"> Gradiant </option>
      </select><br>
      Background color 1 : <input type="color" id="background-color-1" onchange="set_background_color()"><br>
      Background color 2 : <input type="color" id="background-color-2" onchange="set_background_color()" style="visibility :hidden">
      <hr>
      Bold :  <input type="checkbox" id="boldTitle" onchange="set_bold_title()">
      <hr>
      Font Color : <input type="color" id= "font-color" onchange="set_font_color()"><br>
      Font Style : 
      <select type="text" id="font-style"  onchange="set_font_style()">
        <option value = "normal" > normal</option>
        <option value = "italic" > italic</option>
        <option value = "oblique"> oblique</option>
      </select><br>
      <!-- Have to change it to select later -->
      Font Family :<input type="text" id="font-family" onKeyUp = "set_font_family_title()">

    </div>
  </div>
  
  <div>
    <span id='ttl'></span>

<div id='chartDiv'></div>
  </div>
  <div style="clear:both"></div>
  <?php
}
function zing_html ( $post ) {
  wp_nonce_field( ZING_NOUNCE, 'zing_nounce' );
  $html = get_post_meta ($post->ID, 'zing_html_content', true );
  // get settings
  //$settings = get_option( 'amcharts_options', amcharts_get_defaults() );
  
  ?>
  
  <p>
    <textarea name="html" class="widefat code code-html" id="zingchart-html"><?php echo esc_textarea( $html ); ?></textarea>
  </p>
  
  <p class="description">
    <?php _e( 'Html tooltip ', 'zingchart' ); ?>
  </p>
  
  <?php
}

function zing_javascript($post) {
  wp_nonce_field( ZING_NOUNCE, 'zing_nounce' );
  $javaScript =  get_post_meta($post->ID,'zing_javascript_content',true);
  ?>
  <textarea name="JavaScript" class="widefat code code-html" id="zingcharts-javaScript"> <?php echo esc_textarea( $javaScript ); ?></textarea>
  <?php
}
/**
 * Save post metadata when a post is saved.
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function zing_save_chart($post_id,$post,$update) {
  if ('zing_chart' ==$post->post_type && isset($_POST)) {
    if (isset($_POST['zing_nounce'])&&!wp_verify_nonce($_POST['zing_nounce'],ZING_NOUNCE)) return;
      if (isset($_POST['html'])&& isset($_POST['JavaScript'])) {
        update_post_meta($post_id,'zing_html_content',trim($_POST['html']));
        update_post_meta($post_id,'zing_javascript_content',trim($_POST['JavaScript']));
      }
  }
}
add_action('save_post_zing_chart','zing_save_chart',10,3);

/**
 * Plot the chart on the screen
 * @param array $atts Shortcode array attributes 
 */
function plot_it ($atts) {
  foreach ($atts as $key => $value) {
    if ($key== 'id') {
      $post = get_post('value');
      return get_post_meta($value,'zing_html_content',true).get_post_meta($value,'zing_javascript_content',true);
    }
  }
}
add_shortcode("zing","plot_it");