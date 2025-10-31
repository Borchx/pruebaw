<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 1001 );
function theme_enqueue_styles() {
	etheme_child_styles();
}

add_filter( 'woocommerce_cart_item_name', 'dl_imagen_producto_checkout', 9999, 3 );
  
function dl_imagen_producto_checkout( $name, $cart_item, $cart_item_key ) {
    if ( ! is_checkout() ) return $name;
    $product = $cart_item['data'];
    $thumbnail = $product->get_image( array( '80', '53' ), array( 'class' => 'alignleft' ) );
    return $thumbnail . $name;
}
add_action('wp_footer', 'custom_talla_message_script');
function custom_talla_message_script() {
?>
<script>
    jQuery(document).ready(function($) {
        function showTallaMessage() {
            var selectedTalla = $('select#pa_size').val();
            var tallaError = $('#talla-error');

            if (tallaError.length) {
                tallaError.remove();
            }

            if (!selectedTalla) {
                $('<span id="talla-error" style="color: red;padding-left: 10px;font-size: 16px;">Choose your size!</span>').insertAfter('select#pa_size');
            }
        }

        showTallaMessage(); // Ejecutar al cargar la página

        $('body').on('change', 'select#pa_size', function() {
            showTallaMessage(); // Ejecutar al cambiar la selección de talla
        });
    });
</script>
<?php
}
