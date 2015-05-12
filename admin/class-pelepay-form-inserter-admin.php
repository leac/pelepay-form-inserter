<?php

    /**
     * The dashboard-specific functionality of the plugin.
     *
     * @link       http://leketshibolim.ort.org.il
     * @since      1.0.0
     *
     * @package    Pelepay_Form_Inserter
     * @subpackage Pelepay_Form_Inserter/admin
     */

    /**
     * The dashboard-specific functionality of the plugin.
     *
     * Defines the plugin name, version, and two examples hooks for how to
     * enqueue the dashboard-specific stylesheet and JavaScript.
     *
     * @package    Pelepay_Form_Inserter
     * @subpackage Pelepay_Form_Inserte/admin
     * @author     Lea Cohen <leac@ort.org.il>
     */
    class Pelepay_Form_Inserter_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

	    $this->plugin_name = $plugin_name;
	    $this->version = $version;
	}

	// When using a plugin that changes the paths dinamically, set these earlier than 'plugins_loaded' 50.
	function set_paths() {
	    if ( ! defined( 'PFI_URL' ) )
		define( 'PFI_URL', plugin_dir_url( __FILE__ ) );

	    if ( ! defined( 'PFI_PATH' ) )
		define( 'PFI_PATH', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

	    /**
	     * This function is provided for demonstration purposes only.
	     *
	     * An instance of this class should be passed to the run() function
	     * defined in Pelepay_Form_Inserter_Loader as all of the hooks are defined
	     * in that particular class.
	     *
	     * The Pelepay_Form_Inserter_Loader will then create the relationship
	     * between the defined hooks and the functions defined in this
	     * class.
	     */
	    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pelepay-form-inserter-admin.css', array (), $this->version, 'all' );
	    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tinymce.css', array (), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

	    /**
	     * This function is provided for demonstration purposes only.
	     *
	     * An instance of this class should be passed to the run() function
	     * defined in Pelepay_Form_Inserter_Loader as all of the hooks are defined
	     * in that particular class.
	     *
	     * The Pelepay_Form_Inserter_Loader will then create the relationship
	     * between the defined hooks and the functions defined in this
	     * class.
	     */
	    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pelepay-form-inserter-admin.js', array ( 'jquery' ), $this->version, false );
	}

	/**
	 * Adds 2 filters:
	 * One to specify the path to the script with our plugin for TinyMC.
	 * The second, is used to add a button in the editor
	 *
	 * @since    1.0.0
	 */
	function add_my_tc_button() {
	    global $typenow;
	    // check user permissions
	    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
		return;
	    }

	    // verify the post type
	    if ( ! in_array( $typenow, array ( 'post', 'page' ) ) )
		return;
	    // check if WYSIWYG is enabled
	    if ( get_user_option( 'rich_editing' ) == 'true' ) {
		add_filter( "mce_external_plugins", array ( &$this, 'pelepay_add_tinymce_plugin' ) );
		add_filter( 'mce_buttons', array ( &$this, 'pelepay_register_my_tc_button' ) );
	    }
	}

	/**
	 * Called by add_my_tc_button, adds our tinymce button's js file to plugin array
	 * @param array $plugin_array
	 * @return $plugin_array
	 */
	function pelepay_add_tinymce_plugin( $plugin_array ) {
	    $plugin_array['pelepay_tc_button'] = PFI_URL . 'js/pelepay_form.js'; // PFI_URL already includes admin folder
	    return $plugin_array;
	}

	/**
	 * Called by add_my_tc_button, adds our button name to the buttons array. This name is the one used in the editor.addButton in the js file
	 * @param type $buttons
	 * @return $buttons
	 */
	function pelepay_register_my_tc_button( $buttons ) {
	    array_push( $buttons, 'pelepay_tc_button' );
	    return $buttons;
	}

	/**
	 * Add translation file to tinymce plugin
	 * @param array $locales
	 * @return string
	 */
	function add_my_tc_button_lang( $locales ) {
	    $locales['pelepay_tc_button'] = plugin_dir_path ( __FILE__ ) . 'pelepay_tinymce_btn_lang.php';
	    return $locales;
	}

    }
