<?php



    function func_WCDOp_ajax_deleterow_execute ( $post_date , $post_status , $post_export_backup ){

        global $wpdb;

      

        $backup = "";

        $retorno_carro = "\\n";

      

        if ( $post_status == "wc-all") { $post_status = "";}

        if ( strtolower ( $post_export_backup ) == "true" ) { $post_export_backup = true; } else { $post_export_backup = false; }

      

        // woocommerce_order_itemmeta

            $wc_sql = " DELETE FROM " . $GLOBALS['table_prefix'] . "woocommerce_order_itemmeta 

            INNER JOIN " . $GLOBALS['table_prefix'] . "woocommerce_order_items 

                ON " . $GLOBALS['table_prefix'] . "woocommerce_order_itemmeta.order_item_id = " . $GLOBALS['table_prefix'] . "woocommerce_order_items.order_item_id 

            INNER JOIN " . $GLOBALS['table_prefix'] . "posts 

                ON " . $GLOBALS['table_prefix'] . "woocommerce_order_items.order_id = " . $GLOBALS['table_prefix'] . "posts.ID ";

              $wc_sql .= " WHERE post_date < '" . $post_date . "' ";

              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }

              $wc_sql .= " ))";

            $WCDO_woocommerce_order_itemmeta = $wpdb->query( $wc_sql);

      

      

      

        // woocommerce_order_items

            $wc_sql = " DELETE FROM " . $GLOBALS['table_prefix'] . "woocommerce_order_items 

            INNER JOIN " . $GLOBALS['table_prefix'] . "posts 

                ON " . $GLOBALS['table_prefix'] . "woocommerce_order_items.order_id = " . $GLOBALS['table_prefix'] . "posts.ID ";

              $wc_sql .= " WHERE post_date < '" . $post_date . "' ";

              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }

              $wc_sql .= " )";

            $WCDO_woocommerce_order_items = $wpdb->query( $wc_sql);



      

        // commentsmeta

            $wc_sql = " DELETE FROM " . $GLOBALS['table_prefix'] . "commentmeta  

            INNER JOIN " . $GLOBALS['table_prefix'] . "comments 

                ON " . $GLOBALS['table_prefix'] . "commentmeta.comment_id = " . $GLOBALS['table_prefix'] . "comments.comment_id

            INNER JOIN " . $GLOBALS['table_prefix'] . "posts 

                ON " . $GLOBALS['table_prefix'] . "comments.comment_post_ID = " . $GLOBALS['table_prefix'] . "posts.ID

            WHERE " . $GLOBALS['table_prefix'] . "comments.comment_type = 'order_note' AND ";

        $wc_sql .= " post_date < '" . $post_date . "' ";

            if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }

            $wc_sql .= " ))";

            $WCDO_commentsmeta = $wpdb->query( $wc_sql);   

      

      

        // comment_type  

            $wc_sql = " DELETE FROM " . $GLOBALS['table_prefix'] . "comments 

            INNER JOIN " . $GLOBALS['table_prefix'] . "posts 

                ON " . $GLOBALS['table_prefix'] . "comments.comment_post_ID = " . $GLOBALS['table_prefix'] . "posts.ID

            WHERE comment_type = 'order_note' ";    

        $wc_sql .= " AND post_date < '" . $post_date . "' ";

              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }

              $wc_sql .= " )";

            $WCDO_comment_type = $wpdb->query( $wc_sql);

      



      

        // postmeta

            $wc_sql = " DELETE FROM " . $GLOBALS['table_prefix'] . "postmeta 

            INNER JOIN " . $GLOBALS['table_prefix'] . "posts 

                ON " . $GLOBALS['table_prefix'] . "postmeta.post_id = " . $GLOBALS['table_prefix'] . "posts.ID ";

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

           "commentsmeta"  => $WCDO_commentsmeta ,

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
            if ( $post_export_backup ) {
                $field_sql = "*";
            } else {
                $field_sql = "count(meta_id)";
            }

            $wc_sql = "SELECT ".$field_sql." FROM " . $GLOBALS['table_prefix'] . "woocommerce_order_itemmeta 

                INNER JOIN " . $GLOBALS['table_prefix'] . "woocommerce_order_items 

                    ON " . $GLOBALS['table_prefix'] . "woocommerce_order_itemmeta.order_item_id = " . $GLOBALS['table_prefix'] . "woocommerce_order_items.order_item_id 

                INNER JOIN " . $GLOBALS['table_prefix'] . "posts 

                    ON " . $GLOBALS['table_prefix'] . "woocommerce_order_items.order_id = " . $GLOBALS['table_prefix'] . "posts.ID ";

                

            $wc_sql .= " WHERE post_date < '" . $post_date . "' ";

              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }

              $wc_sql .= " ";

            
            // $backup .= $wc_sql . "  " . $retorno_carro ;

            if ( $post_export_backup ) {
                $results = $wpdb->get_results( $wc_sql , OBJECT );
                $WCDO_woocommerce_order_itemmeta = count($results);

                $backup .= " -- " . $retorno_carro ;
                $backup .= " -- " . $GLOBALS['table_prefix'] . "woocommerce_order_itemmeta " . $retorno_carro ;
                $backup .= " -- " . $retorno_carro ;

                $backup .= func_WCDOp_get_results_to_insert_sql ( $results , "woocommerce_order_itemmeta"  , $retorno_carro) . $retorno_carro ;
            } else {
                $WCDO_woocommerce_order_itemmeta = $wpdb->get_var( $wc_sql );
            }

      

      

      

        // woocommerce_order_items
            if ( $post_export_backup ) {
                $field_sql = "*";
            } else {
                $field_sql = "count(order_item_id)";
            }

            $wc_sql = "SELECT ".$field_sql." FROM " . $GLOBALS['table_prefix'] . "woocommerce_order_items 

                INNER JOIN " . $GLOBALS['table_prefix'] . "posts 

                    ON " . $GLOBALS['table_prefix'] . "woocommerce_order_items.order_id = " . $GLOBALS['table_prefix'] . "posts.ID ";



            $wc_sql .= " WHERE post_date < '" . $post_date . "' ";

              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }

              $wc_sql .= " ";

            if ( $post_export_backup ) {
                $results = $wpdb->get_results( $wc_sql , OBJECT );
                $WCDO_woocommerce_order_items = count($results);

                $backup .= " -- " . $retorno_carro ;
                $backup .= " -- " . $GLOBALS['table_prefix'] . "woocommerce_order_items " . $retorno_carro ;
                $backup .= " -- " . $retorno_carro ;

                $backup .= func_WCDOp_get_results_to_insert_sql ( $results , "woocommerce_order_items"  , $retorno_carro) . $retorno_carro ;
            } else {
                $WCDO_woocommerce_order_items = $wpdb->get_var( $wc_sql );
            }

      

      

        // commentsmeta

            if ( $post_export_backup ) {
                $field_sql = "*";
            } else {
                $field_sql = "count(meta_id)";
            }

            $wc_sql = "SELECT ".$field_sql." FROM " . $GLOBALS['table_prefix'] . "commentmeta  

                INNER JOIN " . $GLOBALS['table_prefix'] . "comments 

                    ON " . $GLOBALS['table_prefix'] . "commentmeta.comment_id = " . $GLOBALS['table_prefix'] . "comments.comment_id

                INNER JOIN " . $GLOBALS['table_prefix'] . "posts 

                    ON " . $GLOBALS['table_prefix'] . "comments.comment_post_ID = " . $GLOBALS['table_prefix'] . "posts.ID

                WHERE " . $GLOBALS['table_prefix'] . "comments.comment_type = 'order_note' AND ";

            $wc_sql .= " post_date < '" . $post_date . "' ";

            if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }

            $wc_sql .= " ";      

            if ( $post_export_backup ) {
                $results = $wpdb->get_results( $wc_sql , OBJECT );
                $WCDO_commentsmeta = count($results);

                $backup .= " -- " . $retorno_carro ;
                $backup .= " -- " . $GLOBALS['table_prefix'] . "commentmeta " . $retorno_carro ;
                $backup .= " -- " . $retorno_carro ;

                $backup .= func_WCDOp_get_results_to_insert_sql ( $results , "commentmeta"  , $retorno_carro) . $retorno_carro ;
            } else {
                $WCDO_commentsmeta = $wpdb->get_var( $wc_sql );
            }



      

        // comment_type
            if ( $post_export_backup ) {
                $field_sql = "*";
            } else {
                $field_sql = "count(comment_ID)";
            }

            $wc_sql = "SELECT ".$field_sql." FROM " . $GLOBALS['table_prefix'] . "comments 

                INNER JOIN " . $GLOBALS['table_prefix'] . "posts 

                    ON " . $GLOBALS['table_prefix'] . "comments.comment_post_ID = " . $GLOBALS['table_prefix'] . "posts.ID

                WHERE comment_type = 'order_note' ";    

            $wc_sql .= " AND post_date < '" . $post_date . "' ";

              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }

              $wc_sql .= " ";      


            if ( $post_export_backup ) {
                $results = $wpdb->get_results( $wc_sql , OBJECT );
                $WCDO_comment_type = count($results);

                $backup .= " -- " . $retorno_carro ;
                $backup .= " -- " . $GLOBALS['table_prefix'] . "comments " . $retorno_carro ;
                $backup .= " -- " . $retorno_carro ;

                $backup .= func_WCDOp_get_results_to_insert_sql ( $results , "comments"  , $retorno_carro) . $retorno_carro ;
            } else {
                $WCDO_comment_type = $wpdb->get_var( $wc_sql );
            }



      

        // postmeta
            if ( $post_export_backup ) {
                $field_sql = "*";
            } else {
                $field_sql = "count(meta_id)";
            }

            $wc_sql = "SELECT ".$field_sql." FROM " . $GLOBALS['table_prefix'] . "postmeta 

                INNER JOIN " . $GLOBALS['table_prefix'] . "posts 

                    ON " . $GLOBALS['table_prefix'] . "postmeta.post_id = " . $GLOBALS['table_prefix'] . "posts.ID ";



            $wc_sql .= " WHERE  post_type = 'shop_order' AND post_date < '" . $post_date . "' ";

              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }

              $wc_sql .= " ";  

            if ( $post_export_backup ) {
                $results = $wpdb->get_results( $wc_sql , OBJECT );
                $WCDO_postmeta = count($results);

                $backup .= " -- " . $retorno_carro ;
                $backup .= " -- " . $GLOBALS['table_prefix'] . "postmeta " . $retorno_carro ;
                $backup .= " -- " . $retorno_carro ;

                $backup .= func_WCDOp_get_results_to_insert_sql ( $results , "postmeta"  , $retorno_carro) . $retorno_carro ;
            } else {
                $WCDO_postmeta = $wpdb->get_var( $wc_sql );
            }

      

      

        // posts 

            if ( $post_export_backup ) {
                $field_sql = "*";
            } else {
                $field_sql = "count(ID)";
            }

            $wc_sql = "SELECT ".$field_sql." FROM " . $GLOBALS['table_prefix'] . "posts ";

              $wc_sql .= " WHERE post_type = 'shop_order' AND post_date < '" . $post_date . "' ";

              if ( !empty( $post_status ) ) { $wc_sql .= " AND  post_status = '" . $post_status . "' "; }

            if ( $post_export_backup ) {
                $results = $wpdb->get_results( $wc_sql , OBJECT );
                $WCDO_posts = count($results);

                $backup .= " -- " . $retorno_carro ;
                $backup .= " -- " . $GLOBALS['table_prefix'] . "posts " . $retorno_carro ;
                $backup .= " -- " . $retorno_carro ;

                $backup .= func_WCDOp_get_results_to_insert_sql ( $results , "posts"  , $retorno_carro) . $retorno_carro ;
            } else {
                $WCDO_posts = $wpdb->get_var( $wc_sql );
            }
      



        $array_results = array(

           "woocommerce_order_itemmeta"  => $WCDO_woocommerce_order_itemmeta , 

           "woocommerce_order_items"  => $WCDO_woocommerce_order_items , 

           "comment_type"  => $WCDO_comment_type , 

           "commentsmeta"  => $WCDO_commentsmeta , 

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







