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
     * The arguments for query of page with the principles_page custom field
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $principles_pages_args    The arguments for query of page with the principles_page custom field
     */
    private $principles_pages_args;

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
        $this->principles_pages_args = array(
            'post_type' => 'page',
            'meta_key' => 'principles_page'
        );
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
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pelepay-form-inserter-admin.css', array(), $this->version, 'all' );
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
        if ( ! in_array( $typenow, array('post', 'page') ) )
            return;
        // check if WYSIWYG is enabled
        if ( get_user_option( 'rich_editing' ) == 'true' ) {
            add_filter( "mce_external_plugins", array(&$this, 'pelepay_add_tinymce_plugin') );
            add_filter( 'mce_buttons', array(&$this, 'pelepay_register_my_tc_button') );
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
        $locales['pelepay_tc_button'] = plugin_dir_path( __FILE__ ) . 'pelepay_tinymce_btn_lang.php';
        return $locales;
    }

    /**
     * The shortcode attributes to show in admin
     */
    function shortcode_ui_pelepay() {
        /**
         * Register UI for kamoha_pelepay_form shortcode
         *
         * @param string $shortcode_tag
         * @param array $ui_args
         */
        shortcode_ui_register_for_shortcode( 'kamoha_pelepay_form', array(
            /*
             * How the shortcode should be labeled in the UI. Required argument.
             */
            'label' => esc_html__( 'Add Pelepay form', $this->plugin_name ),
            /*
             * Include an icon with your shortcode. Optional.
             * Use a dashicon, or full URL to image.
             */
            'listItemImage' => 'dashicons-clock',
            /*
             * Limit this shortcode UI to specific posts. Optional.
             */
            'post_type' => array('post'),
            /*
             * Register UI for attributes of the shortcode. Optional.
             *
             * If no UI is registered for an attribute, then the attribute will 
             * not be editable through Shortcake's UI. However, the value of any 
             * unregistered attributes will be preserved when editing.
             * 
             * Each array must include 'attr', 'type', and 'label'.
             * 'attr' should be the name of the attribute.
             * 'type' options include: text, checkbox, textarea, radio, select, email, 
             *     url, number, and date, post_select, attachment, color.
             * Use 'meta' to add arbitrary attributes to the HTML of the field.
             * Use 'encode' to encode attribute data. Requires customization to callback to decode.
             * Depending on 'type', additional arguments may be available.
             */
            'attrs' => array(
                array(
                    'label' => esc_html__( 'Comma separated price list', $this->plugin_name ),
                    'attr' => 'price_list',
                    'type' => 'text',
                ),
                array(
                    'label' => esc_html__( 'Comma separated price text list', $this->plugin_name ),
                    'attr' => 'price_text',
                    'type' => 'text',
                    'encode' => true,
                ),
                array(
                    'label' => esc_html__( 'Payment for...', $this->plugin_name ),
                    'attr' => 'payment_for',
                    'type' => 'text',
                    'encode' => true,
                ),
                array(
                    'label' => esc_html__( 'Max number of payments', $this->plugin_name ),
                    'attr' => 'payments',
                    'type' => 'text',
                ),
                array(
                    'label' => esc_html__( 'Choose page to redirect', $this->plugin_name ),
                    'attr' => 'principles_page',
                    'type' => 'post_select',
                    'query' => $this->principles_pages_args,
                ),
            ),
                )
        );
    }

    /**
     * Function to fetch cpt posts list
     * @return json
     */
    public function principles_pages() {

        $list = array();
        // insert first option none
        $list[] = array(
            'text' => __( 'Choose page to redirect', $this->plugin_name ),
            'value' => 0
        );

        $principles_pages = new WP_Query( $this->principles_pages_args );

        while ( $principles_pages->have_posts() ) : $principles_pages->the_post();
            $post_id = get_the_ID();
            $post_name = htmlspecialchars_decode( get_the_title() );
            $list[] = array(
                'text' => $post_name,
                'value' => $post_id
            );

        endwhile;

        wp_reset_postdata();

        wp_send_json( $list );
    }

    /**
     * Function to fetch principles pages
     * @since  1.6
     * @return string
     */
    public function principles_pages_list_ajax() {
        // check for nonce
        check_ajax_referer( 'principles-pages-nonce', 'security' );
        $posts = $this->principles_pages();
        return $posts;
    }

    /**
     * Function to output button list ajax script
     * Gets the list of pages (using function principles_pages_list) and stores in TinyMCE settings
     * @since  1.6
     * @return string
     */
    public function principles_pages_list() {
        // create nonce
        global $pagenow;
        if ( $pagenow != 'admin.php' ) {
            $nonce = wp_create_nonce( 'principles-pages-nonce' );
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    var data = {
                        'action': 'principles_pages_list', // wp ajax action
                        'security': '<?php echo $nonce; ?>' // nonce value created earlier
                    };
                    // fire ajax
                    jQuery.post(ajaxurl, data, function (response) {
                        // if nonce fails then not authorized else settings saved
                        if (response === '-1') {
                            // do nothing
                        } else {
                            if (typeof (tinyMCE) != 'undefined') {
                                if (tinyMCE.activeEditor != null) {
                                    tinyMCE.activeEditor.settings.principlesPagesList = response;
                                    console.log(JSON.stringify(tinyMCE.activeEditor.settings.principlesPagesList));
                                }
                            }
                        }
                    });
                });
            </script>
            <?php
        }
    }

}
