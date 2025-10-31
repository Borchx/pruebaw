(function($){
  
  $(document).on('click','#WCDO_submit_code',function(e){
      var objWCDO = consultar_licencia();
        jQuery("#WCDO_site_url_reg").val( objWCDO["WCDO_site_url_reg"]);
        jQuery("#WCDO_date_reg").val(objWCDO["WCDO_date_reg"]);
        if ( jQuery("#WCDO_site_url").val() != objWCDO["WCDO_site_url_reg"] ) {
            jQuery("#WCDO_estado").val( "ERROR URL");
        } else {
            jQuery("#WCDO_estado").val( objWCDO["WCDO_estado"]);
        }
  });
  
  if ( jQuery("#WCDO_purchase_code").val() != "" && jQuery("body.settings_page_WCDO_settings_page")[0]) {
      var objWCDO = consultar_licencia();
        jQuery("#WCDO_site_url_reg").val( objWCDO["WCDO_site_url_reg"]);
        jQuery("#WCDO_date_reg").val(objWCDO["WCDO_date_reg"]);
        if ( jQuery("#WCDO_site_url").val() != objWCDO["WCDO_site_url_reg"] ) {
            jQuery("#WCDO_estado").val( "ERROR URL");
        } else {
            jQuery("#WCDO_estado").val( objWCDO["WCDO_estado"]);
        }
  }
  
  
  
function consultar_licencia() {
    var objWCDO;
        $.ajax({
          url : WCDOp_vars.ajaxurl,
          type: 'post',
          async: false,
          data: {
            action : 'TBBL_ajax_submit_code',
            post_url: jQuery("#WCDO_site_url").val(),
            post_admin_email: jQuery("#WCDO_admin_email_reg").val(),
            post_code : jQuery("#WCDO_purchase_code").val()
          },
          beforeSend: function(){
              $(".WCDO-loading-plugin").show();
          },
          success: function(resultado){
              $(".WCDO-loading-plugin").hide();
              objWCDO = JSON.parse(resultado);
          }

        });  
    return objWCDO;
}
  
})(jQuery);
