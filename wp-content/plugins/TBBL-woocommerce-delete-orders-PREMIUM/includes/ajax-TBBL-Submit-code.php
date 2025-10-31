<?php

    add_action('wp_ajax_nopriv_TBBL_ajax_submit_code','func_TBBL_nopriv_ajax_submit_code');
    add_action('wp_ajax_TBBL_ajax_submit_code','func_TBBL_ajax_submit_code');

    function func_TBBL_nopriv_ajax_submit_code(){
        echo ("Forbbiden, you must login.");
    }

    function func_TBBL_ajax_submit_code(){
        // Proceso de envio, recepcion y gestion de licencia
        include_once ( plugin_dir_path( __FILE__ ) .  '/ajax-WCDO-api.php' );

        wp_die();
        exit();
    }


