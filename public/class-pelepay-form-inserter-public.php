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
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @var      string    $plugin_name       The name of the plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
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
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/pelepay-form-inserter-public.css', array(), $this->version, 'all');
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
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/pelepay-form-inserter-public.js', array('jquery'), $this->version, false);
    }

    /**
     * Create shortcode for pelepay form
     */
    function register_shortcodes() {
        add_shortcode('kamoha_pelepay_form', array(&$this, 'insert_pelepay_form'));
        add_shortcode('pelepay_form', array(&$this, 'insert_pelepay_form'));
    }

    /* Create shortcode functions */

    /**
     * Get the attributes from the shortcode:
     * The price list, the text to attach to each price, and the fist option that is displayd
     * @param type $atts - shortcode attributes
     * @return the HTML to insert instead of shortcode
     */
    function insert_pelepay_form($atts) {
        $ret ='';
        $first_option_text = htmlspecialchars($atts['first_option']);
        $price_list = explode(',', htmlspecialchars($atts['price_list']));
        $price_text = explode(',', htmlspecialchars($atts['price_text']));
        $price_len = count($price_list);
        $default_payment_for = '_chart_shopp';
        /* create dropdown list with options from attributes */
        $ret .= '<p><select id="amount">' .
                '<option value="0">' . $first_option_text . '</option>';
        for ($i = 0; $i < $price_len; $i ++) {
            $ret .='<option value="' . trim($price_list[$i]) . '">' . trim($price_text[$i]) . '</option>';
        }
        $ret .= '</select></p>';
        /* save payment's goal and show them in a text box */
        $payment_for = empty($atts['payment_for']) ? $default_payment_for : htmlspecialchars($atts['payment_for']);
        $ret .= '<p><label for="description_text">תרומה בעבור:</label><br>';
        $ret .= '<input name="description_text" id="description_text" type="text" value="';
        if ($payment_for != $default_payment_for) {
            $ret .= $payment_for;
        }
        $ret .='" /></p>';
        /* save number of payments */
        $payments = empty($atts['payments']) ? 1 : htmlspecialchars($atts['payments']);
        /* create the pelepay form with hidden fields, and a button which a function is attached to its click event */
        $ret .= '<form action="https://www.pelepay.co.il/pay/paypage.aspx" method="post" name="pelepayform">'
                . '<input name="business" type="hidden" value="kamoha.or@gmail.com" />'
                . '<input name="amount" type="hidden" value="" />'
                . '<input name="orderid" type="hidden" value="" />'
                . '<input name="description" type="hidden" value="' . $payment_for . '" />'
                . '<input id="pelepay_submit" alt="Make payments with pelepay" name="pelepay_submit" src="https://www.pelepay.co.il/images/banners/respect_pp_8c.gif" type="image" />'
                . '<input type="hidden" name="max_payments" value="' . $payments . '">'
                . '</form>';
        return $ret;
    }

}
