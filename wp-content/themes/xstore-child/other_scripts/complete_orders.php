<?php

$token = $_GET['token'];

if ($token == "SNEAKER_3456d43"){
    //Añadimos librería de Wordpress
    require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

    //Recorremos todos los pedidos de WooCommerce con estado "processing" o "pending" y actualizamos el estado a "completed"
    $orders = wc_get_orders( array(
        'status' => array('processing', 'pending'),
        'limit' => -1
    ) );
    foreach ( $orders as $order ) {
        $order->update_status( 'completed' );
    }

    echo "Pedidos completados correctamente";
} else {
    echo "Token incorrecto";
}

?>


