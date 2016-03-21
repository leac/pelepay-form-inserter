<?php
    /* Translation file for tinymce button */
    if ( ! defined( 'ABSPATH' ) )
	exit;

    if ( ! class_exists( '_WP_Editors' ) ) {
	require( ABSPATH . WPINC . '/class-wp-editor.php' );
    }

    function pelepay_tc_button_translation() {
	$plugin = new Pelepay_form_inserter();
	$strings = array (
	    'button_label' => __( 'Add Pelepay form', $plugin->get_plugin_name() ),
	    'price_list' => __( 'Comma separated price list', $plugin->get_plugin_name() ),
	    'price_text' => __( 'Comma separated price text list', $plugin->get_plugin_name() ),
	    'payment_for' => __( 'Payment for...', $plugin->get_plugin_name() ),
            'payments_text' => __( 'Max number of payments', $plugin->get_plugin_name() )
	);

	$locale = _WP_Editors::$mce_locale;

	$translated = 'tinyMCE.addI18n("' . $locale . '.pelepay_tc_button", ' . json_encode( $strings ) . ");\n";

	return $translated;
    }

    $strings = pelepay_tc_button_translation();
