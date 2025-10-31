<?php



/**



 * Plugin Name: TBBL-woocommerce-delete-orders-PREMIUM (WCDO)



 * Plugin URI: https://theblackboxlab.com/tienda/woocommerce-delete-orders-premium/



 * Description: Borrado masivo de pedidos con un simple click / Delete woocommerce's orders with a single click.



 * Version: 1.0.6



 * Author: The Black Box Lab



 * Text Domain: TBBL-woocommerce-delete-orders-PREMIUM



 * Domain Path: /languages/



 * Author URI: https://theblackboxlab.com/



 * Requires at least: 4.0



 * Tested up to: 5.8.2



 */







if (!is_admin()) {



  return;



}







defined( 'ABSPATH' ) || exit;



define('WCDOp_DIR' , plugin_dir_path(__FILE__));



define('WCDOp_DIR_url' , plugins_url( 'TBBL-woocommerce-delete-orders-PREMIUM/' ) );







//Load text domain for languages



load_plugin_textdomain('TBBL-woocommerce-delete-orders-PREMIUM', false, dirname(plugin_basename( __FILE__ )).'/languages/');







// TBBL Version Controller



require WCDOp_DIR.'plugin-update-checker/plugin-update-checker.php';



$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(



	'https://wordpress-license.theblackboxlab.com/plugins/TBBL-woocommerce-delete-orders-PREMIUM/plugin.json',



	__FILE__, //Full path to the main plugin file or functions.php.



	'TBBL-woocommerce-delete-orders-PREMIUM'



);







// MENU CONFIGURACION



    include_once ( WCDOp_DIR . '/includes/WCDO-setting-plugin.php'  );







// INSERT CSS



    add_action( 'admin_menu', 'WCDOp_css' ); 



    function WCDOp_css() {



        wp_register_style( 'TBBL-woocommerce-delete-orders-PREMIUM', WCDOp_DIR_url . '/public/css/TBBL-woocommerce-delete-orders-PREMIUM.css' , array(), '1.18' );



        wp_enqueue_style( 'TBBL-woocommerce-delete-orders-PREMIUM' );



      



        //https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css







    }







//Insertar Javascript js y enviar ruta admin-ajax.php



    add_action('admin_menu', 'WCDOp_js');



    function WCDOp_js(){







        // WCDOp_script



          wp_register_script('WCDOp_submit_code', WCDOp_DIR_url . '/admin/js/TBBL-Submit-code.js' , array('jquery'), '0.02', true );



          wp_enqueue_script ('WCDOp_submit_code');



          wp_localize_script('WCDOp_submit_code','WCDOp_vars',['ajaxurl'=>admin_url( 'admin-ajax.php' )]);      







        // WCDOp_script



          wp_register_script('WCDOp_script', WCDOp_DIR_url . '/public/js/TBBL-woocommerce-delete-orders-PREMIUM.js' , array('jquery'), '0.53', true );



          wp_enqueue_script ('WCDOp_script');



          wp_localize_script('WCDOp_script','WCDOp_vars',['ajaxurl'=>admin_url( 'admin-ajax.php' )]);







        // sweetalert



          wp_register_script( 'sweetalert', 'https://cdn.jsdelivr.net/npm/sweetalert2@10', null, null, true );



          wp_enqueue_script('sweetalert');



    }











// INSERTA AJAX



    include_once ( WCDOp_DIR . '/includes/ajax-woocommerce-delete-orders-list.php'  );



    include_once ( WCDOp_DIR . '/includes/ajax-TBBL-Submit-code.php'  );



  



// INSERT MENU WCDO



    add_action( 'admin_menu', 'WCDOp_menu_page' );



    function WCDOp_menu_page() {



      add_menu_page(



        'woocommerce-delete-orders', // page <title>Title</title>



        'WCDO', // menu link text



        'manage_options', // capability to access the page



        'WCDO-page', // page URL slug



        'WCDOp_page_content', // callback function /w content



        'dashicons-database-remove', // menu icon



        5 // priority



      );



    }











// FORM MENU WCDO



    function WCDOp_page_content(){



    settings_fields( 'WCDOp_plugin_settings' );



    do_settings_sections( 'WCDOp_plugin' );



  



	  echo '<h1>WooCommerce Delete Orders (WCDO) <span style="color: #d2a504;">PREMIUM</span></h1>';







    WCDOp_alertas ( __("Please, select a date", "TBBL-woocommerce-delete-orders-PREMIUM"), "WCDOp_Fde", "error", true);



    WCDOp_alertas ( __("Premium version functionality", "TBBL-woocommerce-delete-orders-PREMIUM"), "WCDOp_FuncionPremium", "error", true);



      



    $option = get_option( 'WCDO_multi_form_data' );



    $purchase_code  = esc_attr( $option['purchase_code'] );



?>



<input type='hidden' id='WCDO_site_url' disabled value='<?php echo get_site_url(); ?>' />



<input type='hidden' id='WCDO_admin_email_reg' disabled value='<?php echo get_option("admin_email"); ?>' />



<input type='hidden' id='WCDO_site_url_reg' disabled value='UNREGISTERED' />            



<input type='hidden' id='WCDO_date_reg' disabled value='UNREGISTERED' />



<input type='hidden' id='WCDO_estado' disabled value='ACTIVO' />



<input type='hidden' id='WCDO_purchase_code' disabled value='<?php echo $purchase_code; ?>' />



<input type="hidden" id="table_prefix" value="<?php echo $GLOBALS['table_prefix']; ?>">







        <div >



            <div id="WCDO-Filters" >



                <div class="WCDO-Filtro">



                      <label for="WCDOp_post_date"><?php _e("Search orders before date:", "TBBL-woocommerce-delete-orders-PREMIUM"); ?></label>



                      <br>



                      <input type="date" id="WCDOp_post_date" name="post_date">      



                </div>







                <div class="WCDO-Filtro ">



                      <label for="WCDOp_post_status"><?php _e("Status", "TBBL-woocommerce-delete-orders-PREMIUM"); ?>  </label>



                      <br>



                      <select name="WCDOp_post_status" id="WCDOp_post_status" class="postform">



                        <option value="wc-all"><?php _e("All", "TBBL-woocommerce-delete-orders-PREMIUM"); ?></option>



                        <?php



                            foreach (wc_get_order_statuses() as $estado => $descripcion) {



                            ?>



                                <option value="<?php echo $estado; ?>"><?php echo $descripcion; ?></option>



                            <?php



                            }



                        ?>



                      </select>      



                </div>







                <div class="WCDO-Filtro">



                      <br>      



                      <input



                        id="WCDOp_btfiltrar"



                        type="button"



                        class="button"



                        value="<?php _e("Filter", "TBBL-woocommerce-delete-orders-PREMIUM"); ?>"



                      />      



                </div>







                <div class="WCDO-Filtro_checkbox">



                      <br>      



                      <input id="WCDOp_checkbox" type="checkbox" class="checkbox" />



                      <label for="WCDOp_checkbox">  <?php _e("Make Backup", "TBBL-woocommerce-delete-orders-PREMIUM"); ?> </label>



                </div>







                <div class="WCDO-Filtro">



                      <br>  



                      <span id=WCDOp_msg>*<?php _e("Please, select a date", "TBBL-woocommerce-delete-orders-PREMIUM"); ?></span>     



                </div>







            </div>          







            <div id="WCDO-Borrar">



                <br>



                <div class="WCDO-div-delete">



                      <input



                        id="WCDOp_delete"



                        type="button"



                        class="button button-primary"



                        value="<?php _e("Delete", "TBBL-woocommerce-delete-orders-PREMIUM"); ?>"



                      />              



                </div>              



            </div>   



        </div>



        



        <img id="WCDO-loading" src="<?php echo WCDOp_DIR_url . '/public/images/LoadAjax.gif' ; ?>">



          



        <div id="WCDO-list" class="wrap woocommerce">



            <table id="WCDO-tablelist" class="wp-list-table widefat fixed striped table-view-list posts">



              <thead>



                <tr>



                  <th id="WDCO-th-titulo"><?php _e("Table", "TBBL-woocommerce-delete-orders-PREMIUM"); ?></th>



                  <th id="WDCO-th-dato"><?php _e("Records", "TBBL-woocommerce-delete-orders-PREMIUM"); ?></th>



                </tr>



              </thead>



              <tbody>



                <tr>



                  <td>woocommerce_order_itemmeta</td>



                  <td class="WDCO-td-dato" id="tabreg-woocommerce_order_itemmeta">0</td>



                </tr>



                <tr>



                  <td>woocommerce_order_items</td>



                  <td class="WDCO-td-dato" id="tabreg-woocommerce_order_items">0</td>



                </tr>



                <tr>



                  <td>comments</td>



                  <td class="WDCO-td-dato" id="tabreg-comments">0</td>



                </tr>



                <tr>



                  <td>commentsmeta</td>



                  <td class="WDCO-td-dato" id="tabreg-commentsmeta">0</td>



                </tr>



                <tr>



                  <td>postmeta</td>



                  <td class="WDCO-td-dato" id="tabreg-postmeta">0</td>



                </tr>



                <tr>



                  <td>posts</td>



                  <td class="WDCO-td-dato" id="tabreg-posts">0</td>



                </tr>



              </tbody>



            </table>



          



            <input



              id="WCDOp_save_backup"



              type="button"



              class="button button-primary"



              value="<?php _e("Save", "TBBL-woocommerce-delete-orders-PREMIUM"); ?> "



            />                  



            <br>



            <textarea class="WCDO-100prc" id="WCDO-backup"> </textarea>



        </div>



          



<?php







}







    function WCDOp_alertas ( $texto, $id , $tipoalerta = "error" , $isdismissable = false ){



        /*



          $tipoalerta = "updated"



          $tipoalerta = "error"



          $tipoalerta = "update-nag"



        */







          switch ( $tipoalerta ) {



            case "updated":



                break;







            case "update-nag":



                break;







            default:



              $tipoalerta = "error";



          }







          if ( $isdismissable ) {



              $class_isdismissable = "is-dismissable";



          } else {



              $class_isdismissable = "";



          }







          ?>



          <div id="<?php echo $id; ?>" class="<?php echo $tipoalerta; ?> notice <?php echo $class_isdismissable; ?> ">



              <p><?php echo $texto; ?></p>



          </div>



          <?php



    }



