<?php
//http://www.pipsomania.com/best_php_obfuscator.do
    function func_WCDOp_ajax_deleterow_execute ( $post_date , $post_status , $post_export_backup ){
        global $wpdb;
      
        $backup = "";
        $retorno_carro = "\\n";
      
        if ( $post_status == "wc-all") { $post_status = "";}
        if ( strtolower ( $post_export_backup ) == "true" ) { $post_export_backup = true; } else { $post_export_backup = false; }
      
        // woocommerce_order_itemmeta
            $wc_sql = " DELETE FROM " . $GLOBALS['table_prefix'] . "woocommerce_order_itemmeta WHERE order_item_id IN (SELECT order_id FROM " . $GLOBALS['table_prefix'] . "woocommerce_order_items WHERE order_id IN ( SELECT ID FROM " . $GLOBALS['table_prefix'] . "posts ";
              $wc_sql .= " WHERE post_date < '" . $post_date . "' ";
              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }
              $wc_sql .= " ))";
            $WCDO_woocommerce_order_itemmeta = $wpdb->query( $wc_sql);
      
      
      
        // woocommerce_order_items
            $wc_sql = " DELETE FROM " . $GLOBALS['table_prefix'] . "woocommerce_order_items WHERE order_id IN (SELECT ID FROM " . $GLOBALS['table_prefix'] . "posts  ";
              $wc_sql .= " WHERE post_date < '" . $post_date . "' ";
              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }
              $wc_sql .= " )";
            $WCDO_woocommerce_order_items = $wpdb->query( $wc_sql);

      
      
      
        // comment_type  
            $wc_sql = " DELETE FROM " . $GLOBALS['table_prefix'] . "comments WHERE comment_type = 'order_note' AND comment_post_ID IN (SELECT ID FROM " . $GLOBALS['table_prefix'] . "posts ";
              $wc_sql .= " WHERE post_date < '" . $post_date . "' ";
              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }
              $wc_sql .= " )";
            $WCDO_comment_type = $wpdb->query( $wc_sql);
      

      
        // postmeta
            $wc_sql = " DELETE FROM " . $GLOBALS['table_prefix'] . "postmeta WHERE post_id IN (SELECT ID FROM " . $GLOBALS['table_prefix'] . "posts ";
              $wc_sql .= " WHERE  post_type = 'shop_order' AND post_date < '" . $post_date . "' ";
              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }
              $wc_sql .= " )";  
            $WCDO_postmeta = $wpdb->query( $wc_sql);
      
      
      
        // posts 
            $wc_sql = " DELETE FROM " . $GLOBALS['table_prefix'] . "posts ";
              $wc_sql .= " WHERE post_type = 'shop_order' AND post_date < '" . $post_date . "' ";
              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }
            $WCDO_posts =  $wpdb->query( $wc_sql);
      
      
        $array_results = array(
           "woocommerce_order_itemmeta"  => $WCDO_woocommerce_order_itemmeta , 
           "woocommerce_order_items"  => $WCDO_woocommerce_order_items , 
           "comment_type"  => $WCDO_comment_type , 
           "postmeta"  => $WCDO_postmeta , 
           "posts"  => $WCDO_posts , 
           "backup"  => $backup 
        );
      

        return json_encode($array_results);
    }


    function func_WCDOp_ajax_generar_JSON ( $post_date , $post_status , $post_export_backup ){
        global $wpdb;
      
        $backup = "";
        $retorno_carro = "\\n";
      
        if ( $post_status == "wc-all") { $post_status = "";}
        if ( strtolower ( $post_export_backup ) == "true" ) { $post_export_backup = true; } else { $post_export_backup = false; }
      
        // woocommerce_order_itemmeta
            $wc_sql = " SELECT * FROM " . $GLOBALS['table_prefix'] . "woocommerce_order_itemmeta WHERE order_item_id IN (SELECT order_id FROM " . $GLOBALS['table_prefix'] . "woocommerce_order_items WHERE order_id IN ( SELECT ID FROM " . $GLOBALS['table_prefix'] . "posts ";
              $wc_sql .= " WHERE post_date < '" . $post_date . "' ";
              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }
              $wc_sql .= " ))";
            $results = $wpdb->get_results( $wc_sql , OBJECT );
            $WCDO_woocommerce_order_itemmeta = count($results);
            $backup .= " -- " . $retorno_carro ;
            $backup .= " -- " . $GLOBALS['table_prefix'] . "woocommerce_order_itemmeta " . $retorno_carro ;
            $backup .= " -- " . $retorno_carro ;
            // $backup .= $wc_sql . "  " . $retorno_carro ;
            if ( $post_export_backup ) {
                $backup .= get_results_to_insert_sql ( $results , "woocommerce_order_itemmeta"  , $retorno_carro) . $retorno_carro ;
            }
      
      
      
        // woocommerce_order_items
            $wc_sql = " SELECT * FROM " . $GLOBALS['table_prefix'] . "woocommerce_order_items WHERE order_id IN (SELECT ID FROM " . $GLOBALS['table_prefix'] . "posts  ";
              $wc_sql .= " WHERE post_date < '" . $post_date . "' ";
              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }
              $wc_sql .= " )";
            $results = $wpdb->get_results( $wc_sql , OBJECT );
            $WCDO_woocommerce_order_items = count($results);
            $backup .= $retorno_carro . " -- " . $retorno_carro ;
            $backup .= " -- " . $GLOBALS['table_prefix'] . "woocommerce_order_items " . $retorno_carro ;
            $backup .= " --   " . $retorno_carro ;
            // $backup .= $wc_sql . "  " . $retorno_carro ;
            if ( $post_export_backup ) {
                $backup .= get_results_to_insert_sql ( $results , "woocommerce_order_items"  , $retorno_carro) . $retorno_carro ;
            }
      
      
      
        // comment_type
            $wc_sql = "SELECT * FROM " . $GLOBALS['table_prefix'] . "comments WHERE comment_type = 'order_note' AND comment_post_ID IN (SELECT ID FROM " . $GLOBALS['table_prefix'] . "posts ";
              $wc_sql .= " WHERE post_date < '" . $post_date . "' ";
              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }
              $wc_sql .= " )";      
            $results = $wpdb->get_results( $wc_sql , OBJECT );
            $WCDO_comment_type = count($results);
            $backup .= $retorno_carro . " -- " . $retorno_carro ;
            $backup .= " -- " . $GLOBALS['table_prefix'] . "comments " . $retorno_carro ;
            $backup .= " --   " . $retorno_carro ;
            // $backup .= $wc_sql . "  " . $retorno_carro ;
            if ( $post_export_backup ) {
                $backup .= get_results_to_insert_sql ( $results , "comments"  , $retorno_carro) . $retorno_carro ;
            }
      

      
        // postmeta
            $wc_sql = "SELECT * FROM " . $GLOBALS['table_prefix'] . "postmeta WHERE post_id IN (SELECT ID FROM " . $GLOBALS['table_prefix'] . "posts ";
              $wc_sql .= " WHERE  post_type = 'shop_order' AND post_date < '" . $post_date . "' ";
              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }
              $wc_sql .= " )";  
            $results = $wpdb->get_results( $wc_sql , OBJECT );
            $WCDO_postmeta = count($results);
            $backup .= $retorno_carro . " -- " . $retorno_carro ;
            $backup .= " -- " . $GLOBALS['table_prefix'] . "postmeta " . $retorno_carro ;
            $backup .= " --   " . $retorno_carro ;
            // $backup .= $wc_sql . "  " . $retorno_carro ;
            if ( $post_export_backup ) {
                $backup .= get_results_to_insert_sql ( $results , "postmeta"  , $retorno_carro) . $retorno_carro ;
            }
      
      
      
        // posts 
            $wc_sql = "SELECT * FROM " . $GLOBALS['table_prefix'] . "posts ";
              $wc_sql .= " WHERE post_type = 'shop_order' AND post_date < '" . $post_date . "' ";
              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }
            $results = $wpdb->get_results( $wc_sql , OBJECT );
            $WCDO_posts = count($results);
            $backup .= $retorno_carro . " -- " . $retorno_carro ;
            $backup .= " -- " . $GLOBALS['table_prefix'] . "posts " . $retorno_carro ;
            $backup .= " --   " . $retorno_carro ;
            // $backup .= $wc_sql . "  " . $retorno_carro ;
            if ( $post_export_backup ) {
                $backup .= get_results_to_insert_sql ( $results , "posts"  , $retorno_carro) . $retorno_carro ;
            }
      

        $array_results = array(
           "woocommerce_order_itemmeta"  => $WCDO_woocommerce_order_itemmeta , 
           "woocommerce_order_items"  => $WCDO_woocommerce_order_items , 
           "comment_type"  => $WCDO_comment_type , 
           "postmeta"  => $WCDO_postmeta , 
           "posts"  => $WCDO_posts , 
           "backup"  => $backup ,
           "plugin_version" => "FREE"
        );
      

        return json_encode($array_results);
    }


    function func_WCDOp_get_results_to_insert_sql ( $results , $tabla , $retorno_carro ) {
        $str_insert = "";

        // CABECERA
            $str_cabecera .= " INSERT INTO " . $GLOBALS['table_prefix'] . $tabla . " ( " ;
            $registro = $results[0] ;
            $concatener = "";
            foreach ( $registro as $clave => $valor ) {
                $str_cabecera .= $concatener . $clave  ;
                $concatener = " , ";
            }
            $str_cabecera .= ") ";

        // DATOS
            foreach ($results as &$registro) {
              $str_insert .= $str_cabecera . " VALUES ( " ;
              $concatener = "";    
              foreach ( $registro as $clave => $valor ) {
                  $linea_dato = print_r ( $valor , true ) ;
                  $str_insert .= $concatener . '"' . $linea_dato . '" ';
                  $concatener = " , ";
              }
              $str_insert .= " ); " . $retorno_carro ;
            }

        return $str_insert  ;
    }



