(function($){

  

  $(document).on('click','#WCDOp_checkbox',function(e){



      if(document.getElementById('WCDOp_checkbox').checked) {

          $("#WCDOp_save_backup").show();

          $("#WCDO-backup").show();

      } else {

          $("#WCDOp_save_backup").hide();

          $("#WCDO-backup").hide();

      }



  });

  

	$(document).on('click','#WCDOp_btfiltrar',function(e){

	 	e.preventDefault();

    var WCDO_estado = $("#WCDO_estado").val();

    if ( WCDO_estado != "ACTIVO") {

            Swal.fire({

              icon: 'error',

              title: 'LICENSE ERROR',

              text: 'Please check your license in plugin settings page'

            })

    } else {

            var fecha = $("#WCDOp_post_date").val();

            var status = $("#WCDOp_post_status").val();

            var export_backup = $('#WCDOp_checkbox').is(':checked');

            $("#WCDOp_Fde").hide();

            $("#error-consulta").remove();

            if ( fecha === "" || fecha === undefined ) {

                $("#WCDOp_Fde").show("fast");

            } else {      

                $.ajax({

                  url : WCDOp_vars.ajaxurl,

                  type: 'post',

                  data: {

                    action : 'WCDOp_ajax_readrow',

                    post_date: fecha,

                    post_status: status,

                    post_export_backup : export_backup

                  },

                  beforeSend: function(){

                      $("#WCDO-loading").show();

                      $("#WCDO-list").hide();

                      $("#WCDOp_Fde").hide();

                      $("#WCDOp_FuncionPremium").hide();

                      $("#WCDO-backup").val("");

                  },

                  success: function(resultado){

                      $("#WCDO-loading").hide();

                      if (resultado.includes("error")){

                        $('<div id="error-consulta" style="text-align: center;display: block;margin-top: 100px;font-size: 24px;font-weight: 700;color: #ab0404;">Ups, seems the server returns 500 error. Please, try to limit your query, with a later date or with an order status.</div>').insertAfter("#WCDO-list");

                      } else {

                        var objWCDO = JSON.parse(resultado);

                            $("#tabreg-woocommerce_order_itemmeta").html ( objWCDO["woocommerce_order_itemmeta"] ); 

                            $("#tabreg-woocommerce_order_items").html ( objWCDO["woocommerce_order_items"] ); 

                            $("#tabreg-comments").html ( objWCDO["comment_type"] ); 

                            $("#tabreg-commentsmeta").html ( objWCDO["commentsmeta"] ); 

                            $("#tabreg-postmeta").html ( objWCDO["postmeta"] ); 

                            $("#tabreg-posts").html ( objWCDO["posts"] );

                            var backupdata = objWCDO["backup"];

                            backupdata = backupdata.replaceAll("\\n", "\n");

                            $("#WCDO-backup").val ( backupdata );

          /*

                        if ( objWCDO["plugin_version"] == "FREE" && $("#WCDOp_post_status").val() == "wc-all") {

                            $("#WCDOp_FuncionPremium").hide();

                            $("#WCDOp_FuncionPremium").show("fast");

                        } 

          */



                        $("#WCDO-list").show();

                        display_bt_delete ();

                      }

                    },

                    error: function (jqXHR, textStatus, errorThrown) {

                      console.log('Error Message: ' + textStatus);

                      console.log('HTTP Error: ' + errorThrown);

                      $('<div id="error-consulta" style="text-align: center;display: block;margin-top: 100px;font-size: 24px;font-weight: 700;color: #ab0404;">Ups, seems the server returns 500 error. Please, try to limit your query, with a later date or with an order status.</div>').insertAfter("#WCDO-list");

                    }



                });

            }

      

    }

	});



  $(document).on('click','#WCDOp_save_backup',function(e){

    var WCDO_estado = $("#WCDO_estado").val();

    if ( WCDO_estado != "ACTIVO") {

            Swal.fire({

              icon: 'error',

              title: 'LICENSE ERROR',

              text: 'Please check your license in plugin settings page'

            })

    } else {

      

          var data = $("#WCDO-backup").val ();

              data = data.replace(/\r\n|\r|\n/g,"</br>");

          var fecha = $("#WCDOp_post_date").val();

          var status = $("#WCDOp_post_status").val();    



          if ( fecha === "" || fecha === undefined ) {

              $("#WCDOp_Fde").show("fast");

          } else {

              $("#WCDOp_Fde").hide();

              descargarArchivo(generarTexto(data), "WCDO-" + fecha + "-" + status + ".sql");

          }



    }    



  });



  $(document).on('click','#WCDOp_delete',function(e){

    var WCDO_estado = $("#WCDO_estado").val();

    if ( WCDO_estado != "ACTIVO") {

            Swal.fire({

              icon: 'error',

              title: 'LICENSE ERROR',

              text: 'Please check your license in plugin settings page'

            })

    } else {



          e.preventDefault();

          var fecha = $("#WCDOp_post_date").val();

          var status = $("#WCDOp_post_status").val();

          var export_backup = $('#WCDOp_checkbox').is(':checked');



          if ( fecha === "" || fecha === undefined ) {

              $("#WCDOp_Fde").show("fast");

          } else {

              Swal.fire({

                title: 'Are you sure?',

                text: "Do you want to delete the records?",

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#3085d6',

                cancelButtonColor: '#d33',

                confirmButtonText: 'Yes'

              }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({

                      url : WCDOp_vars.ajaxurl,

                      type: 'post',

                      data: {

                        action : 'WCDOp_ajax_deleterow',

                        post_date: fecha,

                        post_status: status,

                        post_export_backup : export_backup

                      },

                      beforeSend: function(){

                          $("#WCDO-loading").show();

                          $("#WCDO-list").hide();

                          $("#WCDOp_Fde").hide();

                          $("#WCDO-backup").val("");

                      },

                      success: function(resultado){

                          $("#WCDO-loading").hide();

                          var objWCDO = JSON.parse(resultado);

                              $("#tabreg-woocommerce_order_itemmeta").html ( objWCDO["woocommerce_order_itemmeta"] ); 

                              $("#tabreg-woocommerce_order_items").html ( objWCDO["woocommerce_order_items"] ); 

                              $("#tabreg-comments").html ( objWCDO["comment_type"] ); 

                              $("#tabreg-commentsmeta").html ( objWCDO["commentsmeta"] ); 

                              $("#tabreg-postmeta").html ( objWCDO["postmeta"] ); 

                              $("#tabreg-posts").html ( objWCDO["posts"] );



                          $("#WCDO-list").show();

                          display_bt_delete ();

                          Swal.fire(

                            'Deleted!',

                            'Your records has been deleted.',

                            'success'

                          )



                      }



                    });

                }

              })



          }    

    }



  });

  

})(jQuery);



function display_bt_delete () {

    var display_bt_delete = false; 

    if ( !display_bt_delete ) {

        if ( jQuery("#tabreg-woocommerce_order_itemmeta").html () == "0" ){

            display_bt_delete = true; 

        } 

    }

  

    if ( !display_bt_delete ) {

        if ( jQuery("#tabreg-woocommerce_order_items").html () == "0" ){

            display_bt_delete = true; 

        } 

    }

  

    if ( !display_bt_delete ) {

        if ( jQuery("#tabreg-comments").html () == "0" ){

            display_bt_delete = true; 

        } 

    }

  

    if ( !display_bt_delete ) {

        if ( jQuery("#tabreg-postmeta").html () == "0" ){

            display_bt_delete = true; 

        } 

    }

  

    if ( !display_bt_delete ) {

        if ( jQuery("#tabreg-posts").html () == "0" ){

            display_bt_delete = true; 

        } 

    }



    display_bt_delete = true; 

  

    if ( display_bt_delete ) {

        jQuery("#WCDOp_delete").show();

    } else {

        jQuery("#WCDOp_delete").hide();

    }

  

}



function descargarArchivo(contenidoEnBlob, nombreArchivo) {

  //creamos un FileReader para leer el Blob

  var reader = new FileReader();

  //Definimos la funci�n que manejar� el archivo

  //una vez haya terminado de leerlo

  reader.onload = function (event) {

    //Usaremos un link para iniciar la descarga

    var save = document.createElement('a');

    save.href = event.target.result;

    save.target = '_blank';

    //Truco: as� le damos el nombre al archivo

    save.download = nombreArchivo || 'archivo.dat';

    var clicEvent = new MouseEvent('click', {

      'view': window,

      'bubbles': true,

      'cancelable': true

    });

    //Simulamos un clic del usuario

    //no es necesario agregar el link al DOM.

    save.dispatchEvent(clicEvent);

    //Y liberamos recursos...

    (window.URL || window.webkitURL).revokeObjectURL(save.href);

  };

  //Leemos el blob y esperamos a que dispare el evento "load"

  reader.readAsDataURL(contenidoEnBlob);

}



function generarTexto(datos) {

  datos = datos.replaceAll ( "</br>" , "\n");

  var texto = [];

  texto.push( datos );

  return new Blob(texto, {

    type: 'text/plain'

  });

}