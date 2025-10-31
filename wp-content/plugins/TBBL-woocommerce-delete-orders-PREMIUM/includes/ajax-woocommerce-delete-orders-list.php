<?php

    add_action('wp_ajax_nopriv_WCDOp_ajax_readrow','func_WCDOp_nopriv_ajax_readrow');
    add_action('wp_ajax_WCDOp_ajax_readrow','func_WCDOp_ajax_readrow');

    add_action('wp_ajax_nopriv_WCDOp_ajax_deleterow','func_WCDOp_nopriv_ajax_readrow');
    add_action('wp_ajax_WCDOp_ajax_deleterow','func_WCDOp_ajax_deleterow');



    function func_WCDOp_nopriv_ajax_readrow(){
        alert ("Forbbiden, you must login.");
    }


    function func_WCDOp_ajax_readrow(){
        $post_date = ($_POST['post_date']);
        $post_status = ($_POST['post_status']);
        $post_export_backup = ($_POST['post_export_backup']);
      
        echo func_WCDOp_ajax_generar_JSON ( $post_date , $post_status , $post_export_backup );
        wp_die();
        exit();
    }


    function func_WCDOp_ajax_deleterow(){
        $post_date = ($_POST['post_date']);
        $post_status = ($_POST['post_status']);
        $post_export_backup = ($_POST['post_export_backup']);
      
        echo func_WCDOp_ajax_deleterow_execute ( $post_date , $post_status , $post_export_backup );
        wp_die();
        exit();
    }

    include_once ( plugin_dir_path( __FILE__ ) .  '/ajax-woocommerce-delete-orders-functions.php'  );

