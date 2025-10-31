<?php

function WCDO_add_pages() {
    //Add Settings Page
    add_options_page(
        'WCDO Settings', //Page Title
        __( 'WCDO Settings', 'TBBL-woocommerce-delete-orders-PREMIUM' ), //Menu Title
        'manage_options', //capability
        'WCDO_settings_page', //menu slug
        'WCDO_settings_page_content' //The function to be called to output the content for this page.
    );
}
add_action( 'admin_menu', 'WCDO_add_pages' );

function WCDO_multi_form_init() {
    register_setting(
        'WCDO_multi_form',  // A settings group name. 
                            // Must exist prior to the register_setting call. 
                            // This must match the group name in settings_fields()
        'WCDO_multi_form_data' //The name of an option to sanitize and save.
    );

  /*
  // APICODE
      add_settings_section( 
        'WCDO_section_APICODE', 
        ' ', 
        'WCDO_section_callback_APICODE', 
        'WCDO_settings_page' 
      );

      add_settings_field( 
        'WCDO_section_APICODE', 
        'API CODE', 
        'field_callback_APICODE', 
        'WCDO_settings_page', 
        'WCDO_section_APICODE' 
      );
*/
  
  // Purchase_code
      add_settings_section( 
        'WCDO_section_purchase_code', 
        ' ', 
        'WCDO_section_callback_Purchase_code', 
        'WCDO_settings_page' 
      );

      add_settings_field( 
        'WCDO_section_purchase_code', 
        __('REGISTERED E-MAIL', "TBBL-woocommerce-delete-orders-PREMIUM"), 
        'field_callback2', 
        'WCDO_settings_page', 
        'WCDO_section_purchase_code' 
      );

}
add_action( 'admin_init', 'WCDO_multi_form_init' );


function WCDO_plugin_fields() {
  // register_setting( 'WCDO-settings-menu', 'field_APICODE' );
  register_setting( 'WCDO-settings-menu', 'field_purchase_code' );
   
  // add_settings_section( 'WCDO_section_APICODE', 'My Section Title 1', false, 'WCDO-settings-menu' );
  add_settings_section( 'WCDO_section_purchase_code', 'My Section Title 2', false, 'WCDO-settings-menu' );

  // add_settings_field( 'field_APICODE', 'Field 1', 'field_callback_APICODE' , 'WCDO-settings-menu', 'WCDO_section_APICODE' );
  add_settings_field( 'field_purchase_code', 'Field 2', 'field_callback2' , 'WCDO-settings-menu', 'WCDO_section_purchase_code' );

}

function WCDO_section_callback_APICODE() {
    _e("The e-mail you used in your purchase.", "TBBL-woocommerce-delete-orders-PREMIUM");
}

function WCDO_section_callback_Purchase_code() {
    _e("The e-mail you used in your purchase.", "TBBL-woocommerce-delete-orders-PREMIUM");
}

/*
function field_callback_APICODE() {
    $option = get_option( 'WCDO_multi_form_data' );
    $APICODE   = esc_attr( $option['APICODE'] );
    echo "<input type='text' name='WCDO_multi_form_data[APICODE]' disabled value='".$APICODE."' />";
}
*/

function field_callback2() {
    $option = get_option( 'WCDO_multi_form_data' );
    $purchase_code  = esc_attr( $option['purchase_code'] );
    echo "<input id='WCDO_purchase_code' type='text' name='WCDO_multi_form_data[purchase_code]' value='".$purchase_code."' />";
}



/* Settings Page Content */
function WCDO_settings_page_content() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have enought permissions to access this page.', 'TBBL-woocommerce-delete-orders-PREMIUM' ) );
    }

    ?>
    <div class="wrap">
              <img id="TBBL-Logo" src="<?php echo WCDOp_DIR_url . '/public/images/logo_TBBL.png' ; ?>">
        <h2><?php _e("Settings API WCDO", "TBBL-woocommerce-delete-orders-PREMIUM"); ?></h2>
        <?php
        $option = get_option( 'WCDO_multi_form_data' );
        ?>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'WCDO_multi_form' );
            do_settings_sections( 'WCDO_settings_page' );
  
            ?>
            <h2><?php _e("License information", "TBBL-woocommerce-delete-orders-PREMIUM"); ?></h2>
            <span class="WCDO_span_reg"><?php _e("Current site", "TBBL-woocommerce-delete-orders-PREMIUM"); ?>: </span>
              <input class="WCDO_input_reg" type='text' id='WCDO_site_url' name='WCDO_site_url' disabled value='<?php echo get_site_url(); ?>' />
            <br>            
          
            <span class="WCDO_span_reg"><?php _e("Activated site", "TBBL-woocommerce-delete-orders-PREMIUM"); ?>:</span>
              <img class="WCDO-loading-plugin" src="<?php echo WCDOp_DIR_url . '/public/images/LoadAjax.gif' ; ?>">
              <input class="WCDO_input_reg" type='text' id='WCDO_site_url_reg' name='WCDO_site_url_reg' disabled value='UNREGISTERED' />            
            <br>            
          
            <span class="WCDO_span_reg"><?php _e("E-Mail", "TBBL-woocommerce-delete-orders-PREMIUM"); ?>:</span>
              <input class="WCDO_input_reg" type='text' id='WCDO_admin_email_reg' name='WCDO_admin_email_reg' disabled value='<?php echo get_option("admin_email"); ?>' />
            <br>
          
            <span class="WCDO_span_reg"><?php _e("License date", "TBBL-woocommerce-delete-orders-PREMIUM"); ?>:</span>
              <img class="WCDO-loading-plugin" src="<?php echo WCDOp_DIR_url . '/public/images/LoadAjax.gif' ; ?>">
              <input class="WCDO_input_reg" type='text' id='WCDO_date_reg' name='WCDO_date_reg' disabled value='UNREGISTERED' />
            <br>
          
            <span class="WCDO_span_reg"><?php _e("Status", "TBBL-woocommerce-delete-orders-PREMIUM"); ?>:</span>
              <img class="WCDO-loading-plugin" src="<?php echo WCDOp_DIR_url . '/public/images/LoadAjax.gif' ; ?>">
              <input class="WCDO_input_reg" type='text' id='WCDO_estado' name='WCDO_estado' disabled value='UNREGISTERED' />
          
            <?php 
              $other_attributes = array( 'id' => 'WCDO_submit_code' );
              submit_button( __( "Register", "TBBL-woocommerce-delete-orders-PREMIUM" ), 'primary', 'submit', true, $other_attributes );

            ?>
          
        </form>
    </div>
<?php
} //End WCDO_settings_page_content


