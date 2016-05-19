<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://leketshibolim.ort.org.il
 * @since      1.0.0
 *
 * @package    Pelepay_Form_Inserter
 * @subpackage Pelepay_Form_Inserter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Pelepay_Form_Inserter
 * @subpackage Pelepay_Form_Inserte/public
 * @author     Lea Cohen <leac@ort.org.il>
 */
class Pelepay_Form_Inserter_Public {

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
     * Url to pelepay
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $basic_form_action    The pelepay URL
     */
    private $basic_form_action;

    /**
     * Placeholder in the form, for insertion of more elements
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $form_elemenst_placeholder    Placeholder in the form, for insertion of more elements
     */
    private $form_elemenst_placeholder = 'form_elemenst_placeholder';

    /**
     * Placeholder in the form, for insertion of a specific submit button
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $submit_button_placeholder    Placeholder in the form, for insertion of a specific submit button
     */
    private $submit_button_placeholder = 'submit_button_placeholder';

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @var      string    $plugin_name       The name of the plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->basic_form_action = "https://www.pelepay.co.il/pay/paypage.aspx";
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        // wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/pelepay-form-inserter-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pelepay-form-inserter-public.js', array('jquery'), $this->version, false );
    }

    /**
     * Create shortcode for pelepay form
     */
    function register_shortcodes() {
        add_shortcode( 'kamoha_pelepay_form', array(&$this, 'insert_pelepay_form') );
        add_shortcode( 'pelepay_form', array(&$this, 'insert_pelepay_form') );
    }

    /* Create shortcode functions */

    /**
     * Get the attributes from the shortcode:
     * The price list, the text to attach to each price, and the fist option that is displayd
     * @param type $atts - shortcode attributes
     * @return the HTML to insert instead of shortcode
     */
    function insert_pelepay_form( $atts ) {
        $ret = '';
        $form_elements = '';
        $first_option_text = htmlspecialchars( $atts['first_option'] );
        $price_list = explode( ',', htmlspecialchars( $atts['price_list'] ) );
        $price_text = explode( ',', htmlspecialchars( rawurldecode( $atts['price_text'] ) ) );
        $price_len = count( $price_list );
        $form_action = (isset($atts['principles_page'])  && $atts['principles_page'] != 0) ? get_permalink( $atts['principles_page'] ) : $this->basic_form_action;
        $default_payment_for = '_chart_shopp';
        /* create dropdown list with options from attributes */
        $form_elements .= '<p>';
        $form_elements .='<div class="pelepay_error_msg error_msg">' . __( 'No amount chosen', $this->plugin_name ) . '</div>';
        $form_elements.='<select id="amount_select">' .
                '<option value="0">';
        $form_elements .= $first_option_text != null ? $first_option_text : __( 'Click here to choose sum', $this->plugin_name );
        $form_elements.= '</option>';
        for ( $i = 0; $i < $price_len; $i ++ ) {
            $form_elements .='<option value="' . trim( $price_list[$i] ) . '">' . trim( $price_text[$i] ) . '</option>';
        }
        $form_elements .= '</select>';
        $form_elements .= '</p>';
        /* save payment's goal and show them in a text box */
        $payment_for = empty( $atts['payment_for'] ) ? $default_payment_for : htmlspecialchars( rawurldecode( $atts['payment_for'] ) );
        $form_elements .= '<p><label for="description_text">' . __( 'Payment for...', $this->plugin_name ) . '</label><br>';
        $form_elements .= '<input name="description_text" id="description_text" type="text" value="';
        if ( $payment_for != $default_payment_for ) {
            $form_elements .= $payment_for;
        }
        $form_elements .='" /></p>';
        /* save number of payments */
        $payments = empty( $atts['payments'] ) ? 1 : htmlspecialchars( $atts['payments'] );
        $ret .= $this->create_pelepay_form( $payment_for, $payments, '', $form_action );
        // insert the rest of the form elements
        $ret = str_replace( $this->form_elemenst_placeholder, $form_elements, $ret );
        // insert submit button
        $submit_button = '<input id="pelepay_submit" alt="Make payments with pelepay" name="pelepay_submit" src="' . plugin_dir_url( __FILE__ ) . 'images/pelepay.png" type="image" />';
        $ret = str_replace( $this->submit_button_placeholder, $submit_button, $ret );
        return $ret;
    }

    /**
     * Create the pelepay form with hidden fields, and a button which a function is attached to its click event 
     */
    private function create_pelepay_form( $payment_for, $payments, $amount = "", $form_action ) {
        $ret = '<form action=' . $form_action . ' method="post" name="pelepayform">'
                . $this->form_elemenst_placeholder
                . '<input name="business" type="hidden" value="kamoha.or@gmail.com" />'
                . '<input name="amount" type="hidden" value=' . $amount . ' />'
                . '<input name="orderid" type="hidden" value="" />'
                . '<input name="description" type="hidden" value="' . $payment_for . '" />'
                . '<input type="hidden" name="max_payments" value="' . $payments . '">'
                . $this->submit_button_placeholder
                . '</form>';
        return $ret;
    }

    public function pelepay_add_form_after_principles() {
        $ret = '';
        $ret = $this->create_pelepay_form( stripslashes( htmlspecialchars( $_POST['description'] ) ), $_POST['max_payments'], $_POST['amount'], $this->basic_form_action );
        // add a checkbox
        $form_element = '<input type="checkbox" id="read_chkbx" value="read_checkbox"> <label for="read_chkbx">' . __( 'I read the principles', $this->plugin_name ) . '</label><br>';
        $ret = str_replace( $this->form_elemenst_placeholder, $form_element, $ret );
        // add a submit button
        $submit_button = '<input type="submit" id="principles_submit" value="' . __( 'Go on to pay', $this->plugin_name ) . '">';
        $ret = str_replace( $this->submit_button_placeholder, $submit_button, $ret );
        return $ret;
    }

    /**
     * Use custom field in page to indicate a page that should embed a pelepay form
      /* http://wordpress.stackexchange.com/questions/3396/create-custom-page-templates-with-plugins
     * @param string $page_template
     * @return string
     */
    public function page_template( $page_template ) {
        /* this page template will only be applied to pages */
        if ( is_page() ) {
            if ( get_post_meta( get_the_ID(), 'principles_page' ) ) {
                $page_template = dirname( __FILE__ ) . '/pelepay_principles_page_template.php';
            }
        }
        return $page_template;
    }

}
