<?php
/**
* Plugin Name: 					Scripts and Codes
* Plugin URI: 					https://github.com/shorelinemedia/shoreline-scripts-codes
* Description: 					Adds a section in the Customizer to add scripts and codes to header, footer and just after body
* Version: 							1.2
* Author: 							Shoreline Media
* Author URI: 					https://shoreline.media
* License:           		GNU General Public License v2
* License URI:       		http://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:       		sl9-scripts-codes
* GitHub Plugin URI: 		https://github.com/shorelinemedia/shoreline-scripts-codes
*/


class SL9_Scripts_Codes {

  public static $instance;

  // Hold our namespace
  public $namespace;

  // Hold our body hook name
  public $body_hook;

  // Hold our disabled variable
  public $is_disabled;

  public function __construct() {
    $this->namespace = 'sl9_scripts_codes';

    // Get our body hook from the Customizer settings
    // Default is 'wp_body_open'
    $this->body_hook = get_theme_mod('scriptscodes_body_hook', 'wp_body_open');

    add_action( 'plugins_loaded', array( $this, 'init' ) );

  }

  // Return an instance of our class
  public static function get_instance() {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  // Init our script
  public function init() {
    // Setup Customizer
    add_action("customize_register", array( $this, 'customize_register' ), 20);

    // Add CodeMirror to HTML fields in Customizer
    add_action( 'admin_enqueue_scripts', array( $this, 'initCodeMirror' ) );

    // Allow plugins to disable output via filter
    $this->is_disabled = apply_filters( 'sl9_scripts_codes_disable', false );

    // Allow disabling through filter
    if ( $this->is_disabled ) { return; }

    // Only hook our scripts and codes if we aren't in dev mode
    add_action( 'wp_head', array( $this, 'code_head' ), 300 );
    add_action( $this->body_hook, array( $this, 'code_body' ), 1 );
    add_action( 'wp_footer', array( $this, 'code_footer' ), 1 );
  }

  public function customize_register( $wp_customize ) {
		// only admin user can access this page
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}


    $wp_customize->add_section("scriptscodes", array(
      "title" => __('Scripts &amp; Codes', $this->namespace),
      "priority" => 300
    ));
    $wp_customize->add_setting("scriptscodes_body_hook", array(
      "default" => "wp_body_open",
      "transport" => "refresh"
    ));
    $wp_customize->add_setting("scriptscodes_head", array(
      "default" => "",
      "transport" => "refresh"
    ));
    $wp_customize->add_setting("scriptscodes_body", array(
      "default" => "",
      "transport" => "refresh"
    ));
    $wp_customize->add_setting("scriptscodes_footer", array(
      "default" => "",
      "transport" => "refresh"
    ));


    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'scriptscodes_body_hook', array(
      'label'         => __( 'Body Hook', $this->namespace ),
      'description'   => __( 'What hook should we use to hook into the body code? Not all templates/themes have the \'wp_body_open\' hook available.', $this->namespace ),
      'section'       => 'scriptscodes',
      'settings'      => 'scriptscodes_body_hook',
      'type'          => 'text'
    ) ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'scriptscodes_head', array(
      'label'         => __( 'Header Code', $this->namespace ),
      'description'   => __( 'Add scripts and other code that will appear on every page inside the head', $this->namespace ),
      'section'       => 'scriptscodes',
      'settings'      => 'scriptscodes_head',
      'type'          => 'textarea'
    ) ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'scriptscodes_body', array(
      'label'         => __( 'Body Code', $this->namespace ),
      'description'   => __( 'Add scripts and other code that will appear on every page just after the starting body tag', $this->namespace ),
      'section'       => 'scriptscodes',
      'settings'      => 'scriptscodes_body',
      'type'          => 'textarea'
    ) ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'scriptscodes_footer', array(
      'label'         => __( 'Footer Code', $this->namespace ),
      'description'   => __( 'Add scripts and other code that will appear on every page in the footer', $this->namespace ),
      'section'       => 'scriptscodes',
      'settings'      => 'scriptscodes_footer',
      'type'          => 'textarea'
    ) ) );
  }

  // Add CodeMirror to our fields in customizer
  public function initCodeMirror() {
		// Make sure that we don't fatal error on WP versions before 4.9.
		if ( ! function_exists( 'wp_enqueue_code_editor' ) ) {
			return;
		}

		global $pagenow;
    $screen = get_current_screen();
    
    if ( $screen && $screen->base == 'customize' ) {

      // Enqueue code editor and settings for manipulating HTML.
      $settings = wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

      // Bail if user disabled CodeMirror.
      if ( false === $settings ) {
        return;
      } 
      
      // Custom styles for the form fields.
      $styles = '.CodeMirror{ border: 1px solid #ccd0d4; }';

      wp_add_inline_style( 'code-editor', $styles );

      wp_add_inline_script( 'code-editor', sprintf( 'jQuery( function() { wp.codeEditor.initialize( "_customize-input-scriptscodes_head", %s ); } );', wp_json_encode( $settings ) ) );
      wp_add_inline_script( 'code-editor', sprintf( 'jQuery( function() { wp.codeEditor.initialize( "_customize-input-scriptscodes_body", %s ); } );', wp_json_encode( $settings ) ) );
      wp_add_inline_script( 'code-editor', sprintf( 'jQuery( function() { wp.codeEditor.initialize( "_customize-input-scriptscodes_footer", %s ); } );', wp_json_encode( $settings ) ) );



    } // endif on the customizer screen

  }

  // Output our codes sections
  public function code_head() {
    $code = get_theme_mod('scriptscodes_head', '');

    if ( $code && $code != "" ) {
      echo $code . "\n\n";
    }
  }
  public function code_body() {
    $code = get_theme_mod('scriptscodes_body', '');

    if ( $code && $code != "" ) {
      echo $code . "\n";
    }
  }
  public function code_footer() {
    $code = get_theme_mod('scriptscodes_footer', '');

    if ( $code && $code != "" ) {
      echo $code . "\n";
    }
  }



}

$sl9_scripts_codes = new SL9_Scripts_Codes();
